<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */  ?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
 <title>OsoPOS - Caja Web v.  <? echo $caja_web_vers ?>
</head>
</html>
<body bgcolor="white" background="imagenes/fondo.gif" onload="document.confirmar.cambio.focus()">

<?
{

  include("include/linucs.inc");

  if ($comprobante==5) {
	if ($pago == 20)
	  $monto = $efectivo;
	else
	  $monto = $total;

	Crea_Ticket($nm_ticket, $articulo_codigo, $articulo_descripcion,
				$articulo_cantidad, $articulo_pu, $articulo_iva_porc, $articulo_disc,
				$pago, $monto);

	$impresion = popen($CMD_IMPRESION . $nm_ticket, "w");
	if (!$impresion) {
	  echo "<b>Error al ejecutar <i>$CMD_IMPRESION $nm_ticket</i></b><br>\n";
	}
	else {
	  echo "<center><i>Ticket impreso con comando $CMD_IMPRESION $nm_ticket.</i></center>\n";
	  pclose($impresion);
	}
  }


?>

<form action="<? echo $php_anterior ?>" method=post name="confirmar">
<table border=0 cellpadding=0 cellspacing=0>
<tbody>
<tr>
 <td colspan=2><font face="helvetica, arial" size="+1">

<?
 if ($comprobante==5)
   echo "Corte el papel y a";
 else
   echo "A";
 ?>priete el botón o
 presione <i>Intro</i> para continuar</font>
<tr><td>&nbsp;
<tr>
 <td><font face="helvetica, arial">
  <?
  if($pago>=20) {
	printf("Cambio de $<input type=text size=10 name=cambio value=%.2f>", $efectivo-$total);
  }
  else
    echo "&nbsp;";
 ?>
 </font>
 <td align="right"><font face="helvetica, arial" color=blue><input type=submit value="Continuar"></font>
</tbody>
</table>


</form>

  <? } ?>
</BODY>
</HTML>
