<!-- -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->
<h2>Clientes con productos rentados por entregar</h2>
<table border=0 width="100%" height="500px">
<?php
if (db_num_rows($db_res) == 0) {
  echo "<tr>\n  <td align=\"center\">Todos los productos han sido devueltos</td>\n</tr>\n";
}
else {
?>
<colgroup>
  <col width="50px"><col width=*><col width="200px"><col width="130px"><col width="130px">
</colgroup>
<tr>
  <th>Cliente</th>
  <th>Nombre</th>
  <th>F. entrega</th>
  <th>Tel. casa.</th>
  <th>Tel. trabajo</th>
</tr>
<?php for ($i=0, $last_id=-1; $i < db_num_rows($db_res); $i++) {
	  $cliente = db_fetch_object($db_res, $i);
	  if ($cliente->id==$last_id)
		continue;
?>
  <tr>
    <td><?php echo $cliente->id ?></td>
	<td><?php printf("%s %s", $cliente->nombres, $cliente->ap_paterno); ?></td> 
	<td class="serie"><?php echo $cliente->entrega ?></td>
	<td><?php echo $cliente->dom_tel_casa ?></td>
	<td><?php echo $cliente->dom_tel_trabajo ?></td>
  </tr>
<?php
	  $last_id = $cliente->id;
	}
}
?>
</table>
<?php   include("bodies/menu/renta.bdy"); ?>