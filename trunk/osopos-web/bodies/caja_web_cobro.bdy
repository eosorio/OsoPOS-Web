<?  /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- 

 Caja Web 0.02-1. Módulo de caja de OsoPOS Web.

        Copyright (C) 2000 Eduardo Israel Osorio Hernández
        iosorio@punto-deventa.com

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

 include("include/pos.inc"); 

?>

<body bgcolor="white" background="imagenes/fondo.gif" onload="document.pago.efectivo.focus()">

<form action=caja_web_imprime.php method=post name="pago">
<font face="helvetica, arial"><h4>Indique la forma de pago</h4></font>

<table width="100%">
<tbody>
 <tr>
  <td align="center" width="5%"><input type=radio name=pago value=20 checked>
  <td><font face="helvetica, arial">Efectivo</font>
  <td align="right">$<input type=hidden name=php_anterior value="<? echo $PHP_SELF ?>">
  <td><font face="helvetica, arial" size="-1"><input type=text
  name=efectivo size=10></font>
  <td colspan=5>&nbsp;<input type=hidden name=nm_ticket value="<? echo $nm_ticket ?>">
  <td><font face="helvetica, arial" color=blue><input type=submit value="Cobrar"></font>
 <tr>
  <input type=hidden name=cheque value=<? printf("%.2f", $subtotal) ?>>
  <td align="center" width="5%"><input type=radio name=pago value=21>
  <td><font face="helvetica, arial">Cheque</font>
  <td align="right"><font face="helvetica, arial" size="-1">N&uacute;mero</font>
  <td><font face="helvetica, arial" size="-1"><input type=text name=cheque_num size=4></font>
  <td align="right"><font face="helvetica, arial" size="-1">Cuenta</font>
  <td colspan=3><font face="helvetica, arial" size="-1"><input type=text name=cheque_cta size=30></font>
  <td align="right"><font face="helvetica, arial" size="-1">Banco</font>
  <td><font face="helvetica, arial" size="-1"><input type=text name=cheque_banco size=10></font>
 <tr>
  <input type=hidden name=credito value=<? printf("%.2f", $subtotal) ?>>
  <td align="center" width="5%"><input type=radio name=pago value=2>
  <td><font face="helvetica, arial">A cr&eacute;dito</font>
  <td align="right"><font face="helvetica, arial" size="-1">Autorizaci&oacute;n</font>
  <td><font face="helvetica, arial" size="-1"><input type=text name=credito_aut size=7></font>
 <tr>
  <input type=hidden name=tarjeta value=<? printf("%.2f", $subtotal) ?>>
  <td align="center" width="5%"><input type=radio name=pago value=1>
  <td><font face="helvetica, arial">Con tarjeta</font>
  <td align="right"><font face="helvetica, arial" size="-1">Num.</font>
  <td colspan=2><input type=text name=tarjeta_num size=15></font>
  <td align="right"><font face="helvetica, arial" size="-1">Banco</font>
  <td><font face="helvetica, arial" size="-1"><input type=text name=tarjeta_banco size=10></font>
  <td align="right"><font face="helvetica, arial" size="-1">Autorizaci&oacute;n</font>
  <td colspan=2><font face="helvetica, arial" size="-1"><input type=text name=tarjeta_aut size=15></font>
</tbody>
</table>
<hr>
<table width="100%" columns=2 border=0>
<tr>
 <td width="50%">
 <table width="100%" border=0>
  <tr>
   <td colspan=2><font face="helvetica, arial"><h4>Indique el comprobante a imprimir</h4></font>
  <tr>
   <td width="5%"><input type=radio name=comprobante value=1 checked>
   <td><font face="helvetica, arial">Nota de mostrador</font>
  <tr>
   <td width="5%"><input type=radio name=comprobante value=2>
   <td><font face="helvetica, arial">Factura</font>
  <tr>
   <td width="5%"><input type=radio name=comprobante value=5>
   <td><font face="helvetica, arial">Ticket</font>
 </table>
 <td widht="50%">
  <table border=0 width="100%">
  <tr>
   <td colspan=2>
     <font face="helvetica, arial"><h4>Datos de la venta</h4></font>
  <tr>
   <td><font face="helvetica, arial">Núm. vendedor</font>
   <td><font face="helvetica, arial"><input type=text name=id_vendor size=4></font>
  <tr>
   <td colspan=2>&nbsp;
  <tr>
   <td colspan=2>&nbsp;
  </table>
</table>
<hr>
<center>
<font size=+3 face="helvetica, arial">
<font color="green">Total a pagar: <? printf("%.2f", $subtotal) ?></font><br>
<?php echo str_cant($subtotal+$iva, $centavos) . "pesos $centavos"?>/100 M.N.<br>
<input type=hidden name=total value=<? printf("%.2f", $subtotal) ?>>
</font>
</center>
<br>

<?
  for ($i=0; $i<count($articulo_codigo); $i++) {
	echo "<input type=hidden name=articulo_codigo[] value=\"" . $articulo_codigo[$i] . "\">\n";
	echo "<input type=hidden name=articulo_descripcion[] value=\"" . $articulo_descripcion[$i] . "\">\n";
	printf ("<input type=hidden name=articulo_cantidad[] value=%d>\n", $articulo_cantidad[$i]);
	printf ("<input type=hidden name=articulo_pu[] value=%.2f>\n", $articulo_pu[$i]);
	printf ("<input type=hidden name=articulo_iva_porc[] value=%.2f>\n", $articulo_iva_porc[$i]);
	printf ("<input type=hidden name=articulo_disc[] value=%.2f>\n", $articulo_disc[$i]);
  }
  echo "<input type=hidden name=id_venta value=$id_venta>\n";
?>


</form>
</BODY>
</HTML>
