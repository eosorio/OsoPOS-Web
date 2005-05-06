<?php /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Corte Web. M�dulo de corte de OsoPOS Web.

        Copyright (C) 2003 Eduardo Israel Osorio Hern�ndez

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los t�rminos de la Licencia P�blica General GNU
publicada por la Free Software Foundation: ya sea en la versi�n 2 de la
Licencia, o (a su elecci�n) en una versi�n posterior. 

        Este programa es distribuido con la esperanza de que sea �til, pero
SIN GARANTIA ALGUNA; incluso sin la garant�a impl�cita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. V�ase la Licencia P�blica General
GNU para mayores detalles. 

        Deber�a usted haber recibido una copia de la Licencia P�blica General
GNU junto con este programa; de no ser as�, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 

*/
?>
<hr>

<font class="item_tit">Generaci�n de corte</font><br>
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="radio" name="parcial" value="0"> Corte de d�a<br>
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
<option value="<?php echo $NOTA_MOSTRADOR ?>">Remisi�n
<option value="<?php echo $FACTURA ?>">Factura
</select><br>
Num. cajero: <input type="text" name="num_cajero" size=2 value=0><br>
Num. primera venta: <input type="text" name="primera_v" size=8 value=0>
Num. �ltima venta: <input type="text" name="ultima_v" size=8 value=0><br>
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

