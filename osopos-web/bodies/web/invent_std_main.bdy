<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- bodies/invent_std_main.bdy -->

<table border=0 width='100%' cellpadding=0 cellspan=0>
<colgroup>
  <col width=20 span=3><col width=110><col width=*>
<?php
  if (isset($alm) && $alm>0) {
    echo "<col width=80><col width=30><col width=20 span=2><col width=160>\n";
  }
  else {
    echo "<col width=120><col width=120><col width=80>\n";
  }
?>
</colgroup>
 <tr>
 <th>&nbsp;</th>
 <th>&nbsp;</th>
<?php
  if (puede_hacer($conn, $user->user, "invent_borrar_item"))
    echo "  <th>&nbsp;</th>\n";

    echo "  <th><a href=\"" . $_SERVER['PHP_SELF'] . "?offset=0&order_by=codigo&order=";
    printf("%d",  $order_by=="codigo" && !$order);
    echo "$href_dept$href_prov\">C&oacute;digo</a></th>\n";

    echo "  <th><a href=\"" . $_SERVER['PHP_SELF'] . "?offset=0&order_by=descripcion&order=";
    printf("%d",  $order_by=="descripcion" && !$order);
    echo "$href_dept$href_prov\">Descripci&oacute;n</a></th>\n";

    if (isset($alm) && $alm>0) {
      echo "  <th><a href=\"" . $_SERVER['PHP_SELF'] . "?offset=0&order_by=pu&order=";
      printf("%d",  $order_by=="pu" && !$order);
      echo "$href_dept$href_prov\">Precio</a></th>\n";
?>
   <th>Ex.</th>
   <th>Min</th>
   <th>Max</th>
<?
    } else if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
      echo "  <th><a href=\"" . $_SERVER['PHP_SELF'] . "?offset=0&order_by=id_prov&order=";
      printf("%d",  $order_by=="id_prov" && !$order);
      echo "$href_dept$href_prov\">Proveedor</a></th>";
    }

    echo "  <th><a href=\"" . $_SERVER['PHP_SELF'] . "?offset=0&order_by=id_dept&order=";
    printf("%d",  $order_by=="id_dept" && !$order);
    echo "$href_dept$href_prov\">Depto.</a></th>\n";
