<h4>Modificación de precio de costo</h4>
<form action="<?php echo $PHP_SELF ?>" method="post">
<table border=1>
<colgroup>
  <col width="200"><col width="100"><col width="50"><col width="95">
  <col width="100"><col width="50"><col width="50">
</colgroup>
<tr>
  <th>Proveedor</th><th>Costo</th><th>Divisa</th><th>% IVA</th><th>Tiempo entrega</th>
  <th>Clave prov.</th><th>Costo envío</th><th>Div. envio</th><th>Estatus</th>
</tr>
<tr>
  <td><?php lista_proveedores(FALSE, "id_prov", "Seleccione proveedor", $id_prov) ?></td>
  <td><input type="text" size="10" name="costo1" value="<?php printf("%.2f", $costo1) ?>">
      <input type="hidden" name="codigo" value="<?php echo $codigo ?>"></td>
  <td><select name="divisa"><?php echo lista_divisas($conn, $divisa) ?></select></td>
  <td><input type="text" name="iva_porc" size=5 value="<?php printf("%.2f", $iva_porc) ?>">
  <td><input type="text" size="3" name="entrega1" value="<?php printf("%d", $entrega1) ?>">
      <input type="hidden" name="boton" value="costos"></td>
  <td><input type="text" size="10" name="prov_clave" value="<?php printf("%s", $prov_clave) ?>">
      <input type="hidden" name="action" value="ver"></td>
  <td><input type="text" size="10" name="costo_envio1" value="<?php printf("%.2f", $costo_envio1) ?>">
      <input type="hidden" name="subaction" value="actualiza_pcosto"></td>
  <td><select name="divisa_env"><?php echo lista_divisas($conn, $divisa) ?></select></td>
  <td><input type="text" size="1" name="status" value="<?php echo $status ?>"></td>
</tr>
</table>
<input type="submit" value="Actualizar">
</form>
