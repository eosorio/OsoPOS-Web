<? /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- forms/invent_exp_main.bdy -->

<form action=<? echo $PHP_SELF ?> method=<?/*"POST"*/?>GET name="f_articulos">
<table border=0 width='100%'>
 <tr>
   <th><image src="imagenes/trash.png" alt="Eliminar"></th>
<?
    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=codigo&order=";
    printf("%d",  $order_by=="codigo" && !$order);
    echo "$href_dept$href_prov\">C&oacute;digo</a></th>\n";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=descripcion&order=";
    printf("%d",  $order_by=="descripcion" && !$order);
    echo "$href_dept$href_prov\">Descripci&oacute;n</a></th>\n";

    if ($alm>0) {
      echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=pu&order=";
      printf("%d",  $order_by=="pu" && !$order);
      echo "$href_dept$href_prov\">P. Público</a></th>\n";

      echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=pu2&order=";
      printf("%d",  $order_by=="pu2" && !$order);
      echo "$href_dept$href_prov\">P.U. 2</a></th>\n";
    }
    else {
      echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=p_costo&order=";
      printf("%d",  $order_by=="p_costo" && !$order);
      echo "$href_dept$href_prov\">P. Costo</a></th>\n";
    }

    if ($alm>0) {
      echo "  <th>Cant.</th>\n";
      if (puede_hacer($conn, $user->user, "invent_cambiar_item")) {
?>

  <th>Entradas</th>
  <th>Salidas</th>
<?
     }
    }
?>
  <th>Modif.</th>
 </tr>

<?

    for ($i=0; $i<$num_ren; $i++) {
      $reng = db_fetch_object($resultado, $i);
      $id_prov = $reng->id_prov;
      $id_dept = $reng->id_depto;
      if (empty($search))
        $descripcion = htmlentities($reng->descripcion);
      else
        $descripcion = str_replace($search, "<b>$search</b>", htmlentities($reng->descripcion));
      if (empty($search))
        $codigo = $reng->codigo;
      else
        $codigo = str_replace($search, "<b>$search</b>", $reng->codigo);

      if (!($i%4) || $i==0) {
        $class = "bg1";
      }
      else if (!(($i+2)%2)) {
        $class = "bg2";
      }
      else {
        $class = "bg0";
      }

      echo " <tr>\n";
      echo "  <td>\n";
      printf("<input type=\"checkbox\" name=\"delete[%d]\">", $i);
      echo "</td>\n";

      echo "  <td class=\"$class\"><a href=\"$PHP_SELF?codigo=";
      echo str_replace(" ", "%20", htmlentities($reng->codigo));
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_dept$href_prov\">";
      echo stripslashes($codigo) . "</a>";
      printf("<input type=\"hidden\" name=\"code[%d]\" value=\"%s\"></td>\n", $i, $reng->codigo);
      printf("  <td class=\"%s\">", $class);
      if ($alm==0) {
        printf("<input type=\"text\" name=\"desc[%d]\" value=\"%s\" size=40 ",
               $i, htmlentities($reng->descripcion));
        printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">", ($i+1)*5-1);

      }
      else {
        printf("<input type=\"hidden\" name=\"desc[%d]\" value=\"%s\" ",
               $i, htmlentities($reng->descripcion));
        printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">%s",
               ($i+1)*(8+$alm)-1,  $descripcion);

      }
      echo "</td>\n";

      if ($alm>0) {
        printf("  <td class=\"%s_right\">", $class);
        printf("<input type=\"text\" name=\"pu[%d]\" size=8 value=\"%.2f\" ",
               $i, $reng->pu);
        printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">", ($i+1)*9-1);
        echo "  </td>\n";
        printf("  <td class=\"%s_right\">", $class);
        printf("<input type=\"text\" name=\"pu2[%d]\" size=8 value=\"%.2f\" ",
               $i, $reng->pu2);
        printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">", ($i+1)*9-1);
        echo "  </td>\n";
      }
      else {
        printf("  <td class=\"%s_right\">", $class);
        printf("<input type=\"text\" name=\"p_costo[%d]\" size=8 value=\"%.2f\" ",
               $i, $reng->p_costo);
        printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">", ($i+1)*(8+$alm)-1);
        echo "  </td>\n";
      }

      if ($alm>0) {
        printf("  <td class=\"%s_center\">", $class);
        if (puede_hacer($conn, $user->user, "invent_cambiar_item") && puede_hacer($conn, $user->user, "invent_fisico")) {
          printf(" <input type=\"text\" name=\"qt[%d]\" size=5 value=\"%.2f\" ", $i, $reng->cant);
          printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">", ($i+1)*(8+($alm>0))-1);
        }
        else
          printf("%.2f", $reng->cant);
        echo "</td>\n";

        printf("  <td class=\"%s_center\">", $class);
        if (puede_hacer($conn, $user->user, "invent_cambiar_item") && puede_hacer($conn, $user->user, "invent_fisico")) {
          echo "+ <input type=\"text\" name=\"item_add[$i]\" size=3 ";
          printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">", ($i+1)*(8+$alm)-1);
        }
        else
          echo "<small>N/D</small>";
        echo "</td>\n";

        printf("  <td class=\"%s_center\">", $class);
        if (puede_hacer($conn, $user->user, "invent_cambiar_item") && puede_hacer($conn, $user->user, "invent_fisico")) {
          echo "-<input type=\"text\" name=\"item_minus[$i]\" size=3 ";
          printf("onChange=\"document.f_articulos.elements[%d].checked='true'\">",  ($i+1)*(8+$alm)-1);
        }
        else
          echo "<small>N/D</small>";
        echo "</td>\n";
      }

      printf("  <td class=\"%s_center\"><input type=\"checkbox\" name=\"modify[%d]\"></td>\n",
             $class, $i);
	  unset($id_prov);
  }
?>
   <tr>
   <td class="bg0_right" colspan=<?php printf("%d", ($alm>0)*3+5) ?>><input type="reset" value="Cancelar">&nbsp;
   <input type="submit" value="Cambiar"></td>
   <input type="hidden" name="mode" value="express">
   <input type="hidden" name="action" value="cambia">
   <input type="hidden" name="num_ren" value="<? echo $num_ren ?>">
   <input type="hidden" name="order" value="<? echo $order ?>">
   <input type="hidden" name="order_by" value="<? echo $order_by ?>">
   <input type="hidden" name="offset" value="<? echo $offset ?>">
   <input type="hidden" name="id_depto" value="<? echo $id_dept ?>">
   <input type="hidden" name="prov" value="<? echo $prov ?>">
   <input type="hidden" name="alm_item" value="<? echo $alm ?>">
<? if (isset($id_prov)) { ?>
	<input type="hidden" name="id_prov" value="<? echo $id_prov ?>">
<? } ?>
  </tr>
  </table>
  </form>
