<? /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- bodies/invent_std_main.bdy -->

<table border=0 width='100%'>
 <tr>
 <th>&nbsp;</th>
 <th>&nbsp;</th>
<?php
  if (puede_hacer($conn, $user->user, "invent_borrar_item"))
    echo "  <th>&nbsp;</th>\n";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=codigo&order=";
    printf("%d",  $order_by=="codigo" && !$order);
    echo "$href_dept$href_prov\">C&oacute;digo</a></th>\n";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=descripcion&order=";
    printf("%d",  $order_by=="descripcion" && !$order);
    echo "$href_dept$href_prov\">Descripci&oacute;n</a></th>\n";

    if (isset($alm) && $alm>0) {
      echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=pu&order=";
      printf("%d",  $order_by=="pu" && !$order);
      echo "$href_dept$href_prov\">Precio</a></th>\n";
?>
   <th>Ex.</th>
   <th>Min</th>
   <th>Max</th>
<?
    } else if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
      echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=id_prov&order=";
      printf("%d",  $order_by=="id_prov" && !$order);
      echo "$href_dept$href_prov\">Proveedor</a></th>";
    }

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=id_dept&order=";
    printf("%d",  $order_by=="id_dept" && !$order);
    echo "$href_dept$href_prov\">Departamento</a></th>\n";
    if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
      echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=prov_clave&order=";
      printf("%d",  $order_by=="p_costo" && !$order);
      echo "$href_dept$href_prov\">Clave Prov.</a></th>\n";
    }

    if (puede_hacer($conn, $user->user, "invent_ver_pcosto") && $alm==0) {
          echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=p_costo&order=";
          printf("%d",  $order_by=="p_costo" && !$order);
          echo "$href_dept$href_prov\">P. costo</a></th>\n";
        }


    for ($i=0; $i<$num_ren; $i++) {
      $reng = db_fetch_object($resultado, $i);
      if (!isset($alm))
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

      if (!($i%4) || $i==0)
        $td_fondo = " bgcolor='#dcffdb'";
      else if (!(($i+2)%2))
        $td_fondo = " bgcolor='#fdffd3'";
      else
        $td_fondo = "";
?>
 </tr>
<?
      echo "  <tr>\n";
      echo "   <td>\n";
      echo "   <form action=\"$PHP_SELF\" method=\"post\">";
      echo "     $form_dept\n$form_prov\n";
      echo "     <input type=\"hidden\" name=\"action\" value=\"ver\">\n";
      echo "     <input type=\"hidden\" name=\"offset\" value=\"$offset\"\n";
      echo "     <input type=\"hidden\" name=\"order\" value=\"$order\">\n";
      echo "     <input type=\"hidden\" name=\"order_by\" value=\"$order_by\">\n";
      printf("     <input type=\"hidden\" name=\"codigo\" value=\"%s\">\n", $reng->codigo);
      printf("     <input type=\"hidden\" name=\"id_depto\" value=\"%d\">\n", $id_dept);
      echo "     <input type=\"image\" src=\"imagenes/lupa.png\" alt=\"eliminar\">\n";
      echo "   </form>\n";
      echo "   </td>\n";
      echo "   <td>\n";
      echo "   <form action=\"$PHP_SELF\" method=\"post\">";
      echo "     $form_dept\n$form_prov\n";
      echo "     <input type=\"hidden\" name=\"action\" value=\"add2cart\">\n";
      echo "     <input type=\"hidden\" name=\"offset\" value=\"$offset\"\n";
      echo "     <input type=\"hidden\" name=\"order\" value=\"$order\">\n";
      echo "     <input type=\"hidden\" name=\"order_by\" value=\"$order_by\">\n";
      printf("     <input type=\"hidden\" name=\"codigo\" value=\"%s\">\n", $reng->codigo);
      echo "     <input type=\"hidden\" name=\"qt\" value=1>\n";
      echo "     <input type=\"image\" src=\"imagenes/carrito.png\">\n";
      echo "   </form>\n";
      echo "   </td>\n";
      if (puede_hacer($conn, $user->user, "invent_borrar_item")) {
        echo "  <td>\n";
        echo "   <form action=\"$PHP_SELF\" method=\"post\">";
        echo "     <input type=\"hidden\" name=\"action\" value=\"borrar\">\n";
        echo "     <input type=\"hidden\" name=\"offset\" value=\"$offset\"\n";
        echo "     <input type=\"hidden\" name=\"order\" value=\"$order\">\n";
        echo "     <input type=\"hidden\" name=\"order_by\" value=\"$order_by\">\n";
        printf("     <input type=\"hidden\" name=\"codigo\" value=\"%s\">\n", $reng->codigo);
        echo "     <input type=\"image\" src=\"imagenes/borrar.gif\" border=0>\n";
        echo "   </form>\n";
        echo "  </td>\n";
          }
      printf("  <td$td_fondo><a href=\"%s?codigo=", $_SERVER['PHP_SELF']);
      echo str_replace(" ", "%20", htmlentities($reng->codigo));
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_dept$href_prov";
          if ($debug)
                echo "&debug=1";
          echo "\">";
      echo stripslashes($codigo) . "</a></td>\n";
      printf("  <td%s>%s</td>\n",
             $td_fondo, stripslashes($descripcion));

      if (isset($alm) && $alm>0) {
      echo "  <td align=\"right\"$td_fondo>";
//      printf("%.2f</td>\n", $reng->pu);
      printf("%.2f</td>\n", $reng->unitario);

      //      echo "  <td align=\"center\"$td_fondo>$reng->descuento</td>\n";
        echo "  <td align=\"center\"$td_fondo>";
        if ($reng->cant <= $reng->c_min)
          if (strstr($HTTP_USER_AGENT, "Mozilla/4"))
            echo "  <blink>$reng->cant</blink>";
          else
            echo "<div class=\"notify\">$reng->cant</div>";
        else
          echo "$reng->cant";
        echo "</td>\n";
        echo "  <td align=\"center\"$td_fondo>$reng->c_min</td>\n";
        echo "  <td align=\"center\"$td_fondo>$reng->c_max</td>\n";
      }
      else if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
        echo "  <td$td_fondo>";
        if ($nick_prov[$id_prov1]) {
          echo "<a href=\"proveedor.php?accion=muestra&id=";
          printf("%d\">", $id_prov1 + ($SQL_TYPE=="mysql"));
          printf("%s</a>", $nick_prov[$id_prov1]);
        }
        else
          echo "<small>N/D</small>";
        echo "</td>\n";
      }

      echo "  <td$td_fondo>";
      if ($nm_depto[$id_dept])
        echo $nm_depto[$id_dept];
      else
        echo "&nbsp;";
      echo "</td>\n";

      if (puede_hacer($conn, $user->user, "invent_ver_prov")) {

        echo "  <td align=\"right\"$td_fondo>";
        if (strlen($reng->prov_clave))
          printf("%s</td>\n", $reng->prov_clave);
        else
          echo "&nbsp;</font>\n";
      }

      if (puede_hacer($conn, $user->user, "invent_ver_pcosto") && $alm==0) {
                echo "  <td align=\"right\"$td_fondo>";
                printf("%.2f</td>\n", $reng->pcosto);
          }
      echo " \n";
    }
?>
  </table>
