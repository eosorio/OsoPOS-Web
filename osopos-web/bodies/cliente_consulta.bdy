<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->
<h2>Catálogo de clientes</h2>
<table border=0 width="100%" height="500px">
<tr valign="top">
  <td>
<table border=0 width="100%">
<colgroup>

</colgroup>
<tr>
  <th>I.D.</th><th>Status</th><th>Nombre</th><th>Tel. 1</th>
  <th>Tel. 2</th><th>Sexo</th><th>Cliente desde</th>
</tr>
<?php
  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $ren = db_fetch_object($db_res, $i);
	echo "<tr>\n";
	printf("  <td>%d</td>\n", $ren->id);
	printf("  <td>%s</td>\n", td_status($ren->status));
	printf("  <td>%s %s %s</td>\n", $ren->nombres, $ren->ap_paterno, $ren->ap_materno);
	printf("  <td>%s</td>\n", $ren->telefono1);
	printf("  <td>%s</td>\n", $ren->telefono2);
	printf("  <td>%s</td>\n", $ren->sexo);
//	printf("  <td>%s</td>\n", $ren->alta);
	printf("  <td>&nbsp;</td>\n");
	echo "</tr>\n";
  }

?>

</table>
</td>
</tr>
</table>