Nuevo movimiento de inventario<br>
<form action="<? echo $PHP_SELF ?>" method="post"> 
<table border=0 width="100%">
<tr>
  <td>Almacen: <input type="text" name="almacen" size=3></td>
  <td>Tipo de mov.: <? lista_movimientos($conn, "tipo_mov") ?></td>
  <td>Proveedor: <?  lista_proveedores(0, "id_prov1") ?></td>
</tr>
<tr>
  <td><input type="submit" value="Continuar"><input type="hidden" name="accion" value="cabecera"></td>
</tr>
</table>
</form>
