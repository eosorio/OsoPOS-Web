<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Busca cliente. Submódulo de facturación de OsoPOS Web.

        Copyright (C) 2000 Eduardo Israel Osorio Hernández
        infomres@elpuntodeventa.com

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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - FacturWeb v. 0.2</title>
   <style type="text/css">
    td#nm_campo {font-face: helvetica,arial}
   </style>
</head>


<body text="#000000" bgcolor="#FFFFFF" link="#0000EF" vlink="#51188E" alink="#FF0000" background="imagenes/fondo.gif">

<?
  include("include/general_config.inc");
  include("include/encabezado.inc");
  include("include/pos-var.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }

?>

<form action="busca_cliente.php" method=post>
<table border=0 cellspacing=0 cellpadding=0 width="500">
<tbody>
 <tr>
  <td id="nm_campo" colspan=3><h4>Buscar estos datos en registro:</h4></td>
 </tr>
 <tr>
  <td id="nm_campo">Razón social:</td>
  <td id="nm_campo"><input type=text name=razon_soc size=50></td>
  <td id="nm_campo"><input type=submit value="Encontrar cliente"><input type=hidden name=fase value=1></td>
 </tr>
 <tr>
  <td id="nm_campo">R.F.C.<input type=hidden name=id_venta value="<? echo $id_venta ?>"></td>
  <td id="nm_campo"><input type=text name=rfc size=13 maxlength=13></td>
  <td id="nm_campo"><input type=hidden name=php_anterior value="<? echo $php_anterior ?>">&nbsp;</td>
 </tr>
 <tr>
  <td id="nm_campo">C.U.R.P.</td>
  <td id="nm_campo"><input type=text value="<? echo $curp ?>" name=curp <? echo "size=\"$MAXCURP\" maxlength=\"$MAXCURP\"" ?>></td>
  <td id="nm_campo"><input type=hidden name=id value="<? echo $id ?>">&nbsp<input type=hidden name=fase value=2></td>
 </tr>
</tbody>
</table>
</form>
<br>
<hr>
<div align="right">
<font face="helvetica,arial" size="+1">
<a href="<? echo "$php_anterior?id_venta=$id_venta" ?>">Regresar</a>
</div>

<? /***********  fase dos **********/

if ($fase == 2) {
  $query = "";
  $datos = "?";

  if (strlen($razon_soc))
	$query = "nombre~*'$razon_soc'";
  if (strlen($rfc)) {
	if (strlen($query))
	  $query .= " AND ";
	$query .= "rfc~*'$rfc'";
  }
  if (strlen($curp)) {
	if (strlen($query))
	  $query .= " AND ";
	$query .= "curp~*'$curp'";
  }

  $query = "SELECT * FROM clientes_fiscales WHERE " . $query;
  $result =  pg_exec($conn, $query);
  if (!$result) {
	echo "Error al ejecutar $query<br>" . pg_errormessage($conn) . "<br>\n";
	exit();
  }

?>

Seleccione uno de los registros siguientes:<br>
<form action="<? echo $php_anterior ?>" method=post>
<table border=0 cellspaciing=0 cellpadding=0 width="100%">
 <th>&nbsp;
 <th>R.F.C.
 <th>Nombre
 <th>C.U.R.P.
 <tbody>

<?
  for ($i=0; $i<pg_numrows($result); $i++) {
	$renglon = pg_fetch_object($result, $renglon);
	echo "<tr>\n";
	echo "  <td><input type=radio name=rfc value=";
	printf ("\"%s|%s|%s\"", $renglon->rfc, $renglon->nombre, $renglon->curp);
	if (!$i)
	  echo " checked";
	echo "></td>\n";
	echo "  <td>" . $renglon->rfc . "</td>\n";
	echo "  <td>" . $renglon->nombre . "</td>\n";
	echo "  <td>" . $renglon->curp . "</td>\n";
  }
  echo " </tr>\n";
  echo " <tr>\n"; 
  echo "  <td colspan=4 align=center>\n";
  echo "   <input type=hidden name=decodifica_rfc value=1>\n";
  echo "   <input type=hidden name=id_venta value=\"$id_venta\">\n";
  echo "   <input type=submit value=\"Continuar captura\">\n";
  echo "   <input type=hidden name=\"REPEAT_FACT_DATA\" value=1>\n"; // Evita que se borre $rfc
  echo "  </td>\n";
  echo " </tr>\n";
?>

</tbody>
</table>
</form>

<?
	}
?>

</body>
</html>

