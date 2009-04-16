<div align="right">
<form action="cambia_cookie.php" method="post">
Ver inventario de almacén: <?php echo lista_almacen($conn, "valor_a", "Catálogo") ?>&nbsp;
<input type="submit" value="Mostrar" />
<input type="hidden" name="url" value="<?php echo $_SERVER['PHP_SELF'] ?>" />
<input type="hidden" name="nm_cookie" value="alm" />
</form><br />
</div>
