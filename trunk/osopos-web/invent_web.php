<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Invent Web. Módulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2009 Eduardo Israel Osorio Hernández

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los términos de la Licencia Pública General GNU
publicada por la Free Software Foundation: ya sea en la versión 2 de la
Licencia, o (a su elección) en una versión posterior. 

        Este programa es distribuido con la esperanza de que sea útil, pero
SIN GARANTIA ALGUNA; incluso sin la garantía implícita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. Véase la Licencia Pública General
GNU para mayores detalles. 

        Debería usted haber recibido una copia de la Licencia Pública General
GNU junto con este programa; de no ser así, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 

*/ 


{
  include("include/general_config.inc");
  include("include/invent_config.inc");
  include("include/pos-var.inc");
  if ($PROGRAMA == "video") {
    include("include/videopos.inc");
  }
  include("include/pos.inc");

  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }


  /* Variables globales */
  if (isset($_POST["action"]))
    $action = $_POST["action"];
  else if (isset($_GET["action"]))
    $action = $_GET["action"];
  else
    $action = "";

  if (isset($_COOKIE["alm"]))
    $alm = $_COOKIE["alm"];
  else
    $alm = lee_config($conn, "ALM_DEF");

  if (isset($_POST['id_prov1']))
    $id_prov1 = $_POST['id_prov1'];
  else if (isset($_GET['id_prov1']))
    $id_prov1 = $_GET['id_prov1'];
  else
    $id_prov1 = -1;

  if (isset($_POST['id_dept']))
    $id_dept = $_POST['id_dept'];
  else if (isset($_GET['id_dept']))
    $id_dept = $_GET['id_dept'];
  else
    $id_dept = -1;


  if (isset($_POST["action"]))
    $action = $_POST["action"];
  else if (isset($_GET["action"]))
    $action = $_GET["action"];

  if (isset($_POST["offset"]))
    $offset = $_POST["offset"];
  else if (isset($_GET["offset"]))
    $offset = $_GET["offset"];
  else $offset = 0;

  if (isset($_POST["limit"]))
    $limit = $_POST["limit"];
  else if (isset($_GET["limit"]))
    $limit = $_GET["limit"];
  else $limit = 10;

  if (isset($_POST["search"]))
    $search = $_POST["search"];

  if (isset($_POST["order_by"]))
    $order_by = $_POST["order_by"];
  else if (isset($_GET["order_by"]))
    $order_by = $_GET["order_by"];
  else {
    if (!isset($PROGRAMA) || $PROGRAMA == "web")
      $order_by = "descripcion";
    else if ($PROGRAMA == "video")
      $order_by = "titulo";
  }

  if (isset($_POST["order"]))
    $order = $_POST["order"];
  else if (isset($_GET["order"]))
    $order = $_GET["order"];
  else $order = 1; /* Ascendente */

  if (isset($_POST["codigo"]))
    $codigo = $_POST["codigo"];
  else if (isset($_GET["codigo"]))
    $codigo = $_GET["codigo"];

  if (isset($_POST["mode"]))
    $mode = $_POST["mode"];
  else if (isset($_GET["mode"]))
    $codigo = $_GET["mode"];
  else
    $mode = "normal";  /* express, normal, baja_ex */

  /* igm*/ /* QUITAR ESTA MARRANADA Y CONVERTIRLA EN CONSULTA A BD */
  $tasa_util = array();
  $tasa_util[0] = 40;
  $tasa_util[1] = 35;
  $tasa_util[2] = 30;
  $tasa_util[3] = 20;
  $tasa_util[4] = 10;
  /* AQUI TERMINA MI MARRANADA DE INJERTO DE CÓDIGO */

  /* Fin de variables globales */

  if ($action=="add2cart") {
    $item_agregado = agrega_carrito_item($conn, $codigo, $qt);
  }

  if (!isset($_POST['modulo']) && !isset($_GET['modulo']) && !isset($_POST['boton']))
    $invent_footer = TRUE;

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
   <title>OSOPoS Web - Invent v. <?php echo $INVENT_VERSION ?></title>
   <?php include("menu/menu_principal.inc"); ?>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/extras.css">
   <style type="text/css">
    tr.bg1 { background: <?php echo $bg_color1 ?> }
    tr.bg2 { background: <?php echo $bg_color2 ?> }
    td.right_red {text-align: right; font-color: red}
    td.item_modify {text-align: top }
    div.notify {font-style: italic; color: red}
    div.head_almacen { text-align: center; font-size: big; font-weight: bold }
   </style>

