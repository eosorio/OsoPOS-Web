<? /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- bodies/invent_std_main.bdy -->

<table border=0 width='100%'>
 <tr>
      <th>&nbsp;</th>
<?
    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=codigo&order=";
    printf("%d",  $order_by=="codigo" && !$order);
    echo "$href_dept$href_prov\">C&oacute;digo</a></th>\n";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=descripcion&order=";
    printf("%d",  $order_by=="descripcion" && !$order);
    echo "$href_dept$href_prov\">Descripci&oacute;n</a></th>\n";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=pu&order=";
    printf("%d",  $order_by=="pu" && !$order);
    echo "$href_dept$href_prov\">Precio</a></th>\n";
?>
   <th>Des</th>
   <th>Cant</th>
   <th>Ex. Min</th>
   <th>Ex. max</th>
<?
    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=id_prov&order=";
    printf("%d",  $order_by=="id_prov" && !$order);
    echo "$href_dept$href_prov\">Proveedor</a></th>";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=id_dept&order=";
    printf("%d",  $order_by=="id_dept" && !$order);
    echo "$href_dept$href_prov\">Departamento</a></th>\n";
    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=prov_clave&order=";
    printf("%d",  $order_by=="p_costo" && !$order);
    echo "$href_dept$href_prov\">Clave Prov.</a></th>\n";
    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=p_costo&order=";
    printf("%d",  $order_by=="p_costo" && !$order);
    echo "$href_dept$href_prov\">P. costo</a></th>\n";
    echo " \n";

    for ($i=0; $i<$num_ren; $i++) {
	  if ($SQL_TYPE == "mysql"  &&  $i<$offset) {
		continue $offset;
	  }
      $reng = db_fetch_object($resultado, $i);
      $id_prov = $reng->id_prov;
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
      echo "  <td>\n";
      echo "   <a href=\"$PHP_SELF?order_by=$order_by&order=$order&action=borrar&offset=$offset";
      echo "$href_dept$href_prov&codigo=";
      echo str_replace(" ", "%20", htmlentities($reng->codigo));
      echo "\" border=0><img src=\"imagenes/borrar.gif\" border=0></a></td>";
      echo "  <td$td_fondo><a href=\"$PHP_SELF?codigo=";
      echo str_replace(" ", "%20", htmlentities($reng->codigo));
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_dept$href_prov\">";
      echo stripslashes($codigo) . "</a></td>\n";
      printf("  <td%s>%s</td>\n",
             $td_fondo, stripslashes($descripcion));
      echo "  <td align=\"right\"$td_fondo>";
      printf("%.2f</td>\n", $reng->pu);
      echo "  <td align=\"center\"$td_fondo>$reng->descuento</td>\n";
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
      echo "  <td$td_fondo><a href=\"proveedor.php?accion=muestra&id=$id_prov\">";
      if ($nick_prov[$id_prov])
        echo $nick_prov[$id_prov];
      else
        echo "&nbsp;";
      echo "</a></td>\n";
      echo "  <td$td_fondo>";
      if ($nm_depto[$id_dept])
        echo $nm_depto[$id_dept];
      else
        echo "&nbsp;";
      echo "</td>\n";
      echo "  <td align=\"right\"$td_fondo>";
      if (strlen($reng->prov_clave))
        printf("%s</td>\n", $reng->prov_clave);
      else
        echo "&nbsp;</font>\n";
      echo "  <td align=\"right\"$td_fondo>";
      printf("%.2f</td>\n", $reng->p_costo);
      echo " \n";
    }
?>
  </table>
