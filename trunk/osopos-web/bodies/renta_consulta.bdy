<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->
<h2>Rentas con productos no devueltos</h2>
<table border=0 height="500px" width="100%">
<tr valign="top">
  <td>
<?php
if (db_num_rows($db_res) == 0) {
  echo "<div align=\"center\">Todos los productos han sido devueltos</div>\n";
}
else {
?>
<table border=0 width="100%">
<colgroup>
  <col width="75px"><col width="200px" span=2><col width=*><col width="75px">
</colgroup>
<tr height="20px" valign="top">
  <th>Num. renta.</th>
  <th>F.entrega</th>
  <th>F. pedido</th>
  <th>Id. Cliente</th>
</tr>
<?php for ($i=0, $last_id=-1; $i < db_num_rows($db_res); $i++) {
	  $ren = db_fetch_object($db_res, $i);
?>
  <tr height="40px" valign="top">
    <td class="serie"><?php
	  printf("<a href=\"%s?accion=detalle_renta&id=%d\">%s</a>", $PHP_SELF, $ren->id, $ren->id);
 ?></td>
	<td class="serie"><?php echo $ren->entrega ?></td> 
	<td class="serie"><?php echo $ren->pedido ?></td>
	<td class="serie"><?php echo $ren->cliente ?></td>
  </tr>
<?php

	}
 echo "</table>\n";
}
?>
  </td>
</tr>
</table>
<?php include("bodies/menu/renta.bdy"); ?>
