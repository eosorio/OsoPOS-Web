Introduzca los datos de los art&iacute;culos a facturar. Los renglones vac&iacute;os no aparecer&aacute;n en la factura.

<form action="<? echo $PHP_SELF ?>" method=post>
<table width="450" border="0" cols="5" cellspacing="0" cellpadding="0">
<tbody>
<tr>
 <th><small>Código</small></th>
 <th><small>Cantidad</small></th>
 <th><small>Descripción</small></th>
 <th><small>P. unitario</small></th>
 <th><small>% IVA</small></th>
 <th><small>% IEPS</small></th>


<? for ($i=0; $i<$ART_MAXRENS; $i++) { ?>
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
   <input type=text	name="iva_porc[]" size=2 value="<?echo $IVA_PORCENTAJE ?>"></small>

  <td align=center height=10>
   <small>
   <input type=text	name="tax_0_porc[]" size=2 value="<?echo $IEPS_PORCENTAJE ?>"></small>

<? } /* for */ ?>

 <tr>
  <td colspan=6 align=right>
   <input type=submit value="Continuar">


</tbody>
</table>

<input type="hidden" name=id value="<? echo $id ?>">
<input type="hidden" name=dia value="<? echo $dia ?>">
<input type="hidden" name=mes value="<? echo $mes ?>">
<input type="hidden" name=anio value="<? echo $anio ?>">
<input type="hidden" name=fase value="<? printf ("%d", $fase) ?>">
<input type="hidden" name=razon_soc value="<? echo $razon_soc ?>">
<input type="hidden" name=rfc value="<? echo $rfc ?>">
<input type="hidden" name="accion" value="mostrar">
<input type="hidden" name="existe_venta" value=1>
<input type="hidden" name=curp value="<? echo $curp ?>">
<input type="hidden" name=dom_ext value="<? echo $dom_ext ?>">
<input type="hidden" name=dom_int value="<? echo $dom_int ?>">
<input type="hidden" name=dom_calle value="<? echo $dom_calle ?>">
<input type="hidden" name=dom_col value="<? echo $dom_col ?>">
<input type="hidden" name=dom_cp value="<? echo $dom_cp ?>">
<input type="hidden" name=dom_ciudad value="<? echo $dom_ciudad ?>">
<input type="hidden" name=dom_edo value="<? echo $dom_edo ?>">
<input type="hidden" name="id_venta" value="<? echo $id_venta ?>">

</form>
