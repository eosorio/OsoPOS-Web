<b>Página <?php printf("%d/%d", $pagina+1, $total_renglones/$limit+1) ?></b><br><br>
<form action="<?php echo $PHP_SELF ?>" method="post">
<table border=0 width="100%">
<colgroup>
  <col width="20px" span=2><col width="200px"><col width="*"><col width="250px">
</colgroup>
<tr>
  <th>&nbsp;</th><th>&nbsp;</th><th>Código</th><th>Descripción</th><th>Depto.</th>
</tr>
<? 
{
  for ($i=$pagina*$limit; $i<($pagina+1)*$limit && $i< $total_renglones; $i++) {
	$ren = db_fetch_object($db_res, $i);
?>
<tr>
  <td><img src="imagenes/borrar.gif"></td>
  <td>
    <input type="checkbox" name="codigo[]" value="<? echo $ren->codigo ?>">
  </td>
  <td><? echo htmlentities($ren->codigo) ?></td>
  <td><? echo htmlentities($ren->descripcion) ?></td>
  <td><? echo $nm_deptos[$ren->id_depto] ?></td>
</tr>
<?
   }
}
?>
</table>
<input type="hidden" name="almc" value=<? echo $almc ?>>
<input type="hidden" name="action" value="insertar">
<input type="hidden" name="debug" value="<? echo $debug ?>">
<input type="submit" value="Añadir a este almacén">
</form>
<hr>
<?php if (!isset($pagina)) $pagina=0; ?>
<table width=150 border=0 align="center">
<tr>
  <td>
  <?php if ($pagina>0) { ?>
    <form action="<?php echo $PHP_SELF ?>" method="post">
	<input type="hidden" name="action" value="agregar">
	<input type="image" src="imagenes/web/botones/anterior.png">
	<input type="hidden" name="pagina" value=<?php printf("\"%d\"", $pagina-1) ?>>
	<input type="hidden" name="almc" value=<?php printf("\"%d\"", $almc) ?>>
	<input type="hidden" name="boton" value="anterior">
	</form>
  <?php } else echo "&nbsp;"					 ?>
  </td>

  <td>
  <?php if (($pagina+1)*$limit < $total_renglones) { ?>
    <form action="<?php echo $PHP_SELF ?>" method="post">
	<input type="hidden" name="action" value="agregar">
	<input type="image" src="imagenes/web/botones/siguiente.png" alt="Siguiente">
	<input type="hidden" name="pagina" value=<?php printf("\"%d\"", $pagina+1) ?>>
	<input type="hidden" name="almc" value=<?php printf("\"%d\"", $almc) ?>>
	<input type="hidden" name="boton" value="siguiente">
  <?php } else echo "&nbsp;"					 ?>
	</form>
  </td>
</tr>
</table>
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="hidden" name="almc" value="<?php echo $almc ?>">
<input type="hidden" name="action" value="agregar">
Búsqueda de producto <input type="text" size=30 name="busqueda">
<input type="image" src="imagenes/lupa.png">
</form>
<br><br>
