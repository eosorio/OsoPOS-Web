<b>Catálogo de almacenes</b><br>
<br>
<table border="0" width="210">
<colgroup>
  <col width=30><col width=180>
</colgroup>
<tr>
  <th>Id.</th>
  <th>Nombre</th>
</tr>
<?
  for ($i=0; $i<db_num_rows($db_res); $i++) {
	$ren = db_fetch_object($db_res, $i);
	echo "<tr>\n";
	printf("  <td style=\"text-align: center\"><a href=\"%s?almc=%d\">%d</a></td><td>%s</td>\n",
		   $_SERVER['PHP_SELF'], $ren->id, $ren->id, $ren->nombre);
	echo "</tr>\n";
  }
?>
</table>
<br>
<small>Presione sobre el número de almacén deseado para anexar o quitar productos del catálogo</small><br>
    