<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Busca cliente. Submódulo de facturación de OsoPOS Web.

        Copyright (C) 2000-2005 Eduardo Israel Osorio Hernández
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
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <style type="text/css">
     td.nm_campo {font-face: helvetica,arial; text-align: right}
   </style>
   <script>
   function ponPrefijo(id,rfc,curp,nombre,calle,exter,inter,col,ciudad,edo,cp){
	opener.document.forma_cliente.id_cliente.value=id
	opener.document.forma_cliente.rfc.value=rfc
	opener.document.forma_cliente.curp.value=curp
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

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method=post>
<table border=0 cellspacing=3 cellpadding=0 width="680">
<tbody>
 <tr>
  <td colspan=3><h4>Buscar cualquiera de estos datos en registro:</h4></td>
 </tr>
 <tr>
  <td class="nm_campo">Razón social:</td>
  <td><input type="text" name="razon_soc" size=50></td>
  <td><input type="submit" value="Encontrar cliente"></td>
 </tr>
 <tr>
  <td class="nm_campo">Nombre comercial:</td>
  <td><input type="text" name="nombre_comer" size=50></td>
 </tr>
 <tr>
  <td class="nm_campo">R.F.C.</td>
  <td><input type="text" name="rfc" size=13 maxlength=13></td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td class="nm_campo">C.U.R.P.</td>
  <td><input type="text" name="curp" <?php echo "size=\"$MAXCURP\" maxlength=\"$MAXCURP\"" ?>></td>
  <td>&nbsp</td>
 </tr>
 <tr>
  <td class="nm_campo">Nombre del contacto</td>
  <td><input type="text" name="contacto" size=30></td>
  <td>&nbsp</td>
 </tr>
 <tr>
  <td class="nm_campo"><input type="checkbox" name="dom_principal" value="1" checked></td>
  <td>Mostrar solo el domicilio principal</td>
  <td>&nbsp;</td>
 </tr>
</tbody>
</table>
<input type="hidden" name="id_venta" value="<?php echo $id_venta ?>">
<input type="hidden" name="php_anterior" value="<?php echo $php_anterior ?>">
<input type="hidden" name="id" value="<?php echo $id ?>">
<input type="hidden" name="fase" value=2>
</form>
<hr>
<div align="right">
<font face="helvetica,arial" size="+1">
<a href="#" onclick="JavaScript: window.close();">Regresar</a>
</div>

<?php /***********  fase dos **********/

if ($_POST['fase'] == 2) {

  $rfc = $_POST['rfc'];
  $curp = $_POST['curp'];
  $contacto = $_POST['contacto'];
  $razon_soc = $_POST['razon_soc'];
  $nombre_comer = $_POST['nombre_comer'];

//   //igm  $ = $_POST[''];

  if (empty($rfc) && empty($curp) && empty($contacto) && empty($razon_soc) && empty($contacto) && empty($nombre_comer)) {
	echo "<div class=\"error_nf\">Debe seleccionar al menos un criterio de búsqueda</div>\n";
	$fase = 1;
  }
  else {
	$query = "";

	$condicion = "";
	if (!empty($razon_soc))
	  $condicion = sprintf("cli.nombres~*'%s' ", $razon_soc);

	if (!empty($nombre_comer)) {
	  if (!empty($condicion))
		$condicion.= "OR ";
	  $condicion.= sprintf("cli.nombre_comer~*'%s' ", $nombre_comer);
	}

	if (!empty($rfc)) {
	  if (!empty($condicion))
		$condicion.= "OR ";
	  $condicion.= sprintf("cli.rfc~*'%s' ", $rfc);
	}

	if (!empty($contacto)) {
	  if (!empty($condicion))
		$condicion.= "OR ";
	  $condicion.= sprintf("cli.contacto~*'%s' ", $contacto);
	}

	if (!empty($curp)) {
	  if (!empty($condicion))
		$condicion.= "OR ";
	  $condicion[$i++]= sprintf("cli.curp~*'%s' ", $curp);
	}

	$query.= "SELECT cli.id, cli.rfc, cli.curp, cli.nombres, cli.ap_paterno, cli.ap_materno, ";
	$query.= "cli.contacto, d.dom_calle, d.dom_numero, d.dom_inter, d.dom_col, d.dom_mpo, ";
	$query.= "d.dom_ciudad, d.dom_edo_id, d.dom_cp, d.dom_pais_id, d.dom_telefono, d.dom_nombre, ";
    $query.= "cli.nombre_comer FROM clientes cli, domicilios d ";
	$query.= "WHERE d.id_cliente=cli.id ";
	if ($_POST['dom_principal'])
	  $query.= "AND cli.dom_principal=d.id ";
	$query.= sprintf("AND (%s) ", $condicion);

    //	/*igm*/ echo "<i>$query</i><br>\n";
	$result =  db_query($query, $conn);
	if (!$result) {
	  die("<div class=\"error_f\">Error al consultar datos de clientes y domicilios</div>\n");
	}
	$num_ren = db_num_rows($result);

	if ($num_ren > 0) {
?>

Seleccione uno de los registros siguientes:<br>
<table border=0 cellspaciing=0 cellpadding=0 width="100%">
<tr>
 <th>I.D.</th>
 <th>R.F.C.</th>
 <th>Nombre</th>
 <th>Pseudónimo</th>
 <th>Domicilio</th>
</tr>

<?php
  for ($i=0; $i < $num_ren; $i++) {
	$r = db_fetch_object($result, $i);
    $nombre_completo = sprintf("%s %s %s", $r->nombres, $r->ap_paterno, $r->ap_materno);
	echo "<tr>\n";
	printf("  <td class=\"serie\"><a href=\"#\" onclick=\"ponPrefijo(%d,'%s','%s','%s',", $r->id, $r->rfc,
 		   $r->curp, $nombre_completo);
	printf("'%s','%s','%s','%s','%s','%s','%s')\">%s</a></td>\n",
		   $r->dom_calle, $r->dom_numero,
		   $r->dom_inter, $r->dom_col, $r->dom_ciudad, $r->dom_edo,
		   $r->dom_cp, $r->id);
	printf("  <td>%s</td>\n", $r->rfc);
	printf("  <td>%s</td>\n", $nombre_completo);
    printf("  <td>%s</td>\n", $r->nombre_comer);
    printf("  <td>%s</td>\n", $r->dom_nombre);
  }
  echo " </tr>\n";
?>

</table>


<?php
	}
	else
	  echo "No se encontró registro con ninguno de esos criterios<br>\n";
  }
}
db_close($conn);
?>

</body>
</html>

