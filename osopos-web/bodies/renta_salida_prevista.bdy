<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table border=0 width="100%">
<tr>
  <th>Serie</th><th>Código</th><th>Descripción</th><th>Costo unitario</th><th>F. de entrega</th>
</tr>
<?php
   $importe = 0.0;

   for ($i=0; $i < $num_ren; $i++) {
     $t_entrega = array();
     $ren = db_fetch_object($db_res, $i);
     $importe+= $ren->pu;
     switch($ren->unidad_t) {
     case 2:
       $entrega_i = 7;
       for ($j=0; $j<$entrega_i; $j++) {
	 $t_entrega[] = mktime (0,0,0,date("m"), date("d")+$ren->tiempo+$j,date("Y"));
	 // $f_entrega[] = date("D", mktime (0,0,0,date("m"), date("d")+$ren->tiempo,date("Y")));
       }
       break;
     case 3:
       $entrega_i = 12;
       for ($j=0; $j<$entrega_i; $j++) {
	 $t_entrega[] = mktime (0,0,0,date("m")+$ren->tiempo+$j, date("d"),date("Y"));
	 // $f_entrega = date("F", mktime (0,0,0,date("m")+$ren->tiempo, date("d"),date("Y")));
       }
       break;
     }

     echo "<tr>\n";
     printf("  <td>%s<input type=\"hidden\" name=\"serie[]\" value=\"%s\" /></td>\n",
	    $ren->id, $ren->id);
     printf("  <td class=\"serie\">%s<input type=\"hidden\" name=\"codigo[%s]\" value=\"%s\" /></td>\n",
	    $ren->codigo, $ren->id, $ren->codigo);
     printf("  <td>%s<input type=\"hidden\" name=\"descripcion[%s]\" value=\"%s\" /></td>\n",
	    $ren->descripcion, $ren->id, $ren->descripcion);
     printf("  <td class=\"moneda\">%.2f<input type=\"hidden\" name=\"costo[%s]\" value=\"%f\" /></td>\n",
	    $ren->pu, $ren->id, $ren->pu );
     printf( "  <td align=\"center\">\n    <select name=\"f_entrega[%s]\">\n", $ren->id);
     for ($j=0; $j<$entrega_i; $j++) {
       printf("      <option value=\"%s\" />%s\n",
	      date("Y-m-d H:i", $t_entrega[$j]), date("D d-m-y", $t_entrega[$j])) ;
     }
     echo "    </select>\n";
     printf("    <input type=\"hidden\" name=\"almcen[%s]\" value=\"%d\" />\n", $ren->id, $ren->almacen);
     printf("    <input type=\"hidden\" name=\"unidad_t[%s]\" value=\"%d\" />\n", $ren->id, $ren->unidad_t);
     echo "  </td>\n";
     echo "</tr>\n";
   }
?>
</table>
<input type="hidden" name="id_cliente" value="<?php echo $id_cliente ?>" />

<?php
     echo "<input type=\"hidden\" name=\"accion\" value=\"renta\" />\n";
     echo "<input type=\"hidden\" name=\"subaccion\" value=\"registrar\" />\n";
     echo "<input type=\"hidden\" name=\"serie\" value=\"\" />\n";
     echo "<input type=\"submit\" value=\"Continuar\" />\n";
/*     reset($ser);
     for ($i=0; $i<count($ser); $i++) {
       printf("<input type=\"hidden\" name=\"ser[]\" value=\"%s\">\n", $ser[$i]);

     } */
?>
</form>
