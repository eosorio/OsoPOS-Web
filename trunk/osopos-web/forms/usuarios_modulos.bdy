Indique los accesos a los modulos para el usuario <?php echo $login ?>:<br>
<?php
{
  $query = "SELECT id, \"desc\" FROM modulo ORDER BY id ";
  $db_res2 = db_query($query, $conn);
  if (!$db_res2)
	echo "<div class=\"error_nf\">Error al consultar módulos</div><br>\n";
  else {
	echo "<form action=\"$_SERVER['PHP_SELF']\" method=\"post\">\n";
	echo "<table border=0 cellpadding=3>\n";
	echo "<colgroup>\n  <col width=40><col width=*><col width=20>\n</colgroup>\n";
	for ($i=0; $i < db_num_rows($db_res2); $i++) {
	  $ren = db_fetch_object($db_res2, $i);
	  echo "<tr>\n";
	  printf("  <td class=\"moneda\">%d</td><td>%s</td>\n",
			 $ren->id, htmlentities($ren->desc));
	  printf("  <td><input type=\"checkbox\" name=\"modulo[]\" value=%d></td>\n", $ren->id);
	  echo "</tr>\n";
	}
	echo "<tr>\n  <td><input type=\"submit\" value=\"Otorgar permisos\"></td>\n</tr>\n";
	echo "</table>\n";
	echo "<input type=\"hidden\" name=\"login\" value=\"$login\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"modulos_usuario\">\n";
	echo "</form>\n";
  }
}
?>