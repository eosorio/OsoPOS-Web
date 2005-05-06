<h1>Devolución rápida de productos rentados</h1>
<table border=0 width="100%" height="500px">
<tr valign="top">
  <td>
<form action="<?php echo $PHP_SELF ?>" method="post" name="express">
<table border=0 width="600px">
<colgroup>
  <col width="300px"><col width=*>
</colgroup>
<tr>
  <td class="item_tit" valign="top">Número de serie del producto
  <input type="hidden" name="accion" value="devolucion_express"></td>
  <td align="left" valign="top"><input type="text" name="serie" size=20><input type="hidden" name="subaccion" value="agrega"></td>
</tr>
</table>
</form>
<?php
  if($subaccion=="agrega") { 
	$query = "SELECT det.id, det.serie, a.descripcion, det.f_entrega, det.costo, ";
	$query.= "c.id AS id_cliente, c.nombres, c.ap_paterno, ars.codigo, ars.almacen, ";
	$query.= "CASE WHEN (det.status&B'10000000'=B'10000000') THEN '1' ELSE '0' END AS devuelto "; 
	$query.= "FROM rentas_detalle det, articulos_series ars, ";
	$query.= "articulos a, clientes c, rentas r WHERE ars.id=det.serie ";
	$query.= "AND c.id=r.cliente AND a.codigo=ars.codigo AND det.id=r.id ";
	$query.= "AND det.status&B'10000000'=B'00000000' AND det.serie='$serie' ";

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar detalle de renta</font><br>";
      $mens.= db_errormsg($conn);
      die($mens);
    }

	$ren = db_fetch_object($db_res, 0);
	if (db_num_rows($db_res)==0) {
	  echo "<div class=\"error_nf\">Error: El producto $serie no se encuentra rentado</div><br>\n";
	}
	else {
	  $id_renta = $ren->id;
	  $id_cliente = $ren->id_cliente;
	  $query = sprintf("UPDATE rentas_detalle SET status=status|B'10000000' WHERE serie='%s' AND id=%d ",
					   $ren->serie, $ren->id);

	  if (!@$db_res = db_query($query, $conn)) {
		$mens = "<font style=\"text-size: big; font-weight: bold\">Error al actualizar detalle de rentas</font><br>";
		$mens.= db_errormsg($conn);
		echo "$mens";
	  }
	  else {
	    $query = sprintf("UPDATE almacen_1 SET cant=cant+1 WHERE id_alm=%d AND codigo='%s' ",
			     $ren->almacen, $ren->codigo);

	    if (!@$db_res = db_query($query, $conn)) {
	      $mens = "<div class=\"error_nf\">Error al actualizar catálogo de almacen</div><br>";
	      $mens.= db_errormsg($conn);
	      echo "$mens";
	    }

	    $query = sprintf("UPDATE articulos_series SET status=status&B'01111111' WHERE id='%s' ",
			    $serie);
	    if (!@$db_res = db_query($query, $conn)) {
	      $mens = "<div class=\"error_nf\">Error al actualizar datos del producto/div><br>";
	      $mens.= db_errormsg($conn);
	      echo "$mens";
	    }
?>
<hr>
<i>Producto registrado</i><br>
<table border=0>
<tr>
  <td class="item_tit">Serie</td>
  <td><?php echo $ren->serie ?></td>
</tr>
<tr>
  <td class="item_tit">Producto</td>
  <td><?php echo $ren->descripcion ?></td>
</tr>
<tr>
  <td class="item_tit">Num. renta</td>
  <td><?php echo $ren->id ?></td>
</tr>
<tr>
  <td class="item_tit">Fecha de entrega</td>
  <td><?php echo $ren->f_entrega ?></td>
</tr>
<tr>
  <td class="item_tit">Valor de renta</td>
  <td><?php echo $ren->costo ?></td>
</tr>
<tr>
  <td class="item_tit">Cliente</td>
  <td><?php printf("%s %s", $ren->nombres, $ren->ap_paterno) ?></td>
</tr>
</table>
<?php
	  }
    }
  }
?>
</td>
</tr>
</table>