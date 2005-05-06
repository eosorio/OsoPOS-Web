<?php /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Corte Web. Módulo de corte de OsoPOS Web.

        Copyright (C) 2003 Eduardo Israel Osorio Hernández

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
<hr>

<font class="item_tit">Generación de corte</font><br>
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="radio" name="parcial" value="0"> Corte de día<br>
<input type="radio" name="parcial" value="1" checked> Corte parcial de todos los cajeros<br>
<input type="radio" name="parcial" value="2"> Corte parcial de cajero:
<input type="text" name="cashier_id" size=3><br>
<input type="hidden" name="accion" value="genera_corte">
<input type="submit" value="OK">
</form>

<hr>

<font class="item_tit">Resumen de ventas</font><br>
<form action="<?php echo $PHP_SELF ?>" method="post">
Tipo de comprobante: <select name="tipo_recibo">
<option value="<?php echo $TEMPORAL ?>">Ticket
<option value="<?php echo $NOTA_MOSTRADOR ?>">Remisión
<option value="<?php echo $FACTURA ?>">Factura
</select><br>
Num. cajero: <input type="text" name="num_cajero" size=2 value=0><br>
Num. primera venta: <input type="text" name="primera_v" size=8 value=0>
Num. última venta: <input type="text" name="ultima_v" size=8 value=0><br>
<input type="hidden" name="accion" value="muestra_comprobantes">
<?php echo checklist_forma_pago($conn, $forma_pago) ?>
<input type="submit" value="Generar reporte"><br>
</form>

<hr>

<font class="item_tit">Detalle de ventas</font><br>
<form action="<? echo $PHP_SELF ?>" method="post">
Mostrar desde la venta <input type="text" size=6 name="from">
 hasta la venta <input type="text" size=6 name="to">
<input type="hidden" name="accion" value="detalle">
<input type="submit" value="Mostrar">
</form>

