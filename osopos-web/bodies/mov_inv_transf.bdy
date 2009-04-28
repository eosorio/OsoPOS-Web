<h2>Almacén <?php echo $almacen ?></h2>
<b>Movimiento <?php printf("%d", $id_mov) ?></b><br>
Tipo de movimiento <?php echo $tipo_mov ?> (transferencia)<br>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table border="0">
<tr>
  <th>Código</th><th>&nbsp;</th><th>Cant.</th><th>P. costo</th>
  <th>Alm. dest.</th><th>&nbsp;</th>
</tr>
<?php
{
  if (isset($osopos_carrito)) {
    $p_costo = lista_precio($conn, array_keys($osopos_carrito), 0);
    while (list ($nombre, $cant) = each ($osopos_carrito)) {

?>
  <td width="150"><input type="hidden" name="codigo[]" value="<?php echo $nombre ?>"><?php echo $nombre ?></td>
  <td width="400"><?php echo articulo_descripcion($conn, $nombre) ?></td>
  <td><input type="text" name="ct[]" size=5 value="<?php printf("%.2f", $cant) ?>"></td>
  <td class="moneda">
  <?php printf("<input type=\"hidden\" name=\"p_costo[]\" value=\"%.2f\">%.2f",$p_costo[$nombre], $p_costo[$nombre]) ?>
  </td>
  <td><input type="text" name="alm_dest[]" size=3></td>
</tr>
<?php
     }
  }
  else {
    for ($i=0; $i<15; $i++) { ?>
<tr>
   <td><input type="text" name="codigo[]" size=20 maxlength=20></td>
   <td>&nbsp;</td>
   <td><input type="text" name="ct[]" size=3></td>
   <td><input type="text" name="p_costo[]" size=8></td>
   <td><input type="text" name="alm_dest[]" size=3></td>
</tr>
<?php
   }
  }
 ?>
</table>
<input type="hidden" name="almacen" value=<?php echo "$almacen" ?>>
<input type="hidden" name="accion" value="detalles">
<input type="hidden" name="tipo_mov" value="<?php echo $tipo_mov ?>">
<input type="checkbox" name="imprimir" checked> Imprimir al finalizar<br>
<input type="submit" value="Agregar">
<?php
  if (isset($id_mov))
   printf("<input type=\"hidden\" name=\"id_mov\" value=%d>\n", $id_mov);
}
?>
</form>
