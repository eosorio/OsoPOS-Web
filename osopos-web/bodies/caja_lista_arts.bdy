<!-- bodies/caja_lista_arts.bdy -->
<table border=0>
<tr>
<th>CT.</th>
<th>C&oacute;digo</th>
<th width=300>Descripci&oacute;n</th>
<th>P.U.</th>
<th>Desc.</th>
<th>&nbsp;</th>
</tr>

<tr>
<td colspan=6><hr></td>
</tr>

<?

      for($i=$num_arts-1, $subtotal=0;  $i>=0;  $i--) {
		/* Aumenta cantidad en lugar de duplicar entrada */
		//        if ($articulo_codigo[$i] == $articulo_codigo[$i-1]) {
        if ($art[$i]->codigo == $art[$i-1]->codigo) {
          $i--;
          $num_arts--;
          $articulo_cantidad[$i] += 1;
        }
        echo "<tr>\n";
        echo "<td align=center><font size=-1 face=\"helvetica,arial\">";
        echo $articulo_cantidad[$i] . "</font>\n";
        echo "<input type=hidden name=articulo_cantidad[$i] value=\"" . $articulo_cantidad[$i] . "\">\n";
        echo "<td><small>\n";
        echo "<input type=hidden name=articulo_codigo[$i] value=\"" . $art[$i]->codigo . "\">\n";
        printf("</small></td>\n", $art[$i]->codigo);
        echo "<td><font size=-1 face=\"helvetica,arial\">";
        echo $articulo_descripcion[$i] . "\n";
        echo "<input type=hidden name=articulo_descripcion[$i] value=\"";
        echo $articulo_descripcion[$i] . "\"></font>\n";
        printf("<td align=right><font size=-1 face=\"helvetica,arial\">\n%.2f\n", $articulo_pu[$i]);
        echo "\n";
        printf("<input type=hidden name=articulo_pu[%d] value=\"%.2f\"></font></td>\n",
               $i, $articulo_pu[$i]);
        printf("<td align=right><font size=-1 face=\"helvetica,arial\">\n%.2f\n", $articulo_disc[$i]);
        printf("<input type=hidden name=articulo_disc[%d] value=\"%.2f\"></font>\n",
               $i, $articulo_disc[$i]);
        echo "<td><font size=-1 face=\"helvetica,arial\">";
        if(!$articulo_iva_porc[$i])
          echo "E";
        else
          echo "&nbsp;";
        echo "</font>\n<input type=hidden name=articulo_iva_porc[$i]";
        echo " value=\"$articulo_iva_porc[$i]\">\n";
        echo "</tr>\n";

        $subtotal += $articulo_pu[$i] * $articulo_cantidad[$i];
        $iva += $articulo_pu[$i] / (1+($articulo_iva_porc[$i]/100));
      }

?>
    <tr>
       <td colspan=6><hr></td>
    </tr>

    <tr>
      <td colspan=3 align=right><big>Total acumulado:</big></td>
      <td><big><b><?php printf("%.2f", $subtotal) ?></b></big></td>
    </tr>
 <input type=hidden name=num_arts value="<? printf("%d", $num_arts) ?>">
  <input type=hidden name=subtotal value="<? printf("%.2f", $subtotal) ?>">
</table>
