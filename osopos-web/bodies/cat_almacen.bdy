<b>Catálogo de almacenes</b><br>
<br>
<table border=0 width=200>
<colgroup>
  <col width=20><col width=180>
</colgroup>
<tr>
  <th>Id.</th>
  <th>Nombre</th>
</tr>
<?
  for ($i=0; $i<db_num_rows($db_res); $i++) {
	$ren = db_fetch_object($db_res, $i);
	echo "<tr>\n";
	printf("  <td><a href=\"%s?alm=%d\">%d</a></td><td>%s</td>\n",
		   $PHP_SELF, $ren->id, $ren->id, $ren->nombre);
	echo "</tr>\n";
  }
?>
</table>
    