</head>
<?php
   if (!puede_hacer($conn, $user->user, "invent_general")) {
     echo "<body>\n";
     include("menu/menu_principal.bdy");
     echo "<br/><div class=\"mens_inf\">Usted no tiene permisos para accesar este módulo</div><br/>\n";
     echo "<a href=\"index.php\">Regresar a menú principal</a>\n";
     echo "</body>\n";
     exit();
   }
  echo "<body ";
  if ($action == "muestra") {
    if (isset($alm) && $alm>0)
      echo "onload=\"document.articulo.descripcion.focus()\"";
    else
      echo "onload=\"document.articulo.pu.focus()\"";
  }
  else if ($action == "agrega")
    echo "onload=\"document.articulo.codigo.focus()\"";
  else if ($invent_footer)
    echo "onload=\"document.f_busqueda.search.focus()\"";

  echo ">\n";
  include("menu/menu_principal.bdy");
  echo "<br/>\n";

  if(!empty($depto)) {
    if ($depto!="Todos" && $id_dept>=0) {
      if ($PROGRAMA == "web")
        $query = "SELECT id AS id_dept FROM departamento WHERE nombre='$depto'";
      else if($PROGRAMA == "video")
        $query = "SELECT id AS id_dept FROM genero WHERE nombre='$depto'";

      if (!$resultado = db_query($query, $conn)) {
        $mens = "<div class=\"error_f\">Error al consultar catálogo de departamentos</div>\n";
        die($mens);
      }
      $id_dept = db_result($resultado, 0, "id_dept");
    }
    else
      unset($id_dept);
  }
  $href_dept = isset($id_dept) && $id_dept>=0 ? sprintf("&id_dept=%d", $id_dept) : "";
  $form_dept = isset($id_dept) && $id_dept>=0 ? sprintf("\"id_dept\" value=\"%d\" />\n", $id_dept) : "\"depto\" value=\"Todos\">\n";
  $form_dept = "<input type=\"hidden\" name=" . $form_dept;
  if (isset($prov1))
    $id_prov1 = $prov1;

  if (isset($prov2)) {
    if ($prov2== "Todos")
      unset($id_prov2);
    else {
      $query = "SELECT id FROM proveedores WHERE nick='$prov2'";

      if (!$resultado = db_query($query, $conn)) {
        die ("Error al consultar proveedores<br/>\n");
      }
      $id_prov2 = db_result($resultado, 0, "id");
    }
  }
