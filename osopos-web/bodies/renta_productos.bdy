<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->
<h2>Artículos rentados por entregar</h2>
<table height="500px" width="100%" border=0>
<tr valign="top">
  <td>
<table border="0" width="100%">
<colgroup>
  <col width="50px"><col width="200px" span=2><col width="100px"><col width=*><col width="75px">
</colgroup>
<tr>
  <th>Num. renta.</th>
  <th>F.entrega</th>
  <th>F. pedido</th>
  <th>Serie</th>
  <th>Descripción</th>
  <th>Id. Cliente</th>
</tr>
<?php for ($i=0, $last_id=-1; $i < db_num_rows($db_res); $i++) {
	  $ren = db_fetch_object($db_res, $i);
?>
  <tr>
    <td class="serie"><?php
    printf("<a href=\"%s?accion=detalle_renta&id=%d\">%d</a>",
	$_SERVER['PHP_SELF'], $ren->id, $ren->id)
 ?></td>
	<td class="serie"><?php echo $ren->entrega ?></td> 
	<td class="serie"><?php echo $ren->pedido ?></td>
	<td><?php echo $ren->serie ?></td>
    <td><?php echo $ren->descripcion ?></td>
	<td class="serie"><?php echo $ren->cliente ?></td>
  </tr>
<?php

	}
?>
</table>
  </td>
</tr>
</table>
<?php  include("bodies/menu/renta.bdy"); ?>
