<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- bodies/invent_std_main.bdy -->

<table border=0 width='100%'>
 <tr>
<?
  if (puede_hacer($conn, $user->user, "invent_borrar_item"))
	echo "  <th>&nbsp;</th>\n";

    echo "  <th><a href=\"" .$_SERVER['PHP_SELF'] . "?offset=0&order_by=codigo&order=";
    printf("%d&alm=%d",  $order_by=="codigo" && !$order, $alm);
    echo "$href_dept$href_prov\">C&oacute;digo</a></th>\n";

    echo "  <th><a href=\"" .$_SERVER['PHP_SELF'] . "?offset=0&order_by=descripcion&order=";
    printf("%d&alm=%d",  $order_by=="descripcion" && !$order, $alm);
    echo "$href_dept$href_prov\">Descripci&oacute;n</a></th>\n";

    echo "  <th><a href=\"" .$_SERVER['PHP_SELF'] . "?offset=0&order_by=pu&order=";
    printf("%d&alm=%d",  $order_by=="pu" && !$order, $alm);
    echo "$href_dept$href_prov\">Precio</a></th>\n";

    if (isset($alm) && $alm>0) {
?>
   <th>Ex.</th>
   <th>Min</th>
   <th>Max</th>
<?
    } else if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
      echo "  <th><a href=\"" .$_SERVER['PHP_SELF'] . "?offset=0&order_by=id_prov&order=";
      printf("%d&alm=%d",  $order_by=="id_prov" && !$order, $alm);
      echo "$href_dept$href_prov\">Proveedor</a></th>";
    }

    echo "  <th><a href=\"" .$_SERVER['PHP_SELF'] . "?offset=0&order_by=id_dept&order=";
    printf("%d&alm=%d",  $order_by=="id_dept" && !$order, $alm);
    echo "$href_dept$href_prov\">Departamento</a></th>\n";
    if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
      echo "  <th><a href=\"" .$_SERVER['PHP_SELF'] . "?offset=0&order_by=prov_clave&order=";
      printf("%d&alm=%d",  $order_by=="p_costo" && !$order, $alm);
      echo "$href_dept$href_prov\">Clave Prov.</a></th>\n";
    }

    if (puede_hacer($conn, $user->user, "invent_ver_pcosto") && !isset($alm)) {
	  echo "  <th><a href=\"" .$_SERVER['PHP_SELF'] . "?offset=0&order_by=p_costo&order=";
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
      echo " <tr>\n";
      if (puede_hacer($conn, $user->user, "invent_borrar_item")) {
		echo "  <td>\n";
		echo "   <a href=\"" .$_SERVER['PHP_SELF'] . "?order_by=$order_by&order=$order&action=borrar&offset=$offset";
		echo "$href_dept$href_prov&alm=$alm&codigo=";
		echo str_replace(" ", "%20", htmlentities($reng->codigo));
		echo "\" border=0><img src=\"imagenes/borrar.gif\" border=0 /></a></td>";
	  }
      echo "  <td$td_fondo><a href=\"" .$_SERVER['PHP_SELF'] . "?codigo=";
      echo str_replace(" ", "%20", htmlentities($reng->codigo));
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_dept$href_prov";
	  if ($debug)
		echo "&debug=1";
	  echo "&alm=$alm\">";
      echo stripslashes($codigo) . "</a></td>\n";
      printf("  <td%s>%s</td>\n",
             $td_fondo, stripslashes($descripcion));
      echo "  <td align=\"right\"$td_fondo>";
//      printf("%.2f</td>\n", $reng->pu);
      printf("%.2f</td>\n", $reng->unitario);
      //      echo "  <td align=\"center\"$td_fondo>$reng->descuento</td>\n";
      if (isset($alm) && $alm>0) {
        echo "  <td align=\"center\"$td_fondo>";
        if ($reng->cant <= $reng->min)
          if (strstr($HTTP_USER_AGENT, "Mozilla/4"))
            echo "  <blink>$reng->cant</bink>";
          else
            echo "<div class=\"notify\">$reng->cant</div>";
        else
          echo "$reng->cant";
        echo "</td>\n";
        echo "  <td align=\"center\"$td_fondo>$reng->min</td>\n";
        echo "  <td align=\"center\"$td_fondo>$reng->max</td>\n";
      }
      else if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
        echo "  <td$td_fondo><a href=\"proveedor.php?accion=muestra&id=";
        printf("%d\">", $id_prov1 + ($SQL_TYPE=="mysql"));
        if ($nick_prov[$id_prov1])
          echo $nick_prov[$id_prov1];
        else
          echo "&nbsp;";
        echo "</a></td>\n";
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

      if (puede_hacer($conn, $user->user, "invent_ver_pcosto") && !isset($alm)) {
		echo "  <td align=\"right\"$td_fondo>";
		printf("%.2f</td>\n", $reng->pcosto);
	  }
      echo " \n";
    }
?>
  </table>
