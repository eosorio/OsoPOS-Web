<?php
  $tipo_cambio = array();
  $query = "SELECT id,tipo_cambio FROM divisas ";
  if (!$db_res = db_query($query, $conn)) {
      echo "Error al consultar divisas<br>\n";
      exit();
  }
  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $ren = db_fetch_object($db_res, $i);
    $tipo_cambio[$ren->id] = $ren->tipo_cambio;
  }
?>
