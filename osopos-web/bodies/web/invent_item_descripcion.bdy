<table border="0" width="100%" cellpadding="5">
<colgroup>
  <col width="50" /><col width="200" /><col width="200" /><col />
</colgroup>
<tr>
  <td class="item_tit">Clave</td><td><?php echo $codigo ?></td>
  <td class="item_tit">Descripción</td><td><?php echo articulo_descripcion($conn, $codigo) ?></td>
</tr>
</table>
