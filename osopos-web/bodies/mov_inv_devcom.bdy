<h2>Almacén <? echo $almacen ?></h2>
<b>Movimiento <? printf("%d", $id_mov) ?></b><br>
<form action="<? echo $PHP_SELF ?>" method="post">
<table border=0>
<tr>
  <th>Código</th><th>Cant.</th><th>P. unitario</th>
  <th>Tipo de movimiento</th><th>Alm. dest.</th><th>&nbsp;</th>
</tr>
<? for ($i=0; $i<15; $i++) { ?>
<tr>
  <td><input type="text" name="codigo[]" size=20 maxlength=20></td>
  <td><input type="text" name="ct[]" size=3></td>
  <td><input type="text" name="pu[]" size=8></td>
  <td><? lista_movimientos($conn) ?></td>
  <td><input type="text" name="alm_dest[]" size=3></td>
</tr>
<? } ?>
</table>
<input type="hidden" name="almacen" value=<? echo "$almacen" ?>>
<input type="hidden" name="accion" value="detalles">
<input type="submit" value="Agregar">
<?
  if (isset($id_mov))
   printf("<input type=\"hidden\" name=\"id_mov\" value=%d>\n", $id_mov);
?>
</form>
