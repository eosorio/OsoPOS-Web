<?  /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- 

 Caja Web 0.02-1. Módulo de caja de OsoPOS Web.

        Copyright (C) 2000,2001 Eduardo Israel Osorio Hernández
        iosorio@elpuntodeventa.com

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
{
  
?>
<!-- bodies/caja_web/cobro.bdy -->
<?
  $art = array(new articulosClass);

if ($mode == "express") {
  $item_list = explode("\n", $lista_arts);
  for ($j=0; $j<count($item_list)-1; $j++) {
	$art[$j] = new articulosClass; /* Asi deben ser las variables*/
	$it = explode("|", $item_list[$j]);
	$art[$j]->codigo = trim($it[0]);

	$art[$j]->pu = trim($it[2]);
	$subtotal += $art[$j]->pu;

	$art[$j]->iva_porc = trim($it[3]);
	$iva += ($art[$j]->iva_porc/100 * $art[$j]->pu);
	if ($art[$j]->codigo == $art[$j-1]->codigo  && $art[$j]->desc == $art[$j-1]->desc) {
	  $art[$j]->cant += 1;
	  continue;
	}
	else
	  $art[$j]->cant = 1;
	$art[$j]->desc = trim($it[1]);
	$art[$j]->disc = trim($it[4]);

  /* Colocar aquí el código para interpretar el área de texto */
  }
}

?>

<body bgcolor="white" background="imagenes/fondo.gif" onload="document.pago.efectivo.focus()">

<form action="caja_web_imprime.php" method="post" name="pago">
<h4>Indique la forma de pago</h4>

<table width="100%">
<tbody>
 <tr>
  <td align="center" width="5%"><input type=radio name=pago value=20 checked>
  <td>Efectivo
  <td align="right">$<input type=hidden name=php_anterior value="<? echo $PHP_SELF ?>">
  <td><small><input type=text
  name=efectivo size=10></small>
  <td colspan=5>&nbsp;<input type=hidden name=nm_ticket value="<? echo $nm_ticket ?>">
  <td><font color=blue><input type=submit value="Cobrar"></font>
 <tr>
  <input type=hidden name=cheque value=<? printf("%.2f", $subtotal) ?>>
  <td align="center" width="5%"><input type=radio name=pago value=21>
  <td>Cheque
  <td align="right"><small>N&uacute;mero</small>
  <td><small><input type=text name=cheque_num size=4></small>
  <td align="right"><small>Cuenta</small>
  <td colspan=3><small><input type=text name=cheque_cta size=30></small>
  <td align="right"><small>Banco</small>
  <td><small><input type=text name=cheque_banco size=10></small>
 <tr>
  <input type=hidden name="credito" value=<? printf("%.2f", $subtotal) ?>>
  <td align="center" width="5%"><input type=radio name=pago value=2>
  <td>A cr&eacute;dito
  <td align="right"><small>Autorizaci&oacute;n</small>
  <td><small><input type="text" name="credito_aut" size=7></small>
 <tr>
  <input type=hidden name=tarjeta value=<? printf("%.2f", $subtotal) ?>>
  <td align="center" width="5%"><input type=radio name=pago value=1>
  <td>Con tarjeta
  <td align="right"><small>Num.</small>
  <td colspan=2><input type=text name=tarjeta_num size=15></small>
  <td align="right"><small>Banco</small>
  <td><small><input type=text name=tarjeta_banco size=10></small>
  <td align="right"><small>Autorizaci&oacute;n</small>
  <td colspan=2><small><input type=text name=tarjeta_aut size=15></small>
</tbody>
</table>
<hr>
<table width="100%" columns=2 border=0>
<tr>
 <td width="50%">
 <table width="100%" border=0>
  <tr>
   <td colspan=2><h4>Indique el comprobante a imprimir</h4>
  <tr>
   <td width="5%"><input type=radio name=comprobante value=1 checked>
   <td>Nota de mostrador
  <tr>
   <td width="5%"><input type=radio name=comprobante value=2>
   <td>Factura
  <tr>
   <td width="5%"><input type=radio name=comprobante value=5>
   <td>Ticket
 </table>
 <td widht="50%">
  <table border=0 width="100%">
  <tr>
   <td colspan=2>
     <h4>Datos de la venta</h4>
  <tr>
   <td>Núm. vendedor
   <td><input type="text" name="id_vendor" size=4>
  <tr>
   <td colspan=2>&nbsp;
  <tr>
   <td colspan=2>&nbsp;
  </table>
</table>
<hr>
<center>
<font size=+3>
<font color="green">Total a pagar: <? printf("%.2f", $subtotal+$iva) ?></font><br>
<?php echo str_cant($subtotal+$iva, $centavos) . "pesos $centavos"?>/100 M.N.<br>
<input type=hidden name=total value=<? printf("%.2f", $subtotal+$iva) ?>>
</font>
</center>
<br>

<?
  /* Si los datos vienen como array de la página anterior. Se omite conversion a articulosClass */
  if ($mode != "express") {
	for ($i=0; $i<count($articulo_codigo); $i++) {
	  echo "<input type=hidden name=articulo_codigo[] value=\"" . $articulo_codigo[$i] . "\">\n";
	  echo "<input type=hidden name=articulo_descripcion[] value=\"" . $articulo_descripcion[$i] . "\">\n";
	  printf ("<input type=hidden name=articulo_cantidad[] value=%d>\n", $articulo_cantidad[$i]);
	  printf ("<input type=hidden name=articulo_pu[] value=%.2f>\n", $articulo_pu[$i]);
	  printf ("<input type=hidden name=articulo_iva_porc[] value=%.2f>\n", $articulo_iva_porc[$i]);
	  printf ("<input type=hidden name=articulo_disc[] value=%.2f>\n", $articulo_disc[$i]);
	}
  }
  else {
	for ($i=0; $i<count($art); $i++) {
	  echo "<input type=hidden name=articulo_codigo[] value=\"" . $art[$i]->codigo . "\">\n";
	  echo "<input type=hidden name=articulo_descripcion[] value=\"" . $art[$i]->desc . "\">\n";
	  printf ("<input type=hidden name=articulo_cantidad[] value=%d>\n", $art[$i]->cant);
	  printf ("<input type=hidden name=articulo_pu[] value=%.2f>\n", $art[$i]->pu);
	  printf ("<input type=hidden name=articulo_iva_porc[] value=%.2f>\n", $art[$i]->iva_porc);
	  printf ("<input type=\"hidden\" name=\"articulo_disc[]\" value=%.2f>\n", $art[$i]->disc);
	}
  }

 echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">\n";
}
?>


</form>
</BODY>
</HTML>
