<a href="<? echo $PHP_SELF ?>">Administración de usuarios</a>
<hr>
<table border=0 width=168>
<colgroup>
  <col width="20"><col width="24"><col width="100"><col width="24">
</colgroup>
<tr>
  <th width=20>&nbsp;</th><th>id</th><th>login</th><th>Nivel</th>
</tr>
<?
for ($i=0; $i<$max_usr; $i++) {
  $usr = db_fetch_object($db_res, $i);
  echo "<tr>\n";
  printf("  <td><a href=\"%s?action=borrar&login=%s\">", $PHP_SELF, $usr->user);
  echo "<img src=\"imagenes/borrar.gif\" alt=\"Eliminar\"></a></td>\n";
  echo "  <td align=\"right\">$usr->id</td>\n";
  echo "  <td>$usr->user</td>\n";
  echo "  <td align=\"center\">$usr->level</td>\n";
  echo "</tr>\n";
}
?>
</table>