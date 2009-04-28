<?php
{

  if ($accion=="muestra") {
	echo "<h2>Productos en ventas marcadas sin código</h2>\n";
	$query = "SELECT vd.id_venta AS numero, vd.descrip, vd.cantidad, vd.pu, vd.iva_porc, ";
	$query.= "v.id_cajero, v.fecha, v.hora FROM ventas_detalle vd, ventas v ";
	$query.= sprintf("WHERE vd.codigo='Sin codigo' AND v.numero=vd.id_venta AND fecha='%s' ",
					 $s_fecha);

	if (!$res = db_query($query, $conn)) {
	  $mens = "<div class=\"error_f\">Error al consultar catálogo de ventas y detalle de ventas</div>\n";
	  die($mens);
	}

	echo "<table border=1>\n";
	echo "<tr>\n";
	echo "<th>I.D.</th><th>Descripción</th><th>Ct.</th><th>P.U.</th><th>% IVA</th><th>Cajero</th><th>Fecha</th><th>Hora</th>\n";
	echo "</tr>\n";
	for ($i=0; $i<db_num_rows($res); $i++) {
	  $ren = db_fetch_object($res, $i);
	  echo "<tr>\n";
	  printf("<td class=\"serie\">%d</td>\n  <td> %s</td>\n  <td class=\"moneda\"> %.2f</td>\n  <td class=\"moneda\"> %.2f</td>\n  <td class=\"moneda\"> %.2f</td>\n",
			 $ren->numero, $ren->descrip, $ren->cantidad, $ren->pu, $ren->iva_porc);
	  printf("<td class=\"serie\">%d</td>\n  <td> %s</td>\n  <td> %s</td>\n",
			 $ren->id_cajero, $ren->fecha, $ren->hora);
	  echo "</tr>\n";
	}
	echo "</table>\n";
  }

  else if (empty($accion)) {
	echo "<form action=\"$_SERVER['PHP_SELF']\" method=\"post\">\n";
	echo "Fecha a mostrar: <input type=\"text\" name=\"s_fecha\" size=8> <small><i>(p. ej. 2004-12-22)</i></small>\n";
	echo "<input type=\"hidden\" name=\"modulo\" value=\"nocodigo\" />\n";
	echo "<input type=\"hidden\" name=\"accion\" value=\"muestra\" />\n";
	echo "</form>\n";
  }
}
?>
