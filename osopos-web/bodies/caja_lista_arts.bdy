<!-- bodies/caja_lista_arts.bdy -->
<table border=0>
<colgroup>
  <col width="75"><col width="200"><col width="400"><col width="150"><col>
</colgroup>
<tr>
<th>CT.</th>
<th>C&oacute;digo</th>
<th width=300>Descripci&oacute;n</th>
<th>P.U.</th>
<th>&nbsp;</th>
</tr>

<tr>
<td colspan=6><hr></td>
</tr>

<?php
{
  $subtotal = 0;
  if (count($osopos_carrito) == 0) {
    /* igm */ echo "No existe carrito<br>num_arts: $num_arts<br>\n";
    for($i=$num_arts-1;  $i>=0;  $i--) {
      /* Aumenta cantidad en lugar de duplicar entrada */
      if ($articulo_codigo[$i] == $articulo_codigo[$i-1]) {
	$i--;
	$num_arts--;
	$articulo_cantidad[$i] += 1;
      }
      echo "<tr>\n";
      echo "<td align=center><small>";
      echo $articulo_cantidad[$i] . "</small>\n";
      echo "<input type=hidden name=articulo_cantidad[$i] value=\"" . $articulo_cantidad[$i] . "\">\n";
      echo "<td><small>\n";
      echo "<input type=\"hidden\" name=\"articulo_codigo[$i]\" value=\"" . $articulo_codigo[$i] . "\">";
      printf("%s\n</small></td>\n", $articulo_codigo[$i]);
      echo "<td><small>";
      echo $articulo_descripcion[$i] . "\n";
      echo "<input type=hidden name=articulo_descripcion[$i] value=\"";
      echo $articulo_descripcion[$i] . "\"></small>\n";
      printf("<td align=right>\n<small>%.2f</small>", $articulo_pu[$i]);
      printf("<input type=hidden name=articulo_pu[%d] value=\"%.2f\"></td>\n",
	     $i, $articulo_pu[$i]);
      printf("<td align=right><small>\n%.2f\n", $articulo_disc[$i]);
      printf("<input type=\"hidden\" name=\"articulo_disc[%d]\" value=\"%.2f\"></small>\n",
	     $i, $articulo_disc[$i]);
      echo "<td><small>";
      if(!$articulo_iva_porc[$i])
      echo "E";
      else
      echo "&nbsp;";
      echo "</small>\n<input type=\"hidden\" name=\"articulo_iva_porc[$i]\"";
      echo " value=\"$articulo_iva_porc[$i]\">\n";
      echo "</tr>\n";

      $subtotal += $articulo_pu[$i] * $articulo_cantidad[$i];
      $iva += $articulo_pu[$i] / (1+($articulo_iva_porc[$i]/100));
    }
  }
  else {
    $articulo_pu = lista_precio($conn, array_keys($osopos_carrito), 1);
    $articulo_descripcion = lista_campo($conn, array_keys($osopos_carrito), "descripcion", "articulos");
    $articulo_iva_porc = lista_campo($conn, array_keys($osopos_carrito), "iva_porc", "articulos");
    while (list ($nombre, $valor) = each ($osopos_carrito)) {
      echo "<tr>\n";
      echo "<td align=\"center\"><small>\n";
      echo "<input type=\"hidden\" name=\"articulo_cantidad[$nombre]\" value=\"$valor\">$valor</small></td>\n";
      echo "<td><small>$nombre\n</small>\n";
      echo "</td>\n";
      echo "<td><small>";
      echo $articulo_descripcion[$nombre] . "\n";
      echo "<input type=\"hidden\" name=\"articulo_descripcion[$nombre]\" value=\"";
      echo $articulo_descripcion[$nombre] . "\"></small></td>\n";
      printf("<td align=\"right\">\n<small>%.2f</small>", $articulo_pu[$nombre]);
      printf("<input type=\"hidden\" name=\"articulo_pu[%s]\" value=\"%.2f\"></td>\n",
	     $nombre, $articulo_pu[$nombre]);
      echo "<td><small>";
      if(!$articulo_iva_porc[$nombre])
	echo "E";
      else
	echo "&nbsp;";
      echo "</small></td>\n<input type=\"hidden\" name=\"articulo_iva_porc[$nombre]\"";
      echo " value=\"$articulo_iva_porc[$nombre]\">\n";
      echo "</tr>\n";

      $subtotal = $articulo_pu[$nombre] * $valor + $subtotal;
      $num_arts++;

      /* OJO  REVISAR CONDICION DE IVA INCLUIDO */
      $iva += $articulo_pu[$nombre] / (1+($articulo_iva_porc[$nombre]/100));
    }
  }
    
}
?>
    <tr>
       <td colspan=5><hr></td>
    </tr>

    <tr>
      <td colspan="3" align="right"><big>Total acumulado:</big></td>
      <td><big><b><?php printf("%.2f", $subtotal) ?></b></big><input type="hidden" name="subtotal" value="<?php printf("%.2f", $subtotal) ?>"></td>
    </tr>
 <input type="hidden" name="num_arts" value="<?php printf("%d", $num_arts) ?>">

</table>
