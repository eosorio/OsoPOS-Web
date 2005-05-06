<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->

<h2>Detalle de renta</h2>
<table border=0 width="100%" height="500px">
<tr valign="top">
  <td>
<?php
{
  $query = "SELECT c.id, c.nombres, c.ap_paterno, c.ap_materno, r.pedido ";
  $query.= "FROM clientes  c, rentas r WHERE c.id=r.cliente AND r.id=$id ";

  if (!@$db_res = db_query($query, $conn)) {
    $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar clientes y rentas</font><br>";
	$mens.= db_errormsg($conn);
    die($mens);
  } 
  $ren = db_fetch_object($db_res, 0);
}
?>

<table>
<tr>
  <td class="item_tit">Renta no.</td>
  <td><?php echo $id ?></td>
</tr>
<tr>
  <td class="item_tit">Cliente</td>
  <td><?php
  printf("%d. %s %s %s.", $ren->id, $ren->nombres, $ren->ap_paterno, $ren->ap_materno);
?>
  </td>
</tr>
<tr>
  <td class="item_tit">Fecha de renta:</td>
  <td><?php echo $ren->pedido ?></td>
</tr>
</table>
<?php
    $query = "SELECT det.serie, ars.codigo, a.descripcion, CASE ";
    $query.= "WHEN (det.status&B'10000000' = B'10000000') THEN 'Entregado' ELSE 'No entregado' END ";
    $query.= "AS estado FROM rentas_detalle det, articulos_series ars, articulos a ";
    $query.= "WHERE ars.id=det.serie AND a.codigo=ars.codigo AND det.id=$id";

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar productos rentados</font><br>";
	  $mens.= db_errormsg($conn);
      die($mens);
    } 
    $query = "SELECT det.serie, ars.codigo, a.descripcion, CASE ";
    $query.= "WHEN (det.status&B'10000000' = B'10000000') THEN 'Entregado' ELSE 'No entregado' END ";
    $query.= "AS estado, det.f_entrega FROM rentas_detalle det, articulos_series ars, articulos a ";
    $query.= "WHERE ars.id=det.serie AND a.codigo=ars.codigo AND det.id=$id";

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar productos rentados</font><br>";
	  $mens.= db_errormsg($conn);
      die($mens);
    } 
?>
<br>
<table border=0 width="100%">
<colgroup>
  <col width="25px"><col width="100px"><col width="*"><col width="180px"><col width="200px">
</colgroup>
<tr>
  <th><img src="imagenes/renta_regresar.png"></th>
  <th>Serie</th>
  <th>Descripción</th>
  <th>Estado</th>
  <th>F. entrega</th>
</tr>
<?php for ($i=0, $last_id=-1; $i < db_num_rows($db_res); $i++) {
	  $ren = db_fetch_object($db_res, $i);
?>
<tr>
  <td align="center"><input type="checkbox" name="serie" value="<?php echo $ren->serie ?>"</td>
  <td class="serie"><?php echo $ren->serie ?></td>
  <td><?php echo $ren->descripcion ?></td> 
  <td class="serie"><?php echo $ren->estado ?></td>
  <td class="serie"><?php echo $ren->f_entrega ?></td>
</tr>
<?php

	}
?>
</table>
  </td>
</tr>
</table>
<?php include("bodies/menu/renta.bdy"); ?>