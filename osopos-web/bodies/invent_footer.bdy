<div align="right">
<form action="cambia_cookie.php" method="post">
Ver inventario de almacen: <?php echo lista_almacen($conn, "valor_a", "Catálogo") ?>&nbsp;
<input type="submit" value="Mostrar">
<input type="hidden" name="url" value="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="nm_cookie" value="alm">
</form><br>
<a href="almacen.php?alm=0">Almacenes</a> |
<a href="invent_web.php">Catálogo de productos</a> |
<a href="invent_web.php?action=agrega">Alta de producto</a> |
<a href="depto.php">Departamentos</a> |
<a href="proveedor.php">Proveedores</a> |
<a href="osopos.php">Menú principal</a> |
<a href="<?php echo $_SERVER['PHP_SELF'] ?>?salir=1">Salir del sistema</a>

</div>
