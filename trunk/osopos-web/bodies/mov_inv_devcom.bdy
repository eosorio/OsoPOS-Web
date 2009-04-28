<h2>Almacén <?php echo $almacen ?></h2>
<b>Movimiento <?php printf("%d", $id_mov) ?></b><br>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table border=0>
<tr>
  <th>Código</th><th>Cant.</th><th>P. unitario</th>
  <th>Tipo de movimiento</th><th>Alm. dest.</th><th>&nbsp;</th>
</tr>
<?php for ($i=0; $i<15; $i++) { ?>
<tr>
  <td><input type="text" name="codigo[]" size=20 maxlength=20></td>
  <td><input type="text" name="ct[]" size=3></td>
  <td><input type="text" name="pu[]" size=8></td>
  <td><?php lista_movimientos($conn) ?></td>
  <td><input type="text" name="alm_dest[]" size=3></td>
</tr>
<?php } ?>
</table>
<input type="hidden" name="almacen" value=<?php echo "$almacen" ?>>
<input type="hidden" name="accion" value="detalles">
<input type="submit" value="Agregar">
<?php
  if (isset($id_mov))
   printf("<input type=\"hidden\" name=\"id_mov\" value=%d>\n", $id_mov);
?>
</form>
