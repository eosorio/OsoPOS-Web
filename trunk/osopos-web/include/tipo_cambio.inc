<?php
  $tipo_cambio = array();
  $query = "SELECT id,tipo_cambio FROM divisas ";
  if (!$db_res = db_query($query, $conn)) {
      die("<div class=\"error_f\">Error al consultar divisas</div>\n";
  }
  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $ren = db_fetch_object($db_res, $i);
    $tipo_cambio[$ren->id] = $ren->tipo_cambio;
  }
?>
