<!-- bodies/mov_inv_compra.bdy -->
<h2>Almacén <? echo $almacen ?></h2>
Num. movimiento <b><? printf("%d", $id_mov) ?></b>. Tipo de movimiento: compra</b><br>
<form action="<? echo $PHP_SELF ?>" method="post">
<table border=0>
<tr>
  <th>Código</th><th>Cant.</th><th>P. público</th>
</tr>
<? for ($i=0; $i<15; $i++) { ?>
<tr>
  <td><input type="text" name="codigo[]" size=20 maxlength=20></td>
  <td><input type="text" name="ct[]" size=3></td>
  <td><input type="text" name="pu[]" size=8></td>
  <td><input type="hidden" name="alm_dest[]" size=3 value=0></td>
</tr>
<? } ?>
</table>
<input type="hidden" name="almacen" value=<? echo "$almacen" ?>>
<input type="hidden" name="accion" value="detalles">
<input type="hidden" name="tipo_mov" value=<? printf("%d", $tipo_mov) ?>>
<input type="submit" value="Agregar">
<?
  if (isset($id_mov))
   printf("<input type=\"hidden\" name=\"id_mov\" value=%d>\n", $id_mov);
?>
</form>