/*    if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
      echo "  <th><a href=\"" . $_SERVER['PHP_SELF'] . "?offset=0&order_by=prov_clave&order=";
      printf("%d",  $order_by=="p_costo" && !$order);
      echo "$href_dept$href_prov\">Clave Prov.</a></th>\n";
    }*/

    if (puede_hacer($conn, $user->user, "invent_ver_pcosto") && $alm==0) {
          echo "  <th><a href=\"" . $_SERVER['PHP_SELF'] . "?offset=0&order_by=p_costo&order=";
          printf("%d",  $order_by=="p_costo" && !$order);
          echo "$href_dept$href_prov\">P. costo</a></th>\n";
    }

    echo "</tr>\n";
    /* Fin de cabecera de tabla */

    for ($i=0; $i<$num_ren; $i++) {
      $reng = db_fetch_object($resultado, $i);
      if (empty($alm) || $alm==0)
        $id_prov1 = $reng->id_prov1 ;
      $id_dept = $reng->id_depto;
      if (empty($search))
        $descripcion = $reng->descripcion;
      else
        $descripcion = str_replace($search, "<b>$search</b>", $reng->descripcion);
      if (empty($search))
        $codigo = $reng->codigo;
      else
        $codigo = str_replace($search, "<b>$search</b>", $reng->codigo);

      if (!($i%3) || $i==0)
        /*        $td_fondo = " bgcolor='#dcffdb'"; */
        $estilo = "a";
      else if (!(($i+1)%3))
        /*        $td_fondo = " bgcolor='#fdffd3'"; */
        $estilo = "c";
      else
        /*        $td_fondo = ""; */
        $estilo = "b";

      echo "  <tr class=\"estilo_$estilo\">\n";
      echo "   <td>\n";
      echo "   <form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">\n";
      echo "     $form_dept\n     $form_prov\n";
      echo "     <input type=\"hidden\" name=\"action\" value=\"ver\" />\n";
      echo "     <input type=\"hidden\" name=\"offset\" value=\"$offset\" />\n";
      echo "     <input type=\"hidden\" name=\"order\" value=\"$order\" />\n";
      echo "     <input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />\n";
      printf("     <input type=\"hidden\" name=\"codigo\" value=\"%s\" />\n", $reng->codigo);
      printf("     <input type=\"hidden\" name=\"id_depto\" value=\"%d\" />\n", $id_dept);
      echo "     <input type=\"image\" src=\"imagenes/lupa.png\" alt=\"eliminar\" />\n";
      echo "   </form>\n";
      echo "   </td>\n";
      echo "   <td>\n";
      echo "   <form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
      echo "     $form_dept\n$form_prov\n";
      echo "     <input type=\"hidden\" name=\"action\" value=\"add2cart\" />\n";
      echo "     <input type=\"hidden\" name=\"offset\" value=\"$offset\" />\n";
      echo "     <input type=\"hidden\" name=\"order\" value=\"$order\" />\n";
      echo "     <input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />\n";
      printf("     <input type=\"hidden\" name=\"codigo\" value=\"%s\" />\n", $reng->codigo);
      echo "     <input type=\"hidden\" name=\"qt\" value=1 />\n";
      echo "     <input type=\"image\" src=\"imagenes/carrito.png\" />\n";
      echo "   </form>\n";
      echo "   </td>\n";
      if (puede_hacer($conn, $user->user, "invent_borrar_item")) {
        echo "  <td>\n";
        echo "   <form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">\n";
        echo "     <input type=\"hidden\" name=\"action\" value=\"borrar\">\n";
        echo "     <input type=\"hidden\" name=\"offset\" value=\"$offset\">\n";
        echo "     <input type=\"hidden\" name=\"order\" value=\"$order\">\n";
        echo "     <input type=\"hidden\" name=\"order_by\" value=\"$order_by\">\n";
        printf("     <input type=\"hidden\" name=\"codigo\" value=\"%s\">\n", $reng->codigo);
        echo "     <input type=\"image\" src=\"imagenes/borrar.gif\" border=0 />\n";
        echo "   </form>\n";
        echo "  </td>\n";
          }
      printf("  <td class=\"ren_codigo\"><a href=\"%s?codigo=", $_SERVER['PHP_SELF']);
      echo str_replace(" ", "%20", htmlentities($reng->codigo));
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_dept$href_prov";
          if ($debug)
                echo "&debug=1";
          echo "\">";
      echo stripslashes($codigo) . "</a></td>\n";
      printf("  <td>%s</td>\n",
             stripslashes($descripcion));

      if (isset($alm) && $alm>0) {
      printf("  <td class=\"moneda\">");
      printf("%.2f</td>\n", $reng->unitario);

        printf("  <td class=\"serie\">");
        if ($reng->tangible=='t')
          if ($reng->cant <= $reng->c_min)
            if (strstr($_SERVER['HTTP_USER_AGENT'], "Mozilla/4"))
              echo "  <blink>$reng->cant</blink>";
            else
              echo "<div class=\"notify\">$reng->cant</div>";
          else
            echo "$reng->cant";
        else
          echo "&nbsp;";
        echo "</td>\n";

        printf("  <td class=\"serie\">");
        if ($reng->tangible=='t') echo $reng->c_min; else echo "&nbsp;";
        echo "</td>\n";
        printf("  <td class=\"serie\">");
        if ($reng->tangible=='t') echo $reng->c_max; else echo "&nbsp;";
        echo "</td>\n";
      }
      else if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
        printf("  <td class=\"ren_prov\">");
        if ($nick_prov[$id_prov1]) {
          echo "<a href=\"proveedor.php?accion=muestra&id=";
          printf("%d\">", $id_prov1 + ($SQL_TYPE=="mysql"));
          printf("%s</a>", $nick_prov[$id_prov1]);
        }
        else
          echo "<small>N/D</small>";
        echo "</td>\n";
      }

      printf("  <td class=\"ren_depto\">");
      if ($nm_depto[$id_dept])
        echo $nm_depto[$id_dept];
      else
        echo "&nbsp;";
      echo "</td>\n";

      /*      if (puede_hacer($conn, $user->user, "invent_ver_prov")) {

        echo "  <td align=\"right\"$td_fondo>";
        if (strlen($reng->prov_clave))
          printf("%s</td>\n", $reng->prov_clave);
        else
          echo "&nbsp;</font>\n";
      }*/

      if (puede_hacer($conn, $user->user, "invent_ver_pcosto") && $alm==0) {
        printf("  <td class=\"moneda\">");
        printf("%.2f</td>\n", $reng->pcosto);
      }
      echo " \n";
      echo "</tr>\n";
    }
?>
  </table>
