<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */  ?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
 <title>OsoPOS - Caja Web v.  <? echo $caja_web_vers ?></title>
</head>

<body bgcolor="white" background="imagenes/fondo.gif" onload="document.confirmar.cambio.focus()">

<?
{

  include("include/minegocio.inc");

  if ($pago >= 20) {
    if ($succes = open_drawer() > 0)
      echo "<b>Error: No puedo activar el cajón</b><br>\n";
  }

  if ($comprobante==1) {
	if ($pago == 20)
	  $monto = $efectivo;
	else
	  $monto = $total;

	Crea_Ticket($nm_ticket, $id_venta, $articulo_codigo, $articulo_descripcion,
				$articulo_cantidad, $articulo_pu, $articulo_iva_porc, $articulo_disc,
				$pago, $monto, 0);

	$impresion = popen($CMD_IMPRESION . $nm_ticket, "w");
	if (!$impresion) {
	  echo "<b>Error al ejecutar <i>$CMD_IMPRESION $nm_ticket</i></b><br>\n";
	}
	else {
	  echo "<center><i>Nota impresa con comando $CMD_IMPRESION $nm_ticket.</i></center>\n";
	  pclose($impresion);
	}
  }
  else if ($comprobante==5) {
	if ($pago == 20)
	  $monto = $efectivo;
	else
	  $monto = $total;

	Crea_Ticket($nm_ticket, $id_venta, $articulo_codigo, $articulo_descripcion,
				$articulo_cantidad, $articulo_pu, $articulo_iva_porc, $articulo_disc,
				$pago, $monto, 1);

	$impresion = @popen($CMD_IMPRESION . $nm_ticket, "w");
	if (!$impresion) {
	  echo "<b>Error al ejecutar <i>$CMD_IMPRESION $nm_ticket</i></b><br>\n";
	}
	else {
      pclose($impresion);
      $pie_ticket = @fopen($ARCHIVO_PIE_TICKET, "r");
      if (!$pie_ticket) {
        echo "<i>Advertencia: No puedo leer el pie de ticket</i>\n";
        $tmp_name = tempnam("/tmp", "pieticket");
        $pie_ticket = fopen($tmp_name, "w");
        fputs($pie_ticket, "         Gracias por su compra.\n\n");
        fputs($pie_ticket, " Este es un sistema de elpuntodeventa.com\n");
        fputs($pie_ticket, "        http://elpuntodeventa.com\n");
        fputs($pie_ticket, "           Tel. (9)626-5040\n\n\n");
        fclose($pie_ticket);
        if ($impresion = popen($CMD_IMPRESION . $tmp_name, "w"))
          pclose($impresion);
        unlink($tmp_name);
        imprime_ticket_razon();
      }
      else {
        $impresion = popen($CMD_IMPRESION . $ARCHIVO_PIE_TICKET, "w");
        if (!$impresion) {
          echo "<b>Error al ejecutar <i>$CMD_IMPRESION $ARCHIVO_PIE_TICKET</i></b><br>\n";
        }
        else {
          echo "<center><i>Ticket impreso con comando $CMD_IMPRESION $nm_ticket.</i></center>\n";
          pclose($impresion);
          imprime_ticket_razon();
        }
      }
    }
  }

?>

<form action="<? echo $php_anterior ?>" method=post name="confirmar">
<table border=0 cellpadding=0 cellspacing=0>
<tbody>
<tr>
 <td colspan=2><h3>

<?
 if ($comprobante==5)
   echo "Corte el papel y a";
 else
   echo "A";
 ?>priete el botón o
 presione <i>Intro</i> para continuar</h3></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
 <td>
  <?
  if($pago>=20) {
	printf("Cambio de $<input type=text size=10 name=cambio value=%.2f>", $efectivo-$total);
  }
  else
    echo "&nbsp;";
 ?>
 </td>
 <td align="right"><input type=submit value="Continuar"></td>
</tr>
</tbody>
</table>
<input type="hidden" name="mode" value="<? echo $mode ?>">
<input type="hidden" name="imprime_cabecera" value=1>

</form>

  <? } ?>
</body>
</html>