/*  if (isset($prov3)) {
    if ($prov3== "Todos")
      unset($id_prov3);
    else {
      $query = "SELECT id FROM proveedores WHERE nick='$prov3'";

      if (!$resultado = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br/>\n";
        exit();
      }
      $id_prov3 = db_result($resultado, 0, "id");
    }
  }*/
  $href_prov = isset($id_prov1) && $id_prov1>=0 ? sprintf("&id_prov1=%d", $id_prov1) : "&prov=Todos";
  $form_prov = isset($id_prov1) && $id_prov1>=0 ? sprintf("\"id_prov1\" value=\"%d\" />\n", $id_prov1) : "\"prov\" value=\"Todos\">\n";
  $form_prov = "<input type=\"hidden\" name=" . $form_prov;
  $query = "SELECT id,nick FROM proveedores ORDER BY id";

  if (!$resultado = db_query($query, $conn)) {
    die("<div class=\"error_f\">Error al consultar proveedores</div>\n");
  }
  $num_ren_prov = db_num_rows($resultado);

  $nick_prov = array();
  for ($i=0; $i<$num_ren_prov; $i++) {
    $reng = db_fetch_object($resultado, $i);
    $id = $reng->id;
    //    $nick_prov[$id - ($SQL_TYPE=="mysql")] = $reng->nick;
    $nick_prov[$id] = $reng->nick;
  }
  asort($nick_prov);
  reset($nick_prov);

  if ($PROGRAMA == "web")
    $query = "SELECT id,nombre FROM departamento ORDER BY id";
  else if ($PROGRAMA == "video")
    $query = "SELECT id,nombre FROM genero ORDER BY id";

  if (!$resultado = db_query($query, $conn)) {
    die("<div class=\"error_f\">Error al consultar catálogo</div>\n");
  }

  $num_ren_depto = db_num_rows($resultado);

  $nm_depto = array();
  for ($i=0; $i<$num_ren_depto; $i++) {
    $reng = db_fetch_object($resultado, $i);
    $id = $reng->id;
    $nm_depto[$id] = $reng->nombre;
  }
  asort($nm_depto);
  reset($nm_depto);

  if ($action == "inserta") {
    $existe_codigo = 0;
    $query = sprintf("SELECT (codigo) FROM articulos WHERE codigo='%s'", addslashes($codigo));
    $r = db_query($query, $conn);
    if (db_num_rows($r)) {
      echo "<div class=\"error_nf\">El código $codigo ya existe en catálogo</div>\n";
      $existe_codigo = TRUE;
      $descripcion = $_POST['descripcion'];
      $val_cod = "value=\"$codigo\"";
      $val_cod2 = sprintf("value=\"%s\"", $_POST['codigo2']);
      $val_desc = sprintf("value=\"%s\"", htmlspecialchars($_POST['descripcion']));
      $val_p_costo = sprintf("value=%.2f", $_POST['p_costo']);
      $val_disc = sprintf("value=\"%.2f\"", $_POST['descuento']);
      $val_u_empaque = sprintf("value=\"%f\"", $_POST['u_empaque']);
      $val_iva_porc =  sprintf("value=\"%.2f\"", $_POST['iva_porc']);
      $val_prov_clave = sprintf("value=\"%s\"", $_POST['prov_clave']);
      $val_divisa = $_POST['divisa'];
      $val_serie = !empty($_POST['incluye_serie']) ? "checked" : "";
      $val_tangible = !empty($_POST['granel']) ? "checked" : "";
      $val_granel = !empty($_POST['granel']) ? "checked" : "";
      $val_submit = "value=\"Cambiar datos\"";
      $alm = 0;
      $almacenes = array();
      foreach ($_POST['almac'] as $b_a)
        if (!empty($b_a))
          $almacenes[] = $b_a;
      $action = "muestra";
    }
    else {
      $query = "INSERT INTO articulos (codigo, descripcion, ";
      $query.= "id_prov1, id_prov2, id_depto, p_costo, prov_clave, iva_porc, divisa, ";
      $query.= "codigo2, tax_0, tax_1, tax_2, tax_3, tax_4, tax_5, ";
      $query.= "empaque, serie, granel, tangible) ";
      $query.= sprintf("VALUES ('%s', ", addslashes($_POST['codigo']));
      if (strlen($_POST['descripcion']))
        $query .= sprintf("'%s', ", str_replace("&quot;", "\"", $_POST['descripcion']));
      else
        $query .= "'', ";
      $query.= sprintf("%d, %d, %d, %f, '%s', %d, ", $_POST['id_prov1'], $_POST['id_prov2'],
                       $_POST['id_depto'], $_POST['p_costo'], $_POST['prov_clave'],
                       $_POST['iva_porc']);
      $query.= sprintf("'%3s', '%s' ", $_POST['divisa'], $_POST['codigo2']);
      for ($j=0; $j<$MAXTAX; $j++)
        $query.= sprintf(", %f", $_POST["imp_porc[$j]"]);
      $query.= sprintf(", '%f', ", $_POST['u_empaque']);
      $query.= empty($_POST['incluye_serie']) ? "'f', " : "'t', ";
      $query.= empty($_POST['granel']) ? "'f', " : "'t', ";
      $query.= empty($_POST['tangible']) ? "'f' " : "'t' ";
      $query.= ")";
      if ($debug>0)
        echo "<i>$query</i><br/>\n";
      $resultado1 = db_query($query, $conn);
      if (!$resultado1) {
        $db_msg1 = db_errormsg($conn);
      }

      $query2 = "INSERT INTO article_desc (code, descripcion, long_desc, img_location) VALUES ";
      $query2.= sprintf("('%s', '%s', '%s', '%s')", $codigo, $_POST['descripcion'],
                        str_replace("'", "\'", strip_tags($long_desc)), $_POST['img_source']);
      $resultado2 = db_query($query2, $conn);
      if (!$resultado2) {
        $db_msg2 = db_errormsg($conn);
      }

      if (!$resultado1) {
        $msg = sprintf("Error al insertar articulo %s %s",
                       htmlentities($codigo), htmlentities($_POST['descripcion']));
        die("<div class=\"error_f\">$msg</div><br/>\n");
      }
      if (!$resultado2) {
        echo "<div class=\"error_nf\">Error al insertar descripción expandida del artículo</div><br/>\n";
      }

      for ($i=0; $i<count($almac); $i++)
        if (inserta_en_almacen($conn, $almac[$i], $codigo) == 0)
          printf("<div class=\"men_res\">Articulo %s %s anexado en almacen %d</div>",
                 $codigo, stripslashes($_POST['descripcion']), $almac[$i]);

      if ($resultado1){
        printf("<div class=\"men_res\">Art&iacute;culo <i>%s %s</i> agregado.</div><br/>\n",
               $codigo, stripslashes($_POST['descripcion']));
        $action = "muestra";
        $alm = $almac[0];
      }
    } /* else de codigo de producto duplicado */
  }

  if ($action == "cambia") {
    if (!puede_hacer($conn, $user->user, "invent_cambiar_item")) {
      echo "<h4>Usted no tiene permisos para modificar items</h4>\n";
    }
    else {
      /*      if ($PROGRAMA == "web")
        $query = "SELECT id FROM departamento WHERE nombre='$depto'";
      else if ($PROGRAMA == "video")
        $query = "SELECT id FROM genero WHERE nombre='$depto'";
      if (!$resultado = db_query($query, $conn)) {
        echo "Error al consultar departamentos.<br/>\n$query<br/>\n";
        exit();
      }
      if (db_num_rows($resultado))
        $id_dept = db_result($resultado, 0, "id");
      else
        $id_dept = 0;
      */
      if ($mode == "express") {
        if ($alm_item>0)
          $tabla = sprintf("almacen_1");
        else
          $tabla = "articulos";
        for ($i = 0; $i<$num_ren; $i++) {
          if ($modify[$i]) {
            if ($alm>0) {
              $query = sprintf("UPDATE almacen_1 SET pu=%f, pu2=%f  ",
                             $pu[$i], $pu2[$i]);
              if (puede_hacer($conn, $user->user, "invent_fisico"))
                $query.= sprintf(", cant=%f ", $qt[$i] - $item_minus[$i] + $item_add[$i]);

              $query.= sprintf(" WHERE codigo='%s' AND id_alm=%d",
                               addslashes($code[$i]), $alm_item);
            }
            else {
              $query = sprintf("UPDATE articulos SET p_costo=%f, descripcion='%s' ",
                               $p_costo[$i], str_replace("'", "\'", $desc[$i]));
              $query.= sprintf(" WHERE codigo='%s'", addslashes($code[$i]));
            }

            if ($debug>0)
              echo "<i>$query</i><br/>\n";
            if (!$resultado = db_query($query, $conn)) 
              printf("Error al actualizar articulo %s, $s<br/>\n", $code[$i], $desc[$i]);
            else {
              printf("<b>Art&iacute;culo <i>%s %s</i> actualizado.</b><br/>\n",
                     $code[$i], stripslashes($desc[$i]));
              $action = "";
            }
          }
        }
      }
      else {

        $updt_res = 0; $act_rentas = 0;
        if (isset($alm_item) && $alm_item>0) {
          $alquiler = empty($alquiler) ? "f" : "t";
          $query = sprintf("UPDATE almacen_1 SET pu=%f, pu2=%f, pu3=%f, pu4=%f, pu5=%f, ",
                           $pu, $precio2, $precio3, $precio4, $precio5);
          $query.= sprintf("c_min=%f, c_max=%f, ",
                            $ex_min, $ex_max);
          $query.= sprintf("codigo2='%s', ", $codigo2);
          $query.= sprintf("divisa='%s', ", $divisa);
          $query.= sprintf("tax_0=%.2f, tax_1=%.2f, tax_2=%.2f, tax_3=%.2f, tax_4=%.2f, tax_5=%.2f, ", 
                           $imp_porc[0], $imp_porc[1], $imp_porc[2], $imp_porc[3], $imp_porc[4], $imp_porc[5]);
          $query.= sprintf("alquiler='%s', id_alm=%d ", $alquiler, $alm_item);
          $query.= sprintf("WHERE codigo='%s' AND id_alm=%d", $codigo, $alm_item);
        }
        else {

          $incluye_serie = empty($_POST['incluye_serie']) ? "f" : "t";
          $query = sprintf("UPDATE articulos SET descripcion='%s', ", str_replace("'", "\'", $descripcion));
          $query.= sprintf("id_prov1=%d, id_prov2=%d, id_depto=%d, ",
                           $id_prov1, $id_prov2, $id_depto);
          $query.= sprintf("p_costo=%.2f, prov_clave='%s', codigo2='%s', ", $p_costo, $prov_clave, $codigo2);
          $query.= sprintf("iva_porc=%.2f, divisa='%s', ", $iva_porc, $divisa);
          $query.= sprintf("tax_0=%.2f, tax_1=%.2f, tax_2=%.2f, tax_3=%.2f, tax_4=%.2f, tax_5=%.2f, ", 
                           $imp_porc[0], $imp_porc[1], $imp_porc[2], $imp_porc[3], $imp_porc[4], $imp_porc[5]);
          $query.= sprintf("empaque=%f, serie='%s' ", $u_empaque, $incluye_serie);
          $query.= sprintf("WHERE codigo='%s'", $codigo);
        }
        if (isset($debug) && $debug>0)
          echo "<i>$query</i><br/>\n";
        if (!$resultado = db_query($query, $conn)) {
          $updt_res += 1;
          echo "<div class=\"error_nf\">Error: No puedo actualizar precios</div><br/>\n";
        }

        $query2 = sprintf("UPDATE article_desc SET long_desc='%s' ",
                          addslashes(strip_tags($long_desc)));
        if (!empty($_POST['img_source']))
          $query2.= sprintf(", img_location='%s' ", $_POST['img_source']);
        $query2.= sprintf("WHERE code='%s'", $codigo);

        if (isset($debug) && $debug>0)
          echo "<i>$query2</i><br/>\n";
        if (!$resultado2 = db_query($query2, $conn)) {
          $updt_res += 2;
          echo "<div class=\"error_nf\">Error: No puedo actualizar detalles del producto</div><br/>\n";
        }

        if (empty($alq_prev) && $alquiler=="t") {
          $query = "INSERT INTO articulos_rentas SELECT '$codigo' AS codigo, p0_1, p0_2, p0_3, p0_4, p0_5, ";
          $query.= "p1_1, p1_2, p1_3, p1_4, p1_5, p2_1, p2_2, p2_3, p2_4, p2_5, p3_1, p3_2, p3_3, p3_4, p3_5, ";
          $query.= "p4_1, p4_2, p4_3, p4_4, p4_5, p5_1, p5_2, p5_3, p5_4, p5_5, p6_1, p6_2, p6_3, p6_4, p6_5, ";
          $query.= "tiempo0, tiempo1, tiempo2, tiempo3, tiempo4, tiempo5, tiempo6, unidad_t ";
          $query.= "FROM articulos_rentas WHERE codigo='DEFAULT' ";
          $act_rentas++;
        }
        else if(!empty($alq_prev) && $alquiler=="f") {
          $query = "DELETE FROM articulos_rentas WHERE codigo='$codigo' ";
          $act_rentas++;
        }

        if ($act_rentas) {
          if (!$db_res = db_query($query, $conn)) {
            echo "<div_class=\"error_nf\">Error: No puedo actualizar costos de rentas</div><br/>\n";
            $updt_res += 4;
          }
          if ($debug>0)
            echo "<i>$query</i><br/>\n";
        }
        //        if ($resultado && $resultado2 && $resultado3) {
        if ($updt_res == 0) {

          printf("<b>Art&iacute;culo <i>%s %s</i> actualizado.</b><br/>\n",
                 $codigo, stripslashes($descripcion));
          if ($act_rentas) {
            if ($alquiler=="t")
              echo "<b>Se ingresó el artículo al catálogo de rentas</b><br/>\n";
            else
              echo "<b>Se eliminó el artículo del catálogo de rentas</b><br/>\n";
          }
          $action = "";
        }
        else {
          exit();
        }

      }
      /* Si eligen modificar precios de almacen... */
      if (isset($muestra_alm) && $muestra_alm>0) {
        $action = "muestra";
        $alm = $muestra_alm;
      }
    } /* else de if puede_hacer(...cambiar_item...) */
  }

