<table border=0 width="100%">
<tr>
<td>
<form action="<?php echo $PHP_SELF ?>" method="post">
<div class="menu_texto"><input type="image" src="imagenes/lupa.png" border=0>Ver contenido</div>
</form>
</td>
<td>
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="hidden" name="accion" value="cambiar">
<div class="menu_texto"><input type="image" src="imagenes/lapiz.png" border=0>Modificar cantidades</div>
</form>
</td>
<td>
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="hidden" name="accion" value="borrar">
<div class="menu_texto"><input type="image" src="imagenes/borrar.png" border=0>Vaciar el carrito</div>
</form>
</td>
</tr>
</table>
