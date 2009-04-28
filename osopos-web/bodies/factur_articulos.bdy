Introduzca los datos de los art&iacute;culos a facturar. Los renglones vac&iacute;os no aparecer&aacute;n en la factura.

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method=post>
<table width="450" border="0" cols="5" cellspacing="0" cellpadding="0">
<tbody>
<tr>
 <th><small>Código</small></th>
 <th><small>Cantidad</small></th>
 <th><small>Descripción</small></th>
 <th><small>P. unitario</small></th>
 <th><small>% IVA</small></th>
 <th><small>% IEPS</small></th>


<?php for ($i=0; $i<$ART_MAXRENS; $i++) { ?>
 <tr>
  <td align=center height=10>
    <small><input type=text
    name="codigo[]" size=15 maxlength=20></small>

  <td align=center height=10>
   <small><input type=text
   name="cant[]" size=3></small>

  <td align=center height=10>
    <small><input type=text name="desc[]" size=50></small>

  <td align=center height=10>
   <small><input type=text name="pu[]" size=10></small>

  <td align=center height=10>
   <small>
   <input type=text	name="iva_porc[]" size=2 value="<?php echo $IVA_PORCENTAJE ?>"></small>

  <td align=center height=10>
   <small>
   <input type=text	name="tax_0_porc[]" size=2 value="<?php echo $IEPS_PORCENTAJE ?>"></small>

<?php } /* for */ ?>

 <tr>
  <td colspan=6 align=right>
   <input type=submit value="Continuar">


</tbody>
</table>

<input type="hidden" name=id value="<?php echo $id ?>">
<input type="hidden" name=dia value="<?php echo $dia ?>">
<input type="hidden" name=mes value="<?php echo $mes ?>">
<input type="hidden" name=anio value="<?php echo $anio ?>">
<input type="hidden" name=fase value="<?php printf ("%d", $fase) ?>">
<input type="hidden" name=razon_soc value="<?php echo $razon_soc ?>">
<input type="hidden" name=rfc value="<?php echo $rfc ?>">
<input type="hidden" name="accion" value="mostrar">
<input type="hidden" name="existe_venta" value=1>
<input type="hidden" name=curp value="<?php echo $curp ?>">
<input type="hidden" name=dom_ext value="<?php echo $dom_ext ?>">
<input type="hidden" name=dom_int value="<?php echo $dom_int ?>">
<input type="hidden" name=dom_calle value="<?php echo $dom_calle ?>">
<input type="hidden" name=dom_col value="<?php echo $dom_col ?>">
<input type="hidden" name=dom_cp value="<?php echo $dom_cp ?>">
<input type="hidden" name=dom_ciudad value="<?php echo $dom_ciudad ?>">
<input type="hidden" name=dom_edo value="<?php echo $dom_edo ?>">
<input type="hidden" name="id_venta" value="<?php echo $id_venta ?>">

</form>
