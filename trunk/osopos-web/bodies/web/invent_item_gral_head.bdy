<table border=0 width="100%">
<colgroup>
  <col width="390"><col>
</colgroup>
<tr>
  <td>
    <table border="0">
	<colgroup>
	  <col width="170"><col width="200">
	</colgroup>
	<tr>
      <td class="item_tit">Clave</td>
	  <td><?php echo $codigo ?></td>
	</tr>
	<tr>
	  <td class="item_tit">Descripcion</td>
	  <td><?php echo $ren->descripcion ?></td>
	</tr>
	<tr>
	  <td class="item_tit">Departamento</td>
	  <td><?php printf("%s", nombre_depto($conn, $ren->id_depto)) ?></td>
	</tr>
	<tr>
      <td class="item_tit">P. último costo</td>
      <td>&nbsp;</td>
	</tr>
	<tr>
      <td class="item_tit">Divisa</td><td><?php echo $ren->divisa ?></td>
	</tr>
	<tr>
	  <td class="item_tit">Prov. principal</td>
<?php
  $query = sprintf("SELECT nick FROM proveedores WHERE id=%d", $ren->id_prov1);
  if (!$db_res = db_query($query, $conn)) {
    echo "     <td>N/D</td>\n";
    exit();
  }
  else {
	printf("    <td>%s</td>\n", db_result($db_res, 0, 0));
  }
?>
    </tr>
    <tr>
	  <td class="item_tit">Prov. secundario</td>
<?php
  $query = sprintf("SELECT nick FROM proveedores WHERE id=%d", $ren->id_prov2);
  if (!$db_res = db_query($query, $conn)) {
    echo "      <td>N/D</td>\n";
    exit();
  }
  else {
	printf("      <td>%s</td>\n", db_result($db_res, 0, 0));
  }
?>
    </tr>
    <tr>
	  <td class="item_tit">I.V.A.</td>
	  <td><?php printf("%.2f%%", $ren->iva_porc) ?></td>
	</tr>

	</table>
  </td>
  <td>
<?php
   if (!empty($ren->img_location))
     printf("   <img src=\"%s/%s\">\n", $IMG_DIR, $ren->img_location);
   else
	 echo "&nbsp;\n";
?>
</td>
</tr>
</table>
