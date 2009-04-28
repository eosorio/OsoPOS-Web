Nuevo movimiento de inventario<br>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"> 
<table border=0 width="100%">
<tr>
<td>Almacen: <?php echo lista_almacen($conn, "almacen", "Seleccione su almacen", 0) ?></td>
  <td>Tipo de mov.: <?php lista_movimientos($conn, $MOVINV_COMPRA, "tipo_mov") ?></td>
  <td>Proveedor: <?php  lista_proveedores(0, "id_prov1", "Seleccione su proveedor", 1) ?></td>
</tr>
<tr>
  <td><input type="submit" value="Continuar"><input type="hidden" name="accion" value="cabecera" /></td>
</tr>
</table>
</form>
