<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Busca cliente. Submódulo de facturación de OsoPOS Web.

        Copyright (C) 2000-2004 Eduardo Israel Osorio Hernández
        desarrollo@elpuntodeventa.com

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los términos de la Licencia Pública General GNU
publicada por la Free Software Foundation: ya sea en la versión 2 de la
Licencia, o (a su elección) en una versión posterior. 

        Este programa es distribuido con la esperanza de que sea útil, pero
SIN GARANTIA ALGUNA; incluso sin la garantía implícita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. Véase la Licencia Pública General
GNU para mayores detalles. 

        Debería usted haber recibido una copia de la Licencia Pública General
GNU junto con este programa; de no ser así, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 
*/

  include("include/general_config.inc");
  include("include/pos-var.inc");
  if (!empty($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - FacturWeb v. 0.06</title>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <style type="text/css">
    td#nm_campo {font-face: helvetica,arial}
   </style>
   <script>
   function ponPrefijo(rfc,nombre,calle,exter,inter,col,ciudad,edo,cp){
	opener.document.forma_cliente.rfc.value=rfc
	opener.document.forma_cliente.razon_soc.value=nombre
	opener.document.forma_cliente.dom_calle.value=calle
	opener.document.forma_cliente.dom_ext.value=exter
	opener.document.forma_cliente.dom_int.value=inter
	opener.document.forma_cliente.dom_col.value=col
	opener.document.forma_cliente.dom_cp.value=cp
	opener.document.forma_cliente.dom_edo.value=edo
	opener.document.forma_cliente.dom_ciudad.value=ciudad
	window.close()
   }
   </script>
</head>


<body>

<?php
  include("include/encabezado.inc");
?>

<form action="busca_cliente.php" method=post>
<table border=0 cellspacing=0 cellpadding=0 width="500">
<tbody>
 <tr>
  <td id="nm_campo" colspan=3><h4>Buscar estos datos en registro:</h4></td>
 </tr>
 <tr>
  <td id="nm_campo">Razón social:</td>
  <td id="nm_campo"><input type="text" name="razon_soc" size=50></td>
  <td id="nm_campo"><input type="submit" value="Encontrar cliente"><input type=hidden name=fase value=1></td>
 </tr>
 <tr>
  <td id="nm_campo">R.F.C.<input type="hidden" name="id_venta" value="<?php echo $id_venta ?>"></td>
  <td id="nm_campo"><input type="text" name="rfc" size=13 maxlength=13></td>
  <td id="nm_campo"><input type="hidden" name="php_anterior" value="<?php echo $php_anterior ?>">&nbsp;</td>
 </tr>
 <tr>
  <td id="nm_campo">C.U.R.P.</td>
  <td id="nm_campo"><input type="text" value="<?php echo $curp ?>" name="curp" <?php echo "size=\"$MAXCURP\" maxlength=\"$MAXCURP\"" ?>></td>
  <td id="nm_campo"><input type="hidden" name="id" value="<?php echo $id ?>">&nbsp<input type=hidden name=fase value=2></td>
 </tr>
</tbody>
</table>
</form>
<br>
<hr>
<div align="right">
<font face="helvetica,arial" size="+1">
<a href="#" onclick="JavaScript: window.close();">Regresar</a>
</div>

<?php /***********  fase dos **********/

if ($fase == 2) {
  $query = "";
  $datos = "?";

  if (strlen($razon_soc))
    if ($SQL_TYPE == "postgres")
      $query = "nombre~*'$razon_soc'";
    else
      $query = "nombre LIKE '%$razon_soc%'";
  if (strlen($rfc)) {
	if (strlen($query))
	  $query .= " AND ";
    if ($SQL_TYPE == "postgres")
      $query .= "cf.rfc~*'$rfc'";
    else
      $query = "cf.rfc LIKE '%$rfc%'";
  }
  if (strlen($curp)) {
	if (strlen($query))
	  $query .= " AND ";
    if ($SQL_TYPE == "postgres")
      $query .= "cf.curp~*'$curp'";
    else
      $query = "cf.curp LIKE '%$curp%'";
  }

  $query2 = "SELECT cf.*, fi.dom_calle, fi.dom_numero, fi.dom_inter, fi.dom_col, fi.dom_ciudad, ";
  $query2.= "fi.dom_edo, fi.dom_cp FROM clientes_fiscales cf, facturas_ingresos fi WHERE ";
  $query2.= sprintf("%s AND cf.rfc=fi.rfc ORDER BY fi.id DESC LIMIT 1", $query);

  $result =  db_query($query2, $conn);
  if (!$result) {
    die("Error al consultar datos de facturas de ingreso<br>" . db_errormsg($conn) . "<br>\n");
  }

?>

Seleccione uno de los registros siguientes:<br>
<table border=0 cellspaciing=0 cellpadding=0 width="100%">
 <th>R.F.C.</th>
 <th>Nombre</th>
 <th>C.U.R.P.</th>
 <tbody>

<?
  for ($i=0; $i<db_num_rows($result); $i++) {
	$renglon = db_fetch_object($result, $renglon);
	echo "<tr>\n";
	printf("  <td><a href=\"#\" onclick=\"ponPrefijo('%s','%s','%s','%s','%s','%s','%s','%s','%s')\">%s</a></td>\n",
               $renglon->rfc, $renglon->nombre, $renglon->dom_calle, $renglon->dom_numero,
               $renglon->dom_inter, $renglon->dom_col, $renglon->dom_ciudad, $renglon->dom_edo,
               $renglon->dom_cp, $renglon->rfc);
	printf("  <td>%s</td>\n", $renglon->nombre);
	printf("  <td>%s</td>\n", $renglon->curp);
  }
  echo " </tr>\n";
?>

</tbody>
</table>


<?
	}
?>

</body>
</html>