/*igm*/ /*+++++++++++++++++++++++++++ OJO ++++++++++++++++++++++++*/
/* Revisar si tendría utilidad en el script el código de la siguiente accion */
  if ($action == "agrega_p_renta") {
    $q1 = ""; $q2 = "";
    for ($dia=0; $dia < 7; $dia++) {
      for ($i=0; $i<5; $i++) {
        if ($i) {
          $q1.= ", ";
          $q2.= ", ";
        }
        $s_mat = sprintf("p%d_%d", $dia, $i);
        $q1.= $s_mat;
        $q2.= sprintf("%.2f", $art[$s_mat]);
      }
    }
    $query = sprintf("INSERT INTO articulos_rentas (codigo, %s) VALUES ('%s', %s) ",
                     $q1, $codigo, $q2);

    $action == "muestra";
  }

  if ($action == "cambia_p_renta") {
    $q1 = ""; $q2 = "";
    for ($dia=0; $dia < 7; $dia++) {
      $t_mat = sprintf("tiempo%d", $dia);
      $q1.= sprintf("\"%s\"=%d, ", $t_mat, $art[$t_mat]);
      for ($i=1; $i<6; $i++) {
        $s_mat = sprintf("p%d_%d", $dia, $i);
        $q1.= sprintf("\"%s\"=%.2f", $s_mat, $art[$s_mat]);
        if ($i!=5 || $dia!=6)
          $q1.= ", ";
      }
    }
    $query = sprintf("UPDATE articulos_rentas SET %s WHERE codigo='%s' ",
                     $q1, $codigo);


    if (!$db_res = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al consultar catálogo de departamentos</div>\n";
      die($mens);
    }
    else
      echo "<i>Costos de rentas y tiempos de entrega actualizados</i><br/>\n";

    $action == "muestra";
  }

  if (($action == "muestra"  ||  $action == "agrega")) {

    if ($action == "muestra" && (empty($existe_codigo) || $existe_codigo==0)) {
      if ($PROGRAMA == "web") {
        if (isset($alm) && $alm>0) {
          $query = "SELECT alm.*, art.descripcion, art.prov_clave, art.id_prov1, art.id_prov2, art.id_depto, ";
          $query.= "art.iva_porc, art.p_costo, art.serie, art.tangible, alm.alquiler, ";
          $query.= "art.granel FROM almacen_1 alm, articulos art ";
          $query.= sprintf("WHERE alm.codigo='$codigo' AND art.codigo='$codigo' AND alm.id_alm=%d", $alm);
        }
        else {
          $query = "SELECT descripcion, prov_clave, id_prov1, id_prov2, id_depto, iva_porc, ";
          $query.= "divisa, empaque, codigo2, p_costo, serie, tangible, granel ";
          $query.= "FROM articulos ";
          $query.= "WHERE codigo='$codigo' ";
        }

        if (!$resultado = db_query($query, $conn)) {
          die("<div class=\"error_f\"Error al consultar datos de producto <pre>$codigo</pre></div>\n");
        }
        if (isset($debug) && $debug>0)
          echo "<i>$query</i><br/>\n";

        $reng = db_fetch_object($resultado, 0);
        $val_cod = "value=\"$codigo\"";
        $val_cod2 = sprintf("value=\"%s\"", $reng->codigo2);
        $val_desc = sprintf("value=\"%s\"", htmlspecialchars($reng->descripcion));
        $val_pu  = sprintf("value=\"%.2f\"", $reng->pu);
        $val_pu2 = sprintf("value=\"%.2f\"",  $reng->pu2);
        $val_pu3 = sprintf("value=\"%.2f\"",  $reng->pu3);
        $val_pu4 = sprintf("value=\"%.2f\"",  $reng->pu4);
        $val_pu5 = sprintf("value=\"%.2f\"",  $reng->pu5);
        $val_p_costo = sprintf("value=\"%.2f\"", $reng->p_costo);
        $val_disc = sprintf("value=\"%.2f\"", $reng->descuento);
        $val_u_empaque = sprintf("value=\"%f\"", $reng->empaque);
        $val_u_medida = sprintf("value=\"%s\"", $reng->medida);
        $val_ex = "value=\"" . $reng->cant . "\"";
        $val_min = "value=\"" . $reng->c_min . "\"";
        $val_max = "value=\"" . $reng->c_max . "\"";
        $val_iva_porc =  sprintf("value=\"%.2f\"", $reng->iva_porc);
        $val_prov_clave = sprintf("value=\"%s\"", $reng->prov_clave);
        $val_divisa = $reng->divisa;
        $val_imp_porc[0] = sprintf("value=\"%.2f\"", $reng->tax_0);
        $val_imp_porc[1] = sprintf("value=\"%.2f\"", $reng->tax_1);
        $val_imp_porc[2] = sprintf("value=\"%.2f\"", $reng->tax_2);
        $val_imp_porc[3] = sprintf("value=\"%.2f\"", $reng->tax_3);
        $val_imp_porc[4] = sprintf("value=\"%.2f\"", $reng->tax_4);
        $val_imp_porc[5] = sprintf("value=\"%.2f\"", $reng->tax_5);
        $val_serie = $reng->serie=='t' ? "checked" : "";
        $val_tangible = $reng->tangible=='t' ? "checked" : "";
        $val_granel = $reng->granel=='t' ? "checked" : "";
        $val_alquiler = $reng->alquiler=='t' ? "checked" : "";
        $val_submit = "value=\"Cambiar datos\"";

        $query = "SELECT img_location,long_desc FROM article_desc WHERE code='$codigo'";
        if (!$resultado = db_query($query, $conn)) {
          die("<div class=\"error_f\">Error al leer descripción ampliada del producto</div>");
        }

        $reng2 = db_fetch_object($resultado, 0);
        $long_desc = $reng2->long_desc;
        $img_location = $reng2->img_location;

      }
      else if ($PROGRAMA == "video") {
        if (isset($alm) && $alm>0) {
          /* Aqui va el query para catálogo general */
          $query = "SELECT flm.* FROM filme flm WHERE codigo='$codigo' ORDER BY titulo ASC";
        }
        else {
          /*Aqui el código para la sucursal */
          $query = "SELECT flm.* FROM filme flm WHERE codigo='$codigo' ORDER BY titulo ASC";
        }

        if (isset($debug) && $debug>0)
          echo "<i>$query</i><br/>\n";
        if (!$db_res = db_query($query, $conn)) {
          die("<div class=\"Error al consultar catálogo</div>");
        }
        $ren = db_fetch_object($db_res, 0);
        $val_tit_orig = $ren->tit_orig;
        $val_product = $ren->product;
        $val_anio = $ren->anio;
        $val_elenco = muestra_reparto($conn, $val_codigo);
        $val_codigo = $ren->codigo;
        $val_director = $ren->director;
        $val_genero1 = $ren->genero1;
        $val_genero2 = $ren->genero2;
        $val_clasif = $ren->clasif;
        $val_duracion = $ren->duracion;
        $val_pais = $ren->pais;
        $val_dvd_region1 = $ren->dvd_region1;
        $val_dvd_region2 = $ren->dvd_region2;
        $val_idioma = $ren->idioma;
        $val_subtit = $ren->subtit;
        $val_resenia = $ren->resenia;
        $val_imagen = sprintf("<img src=\"imagenes/video/fichas/%s\" />", $ren->nm_imagen);
        $col_w1 = 250;
      }
    }
    if ($action == "agrega") {
      $alm = 0;
      $val_submit = "value=\"Agregar producto\"";
      $val_divisa = $DIVISA_OMISION;
      $val_tit_orig = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=\"60\" />\n",
                              "tit_orig", $ren->tit_orig);
      $val_product = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />\n", "product", $ren->product);
      $val_anio = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\"/>\n", "anio", $ren->anio);
      $val_elenco = sprintf("<input type=\"text\" name=\"%s\" />\n", "elenco");
      $val_codigo = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />\n", "codigo", $ren->codigo);
      $val_director = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />\n", "director", $ren->director);
      $val_genero1 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />\n", "genero1", $ren->genero1);
      $val_genero2 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />\n", "genero2", $ren->genero2);
      $val_clasif = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />\n", "clasif", $ren->clasif);
      $val_duracion = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />\n", "duracion", $ren->duracion);
      $val_pais = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=\"10\" />\n",
                          "pais", $ren->pais);
      $val_dvd_region1 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=\"1\">\n",
                                 "dvd_region1", $ren->dvd_region1);
      $val_dvd_region2 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=\"1\">\n",
                                 "dvd_region2", $ren->dvd_region2);
      $val_idioma = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "idioma", $ren->idioma);
      $val_subtit = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "subtit", $ren->subtit);
      $val_resenia = sprintf("<textarea name=\"%s\" cols=\"80\" rows=\"8\">%s</textarea>\n", "resenia", $ren->resenia);

      $img_dir = lee_config($conn, "IMG_DIR");
      $pwd_dir = lee_config($conn, "PWD_DIR");
      $val_imagen = sprintf("Ubicaci&oacute;n de la imagen: <input type=\"file\" name=\"img_source\" size=\"60\" value=\"%s/%s/$img_location\" />\n", $pwd_dir, $img_dir);
      $col_w1 = 100;
    }

    if ($PROGRAMA == "web")
      if (!isset($modulo))
        include ("forms/web/invent_std_item.bdy");
    else if ($PROGRAMA == "video") {
      include ("forms/video/filme_datos.bdy");
    }
    
    echo "<hr/>\n";
  }

  if ($action == "borrar") {
    if (!puede_hacer($conn, $user->user, "invent_borrar_item")) {
      echo "<h4>Usted no cuenta con permisos para borrar art&iacute;culos</h4>\n";
    }
    else {
      $query = "SELECT max(id) FROM almacenes ";
      $resultado = db_query($query, $conn);
      if ((!$resultado) || (!$max_alm = db_result($resultado, 0, 0))) {
          echo "<div class=\"error_ft\">Inconsistencia en catálogo de almacenes. ";
          echo "Consulte con su administrador del sistema</div><br/>\n";
          echo "</body>\n";
          exit();
      }

      if (isset($alm) && $alm>0) {
        $query = "DELETE FROM almacen_1 WHERE codigo='$codigo' AND id_alm=$alm";
        if (!$resultado = db_query($query, $conn))
          echo "<div class=\"error_nf\">Error al eliminar producto del almacen $alm.</div><br/>\n";
        else
          echo "Producto eliminado del almacen $alm.<br/>\n";
      }
      else {
        $existe_alm = 0;
        for ($i=1; $i<=$max_alm && !$existe_alm; $i++) {
          $existe_alm = busca_codigo($conn, $codigo, $i);
          if ($existe_alm > 0)
            echo "<div class=\"advt\">El producto existe en almacen $i, se cancela su eliminación</div><br/>\n";
          else if ($existe_alm < 0)
            echo "<div class=\"error_ft\">Error al consultar producto en almacen $i. Consulte con su administrador del sistema</div><br/>\n";
        }
        if (!$existe_alm) {
          $query = "DELETE FROM article_desc WHERE code='$codigo'";
          if (!$resultado = db_query($query, $conn))
            echo "<div class=\"error_nf\">Error al eliminar descripción ampliada del producto.</div><br/>\n";

          $query = "DELETE FROM articulos WHERE codigo='$codigo'";
          if (!$resultado = db_query($query, $conn)) {
            die("<div class=\"error_nf\">Error al eliminar articulo del catálogo general.</div><br/>\n");
          }
          else
            echo "<b>Art&iacute;culo <i>$codigo</i> eliminado.</b><br/>\n";
        }
      }

    }
  }

  if ($action == "ver" && strlen($codigo)) {
    if (!isset($boton) || $boton == "general") {
      include ("bodies/web/invent_item_gral.bdy");
    }
    else if ($boton == "costos") {
      include("bodies/web/invent_costos.bdy");
    }
    else if ($boton == "pventa") {
      include("bodies/web/invent_pventa.bdy");
    }
    else if ($boton == "prenta") {
      $boton = "prenta";

      include("bodies/web/invent_costos_head.bdy");

      $query = "SELECT * FROM articulos_rentas WHERE codigo='$codigo' ";
      if (!@$db_res = db_query($query, $conn)) {
        $mens = "<div class=\"error_f\">Error al consultar catálogo de costos de renta</div><br/>";
        $mens.= db_errormsg($conn);
        die($mens);
      } 
      $ren = db_fetch_array($db_res, 0, PGSQL_ASSOC);
      include("bodies/renta_costos.bdy");
    }
    else if ($boton == "series") {
      include("bodies/web/invent_series.bdy");
    }
  }

  if (empty($action) || ($action!="agrega" && $action!="muestra" && $action!="ver" )) {
    /* Quito marranada para forzar unset de $id_prov1 */
//     if ($prov1=="Todos")
//       unset($id_prov1);
    if ($PROGRAMA == "web") {
      if ($alm>0)
        $query = "SELECT ar.codigo FROM articulos ar, almacen_1 al WHERE al.codigo=ar.codigo AND al.id_alm=$alm ";
      else
        $query = "SELECT ar.codigo FROM articulos ar WHERE 0=0 ";
      $query.= isset($id_dept) && $id_dept>=0 ? sprintf("AND id_depto=%d ", $id_dept) : "";
      $query.= isset($id_prov1) && $id_prov1>=0? sprintf("AND id_prov1=%d ", $id_prov1) : "";

      if (isset($mode) && $mode=="baja_ex") {
        $query.= "AND al.cant<al.c_min ";
      }

      if ($SQL_TYPE=="postgres")
        $query.= !empty($search) ? sprintf("AND (ar.codigo~*'%s' OR ar.descripcion~*'%s') ", $search, $search) : "";
      else if($SQL_TYPE=="mysql")
        $query.= !empty($search) ? sprintf("AND (ar.codigo LIKE '%%%s%%' OR ar.descripcion LIKE '%%%s%%') ", $search, $search) : "";
    }
    else if($PROGRAMA == "video") {
      $query = "SELECT * FROM filme ";
    }

    if (!$resultado = db_query($query, $conn)) {
      die ("<div class=\"error_f\"Error al consultar datos de productos</div>\n");
    }
    $num_arts = db_num_rows($resultado);

//     if (!isset($id_dept))
//       $id_dept = count($nm_depto);
    /* Antes, para representar todos los deptos, $id_prov1 tomaba el id del máximo departamento + 1 */

//     if (!isset($id_prov1)) {
//       $id_prov1 = count($nick_prov);

//    }

    
    if (isset($alm) && $alm>0)
      printf("<div class=\"head_almacen\">%s</div>\n", nombre_almacen($conn, $alm));
    else
      echo "<div class=\"head_almacen\">Catálogo de productos</div>\n";

    include("forms/invent_clasify.bdy");
    if ($action=="add2cart") {
      if ($item_agregado!=$DB_ERROR)
        echo "<i>Artículo agregado al carrito</i><br/>\n";
      else
        echo "<div class=\"error_nf\">Error al intentar agregar el producto al carrito</div><br/>\n";
    }

    if ($PROGRAMA == "web") {
      if (isset($alm) && $alm>0) {
        $query = "SELECT DISTINCT a.codigo, al.*, al.pu*d.tipo_cambio as unitario, a.descripcion, a.id_prov1, ";
        $query.= "a.id_depto, a.prov_clave, al.c_min, al.c_max, a.tangible, a.granel, al.alquiler ";
        $query.= "FROM almacen_1 al, divisas d, articulos a WHERE al.divisa=d.id AND al.codigo=a.codigo AND al.id_alm=$alm ";
      }
      else {
        $query = "SELECT DISTINCT a.codigo, a.*, a.p_costo*d.tipo_cambio as pcosto ";
        $query.= "FROM articulos a,divisas d WHERE a.divisa=d.id ";
      }

      if (!empty($search) && $id_prov1<count($nick_prov))
        $query .= " AND ";
      $query.= isset($id_dept) && $id_dept>=0 ? "AND a.id_depto=$id_dept " : "";

      $query.= isset($id_prov1) && $id_prov1>=0 ? sprintf("AND a.id_prov1=%d ", $id_prov1) : "";

      if (isset($mode) && $mode=="baja_ex") {
        $query.= "AND al.cant<al.c_min ";
      }

      if (!empty($search) && ((isset($id_prov1) && $id_prov1>=0) || (isset($id_dept) && $id_dept>=0) || $mode=="baja_ex"))
        $query.= " AND ";
      if ($SQL_TYPE=="postgres")
        $query.= !empty($search) ? sprintf("(a.codigo~*'%s' OR a.descripcion~*'%s')", $search, $search) : "";
      else if($SQL_TYPE=="mysql")
        $query.= !empty($search) ? sprintf("(a.codigo LIKE '%%%s%%' OR a.descripcion LIKE '%%%s%%')", $search, $search) : "";
      $query.= " ORDER BY ";
      switch ($order_by) {
      case "id_dept" : 
        $query.= "a.id_depto ";
      break;
      case "pu" :
        $query.= "unitario ";
      break;
      case "p_costo" :
        $query.= "pcosto ";
      break;
      case "id_prov" :
        $query.= "id_prov1 ";
      break;
      case "codigo" :
        $query.= "a.codigo ";
        break;
      default:
        $query.= "a.$order_by ";
      }
      $query.= $order ? "ASC" : "DESC";
    }
    else if($PROGRAMA == "video") {
      $query = "SELECT flm.* FROM filme flm ";
      if ((isset($id_dept) && $id_dept>=0) || (isset($id_prov1) && $id_prov1>=0)
          || (!empty($search) && $id_prov>=0) || isset($mode))
        $query .= " AND ";
      $query.= $id_dept>=0 ? "flm.genero1=$id_dept " : "";

      $query.= " ORDER BY ";
       switch ($order_by) {
      case "id_dept" : 
        $query.= "flm.genero1 ";
      break;
      case "titulo" :
        $query.= "flm.titulo ";
      break;
      case "p_costo" :
        $query.= "pcosto ";
      break;
      default:
        $query.= "flm.$order_by ";
      }
      $query.= $order ? "ASC" : "DESC";

   }
    
    if (!$resultado = db_query($query, $conn)) {
      die("<div class=\"error_f\">No puedo consultar datos de artículos</div><br/>\n");
   }
    $total_renglones = db_num_rows($resultado);

    if ($SQL_TYPE=="mysql")
      $query.= " LIMIT $offset,$limit";
    else if($SQL_TYPE=="postgres")
      $query.= " LIMIT $limit OFFSET $offset";

    if (!$resultado = db_query($query, $conn)) {
      die("Error al consultar catálogo</div>\n");
    }

    if (isset($debug) && $debug>0) {
      echo "<i>$query</i><br/>\n";
    }

    if ($num_ren = db_num_rows($resultado)) {

      if ($mode == "express") {
        include("forms/invent_exp_main.bdy");
      }
      else {
        if ($PROGRAMA == "web") {
          include("bodies/web/invent_std_main.bdy");
        }
        else if ($PROGRAMA == "video") {
          include("bodies/video/filmes_std_main.bdy");
        }
      }

      echo "<br/>";
    }
    else {
      echo "<i><center>No hay art&iacute;culos que coincidan en la base de datos</i></center>\n";

    }
  

?>


<table border=0 width="100%">
<colgroup>
  <col width="50%"></col>
</colgroup>
<tr>
  <td>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="f_busqueda" method="post">    B&uacute;squeda rápida: <input type="text" size="40" name="search" />
    <input type="hidden" name="mode" value="<?php echo $mode ?>" />
    <?php if (isset($alm) && $alm>0) printf("<input type=\"hidden\" name=\"alm\" value=\"%d\" />", $alm); ?>
    </form>
  </td>

  <td align="center">
        Divisa
  </td>
  <td align="right">
    <table border=0>
    <tr>
      <td>

<?php
  if ($offset > 0) {
    printf("<a href=\"%s?offset=%d", $_SERVER['PHP_SELF'], $offset-$limit);
    echo "&order_by=$order_by&order=$order&mode=$mode&alm=$alm$href_dept$href_prov";
	if (!empty($search))
      printf("&search=%s", htmlentities(str_replace(" ", "%20", $search)));
	echo "\"><img src=\"imagenes/web/botones/anterior.png\" alt=\"Anterior\" border=0 /></a>";
  }
  else
    echo "<font color=\"#e0e0e0\">&lt;- </font>";

  echo "</td>\n      <td>";

  printf("<form ID=\"pagina\" action=\"%s\" method=\"post\">\n", $_SERVER['PHP_SELF']);
  echo "  <select name=\"offset\" onchange=\"submit()\">\n";
  for ($i=1; $i<=$total_renglones; $i+=$limit) {
    printf("    <option value=\"%d\"", $i-1);
    if ($offset == $i-1)
      echo " selected";
    printf(">%d</option>\n", (int)$i/$limit + 1);
  }
  echo "  </select>\n";
  echo "  <input type=\"hidden\" name=\"mode\" value=\"$mode\" />\n";
  echo "  <input type=\"hidden\" name=\"id_dept\" value=\"$id_dept\" />\n";
  echo "  <input type=\"hidden\" name=\"id_prov1\" value=\"$id_prov1\" />\n";
  echo "</form>\n";


  echo "</td>\n      <td>";


  if ($offset+$limit < $num_arts) {
    printf(" <a href=\"%s?offset=%d", $_SERVER['PHP_SELF'], $offset+$limit);
    echo "&order_by=$order_by&order=$order&mode=$mode&alm=$alm$href_dept$href_prov";
    if (!empty($search))
      printf("&search=%s", htmlentities(str_replace(" ", "%20", $search)));
	echo "\"><img src=\"imagenes/web/botones/siguiente.png\" alt=\"Siguiente\" border=0 /></a>";
  }
  else
    echo "<font color=\"#e0e0e0\">-&gt;</font>";
?>
      </td>
    </tr>
    </table>
  </td>
</tr>
</table>

<hr/>
<?
    }

    if ($PROGRAMA=="web") {
      include("bodies/invent_footer.bdy");
    }
    else
    {
      include("bodies/invent_video_footer.bdy");
    }
    db_close($conn);
?>



</body>
</html>
