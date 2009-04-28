<?php  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */  ?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
 <title>OsoPOS - Caja Web v.  <?php echo $caja_web_vers ?></title>
</head>

<body bgcolor="white" background="imagenes/fondo.gif" onload="document.confirmar.cambio.focus()">

<?php
{

  include("include/minegocio.inc");

  if ($pago >= 20) {
    if ($succes = open_drawer() > 0)
      echo "<b>Error: No puedo activar el cajón</b><br>\n";
  }

  if ($comprobante==$TCOMP_NOTA) {
	if ($pago == 20)
	  $monto = $efectivo;
	else
	  $monto = $total;

	Crea_Ticket($nm_ticket, $id_venta, $articulo_codigo, $articulo_descripcion,
				$articulo_cantidad, $articulo_pu, $articulo_iva_porc, $articulo_disc,
				$pago, $monto, 0);

        $comando = sprintf("%s -P %s %s", $CMD_IMPRESION, $COLA_NOTA, $nm_ticket);
	$impresion = popen($comando, "w");
	if (!$impresion) {
	  echo "<b>Error al ejecutar <i>$comando</i></b><br>\n";
	}
	else {
	  echo "<center><i>Nota impresa con comando $comando.</i></center>\n";
	  pclose($impresion);
	}
  }
  else if ($comprobante==$TCOMP_TICKET) {
	if ($pago == 20)
	  $monto = $efectivo;
	else
	  $monto = $total;

	Crea_Ticket($nm_ticket, $id_venta, $articulo_codigo, $articulo_descripcion,
				$articulo_cantidad, $articulo_pu, $articulo_iva_porc, $articulo_disc,
				$pago, $monto, 0);

        $comando = sprintf("%s -P %s %s", $CMD_IMPRESION, $COLA_TICKET, $nm_ticket);
	$impresion = @popen($comando, "w");
	if (!$impresion) {
	  echo "<b>Error al ejecutar <i>$comando</i></b><br>\n";
	}
	else {
      pclose($impresion);
      $pie_ticket = @fopen($ARCHIVO_PIE_TICKET, "r");
      if (!$pie_ticket) {
        echo "<i>Advertencia: No puedo leer el pie de ticket</i>\n";
        $tmp_name = tempnam("/tmp", "pieticket");
        $pie_ticket = fopen($tmp_name, "w");
        fputs($pie_ticket, "         Gracias por su compra.\n\n");
        fputs($pie_ticket, "     Este es un sistema de\n          elpuntodeventa.com\n");
        fputs($pie_ticket, "        http://elpuntodeventa.com\n");
        fputs($pie_ticket, "        Tel. (962)626-5040\n\n\n");
        fclose($pie_ticket);
        $comando = sprintf("%s -P %s %s", $CMD_IMPRESION, $COLA_TICKET, $tmp_name);
        if ($impresion = popen($comando, "w"))
          pclose($impresion);
        unlink($tmp_name);
        imprime_ticket_razon();
      }
      else {
        $comando = sprintf("%s -P %s %s", $CMD_IMPRESION, $COLA_TICKET, $ARCHIVO_PIE_TICKET);
        $impresion = popen($comando, "w");
        if (!$impresion) {
          echo "<b>Error al ejecutar <i>$comando</i></b><br>\n";
        }
        else {
          echo "<center><i>Ticket impreso con comando $comando.</i></center>\n";
          pclose($impresion);
          imprime_ticket_razon();
        }
      }
    }
  }

?>

<form action="<?php echo $php_anterior ?>" method="post" name="confirmar">
<table border=0 cellpadding=0 cellspacing=0>
<tbody>
<tr>
 <td colspan=2><h3>

<?php
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
	printf("Cambio de $<input type=text size=10 name=\"cambio\" value=%.2f>", $efectivo-$total);
  }
  else
    echo "&nbsp;";
 ?>
 </td>
 <td align="right"><input type="submit" value="Continuar"></td>
</tr>
</tbody>
</table>
<input type="hidden" name="mode" value="<?php echo $mode ?>">
<input type="hidden" name="imprime_cabecera" value=1>

</form>

  <?php } ?>
</body>
</html>
