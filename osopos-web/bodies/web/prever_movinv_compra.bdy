<table border=0 width=400>
<tr>
  <td>Movimiento No. <?php echo $id_mov ?></td><td>Fecha <?php printf("%s", date("j/n/Y")) ?></td>
</tr>
<tr>
  <td colspan=2>Tipo de movimiento: <?php printf("%s %s", $tipo_mov, tipo_mov($conn, $tipo_mov)) ?></td>
</tr>
<tr>
  <td colspan=2>Almacen de entrada: <? echo $almacen ?></td>
</tr>
<tr>
  <td colspan=2>I.D. Proveedor: <?php echo $id_prov1 ?></td>
</tr>
</table>

<table cellpadding=3>
<tr>
  <th>Código</th><th>Descripción</th><th>Ct.</th><th>P. Costo</th><th>Alm. dest.</th>
</tr>
<? 
{
  for ($i=0; $i<count($codigo); $i++) {
    echo "<tr>\n";
    printf("  <td>%s</td>\n",
	   $codigo[$i]);
    printf("  <td>%s</td>\n", articulo_descripcion($conn, $codigo[$i]));
    printf("  <td class=\"numero\">%.2f</td>\n", $ct[$i]);
    printf("  <td class=\"moneda\">%.2f</td>\n", $p_costo[$i]);
    printf("  <td class=\"serie\">%d</td>\n", $alm_dest[$i]);
    echo "</tr>\n\n";

    $pcosto[$codigo[$i]] = $p_costo[$i];
  }
}
?>

</table>
