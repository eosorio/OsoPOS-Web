<form>
<table border=0 width=400>
<tr>
  <th>&nbsp;</th><th>&nbsp;</th><th>Código</th><th>Descripción</th><th>P.U.</th><th>Depto.</th>
</tr>
<? 
{
  for ($i=0; $i<$num_ren; $i++) {
	$ren = db_fetch_object($db_res, $i);
?>
<tr>
  <td><img src="imagenes/borrar.gif"></td>
  <td>
    <input type="checkbox" name="codigo[]" value="<? echo $ren->codigo ?>">
  </td>
  <td><? echo htmlentities($ren->codigo) ?></td>
  <td><? echo htmlentities($ren->descripcion) ?></td>
  <td><? echo $ren->pu ?></td>
  <td><? echo $ren->id_depto ?></td>
</tr>
<?
   }
}
?>
</table>
<input type="hidden" name="alm" value=<? echo $alm ?>>
<input type="hidden" name="action" value="insertar">
<input type="hidden" name="debug" value="<? echo $debug ?>">
<input type="submit" value="Añadir a este almacén">
</form>