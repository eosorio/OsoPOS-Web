Movimiento No. <?php echo $id_mov ?><br />
Tipo de movimiento: <?php printf("%s %s", $tipo_mov, tipo_mov($conn, $tipo_mov)) ?><br />
Almacen: <?php echo $almacen ?><br />
<table>
<tr>
  <th>Código</th><th>Cantidad</th><th>P.U.</th><th>P. Costo</th><th>Alm. dest.</th>
</tr>
<?php 
{
  for ($i=0; $i<count($codigo); $i++) {
	echo "<tr>\n";
	printf("  <td>%s</td>\n",
		   $codigo[$i]);
	printf("  <td>%s</td>\n", articulo_descripcion($conn, $codigo[$i]));
	printf("  <td>%.2f</td>\n", $ct[$i]);
    printf("  <td>%.2f</td>\n", $pu[$i]);
	printf("  <td>%.2f</td>\n", $p_costo[$i]);
	printf("  <td>%d</td>\n", $alm_dest[$i]);
	echo "</tr>\n\n";
  }
}
?>

</table>
