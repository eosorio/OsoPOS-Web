<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Invent Web. M�dulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2003 Eduardo Israel Osorio Hern�ndez

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los t�rminos de la Licencia P�blica General GNU
publicada por la Free Software Foundation: ya sea en la versi�n 2 de la
Licencia, o (a su elecci�n) en una versi�n posterior. 

        Este programa es distribuido con la esperanza de que sea �til, pero
SIN GARANTIA ALGUNA; incluso sin la garant�a impl�cita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. V�ase la Licencia P�blica General
GNU para mayores detalles. 

        Deber�a usted haber recibido una copia de la Licencia P�blica General
GNU junto con este programa; de no ser as�, escriba a Free Software
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

  if ($action=="add2cart") {
    setcookie("osopos_carrito[$codigo]", $qt);
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Invent v. <? echo $INVENT_VERSION ?></TITLE>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/extras.css">
   <style type="text/css">
    td.bg1 { background: <? echo $bg_color1 ?> }
    td.bg1_center {text-align: center; background: <? echo $bg_color1 ?> }
    td.bg1_right {text-align: right; background: <? echo $bg_color1 ?>}
    td.bg2 { background: <? echo $bg_color2 ?> }
    td.bg2_center {text-align: center; background: <? echo $bg_color2 ?> }
    td.bg2_right {text-align: right; background: <? echo $bg_color2 ?> }
    td.bg0 { }
    td.bg0_center {text-align: center }
    td.bg0_right {text-align: right }
    td.right_red {text-align: right; font-color: red}
    td.item_modify {text-align: top }
    div.notify {font-style: italic; color: red}
    div.head_almacen { text-align: center; font-size: big; font-weight: bold }
   </style>
 

</HEAD>
<?php
   if (!puede_hacer($conn, $user->user, "invent_general")) {
     echo "<body>\n";
     echo "<h4>Usted no tiene permisos para accesar este m�dulo</h4><br>\n";
     echo "<a href=\"index.php\">Regresar a men� principal</a>\n";
     echo "</body>\n";
     exit();
   }
  echo "<body ";
  if ($action == "muestra") {
    echo "onload=\"document.articulo.descripcion.focus()\"";
  }
  else if ($action == "agrega")
    echo "onload=\"document.articulo.codigo.focus()\"";

  echo ">\n";

  if (!isset($offset))
    $offset = 0;
  if (!isset($limit))
    $limit = 10;
  if (!isset($order_by))
    if ($PROGRAMA == "web")
      $order_by = "descripcion";
    else if ($PROGRAMA == "video")
      $order_by = "titulo";
  if (!isset($order))
     $order = 1; /* Ascendente */
  if(isset($depto)) {
    if ($depto!="Todos") {
      if ($PROGRAMA == "web")
        $query = "SELECT id AS id_dept FROM departamento WHERE nombre='$depto'";
      else if($PROGRAMA == "video")
        $query = "SELECT id AS id_dept FROM genero WHERE nombre='$depto'";

      if (!$resultado = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_dept = db_result($resultado, 0, "id_dept");
    }
    else
      unset($id_dept);
  }
  $href_dept = isset($id_dept) ? sprintf("&id_dept=%d", $id_dept) : "&depto=Todos";
  $form_dept = isset($id_dept) ? sprintf("\"id_dept\" value=%d>\n", $id_dept) : "\"depto\" value=\"Todos\">\n";
  $form_dept = "<input type=\"hidden\" name=" . $form_dept;
  if (isset($prov1)) {
    if ($prov1== "Todos")
      unset($id_prov1);
    else {
      /*      $query = "SELECT id FROM proveedores WHERE nick='$prov1'";
      if ($debug>0)
        echo "<i>$query</i><br>\n";

      if (!$resultado = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_prov1 = db_result($resultado, 0, "id");
      */
      $id_prov1 = $prov1;

      /************************************ O J O **************************************/
      /* Anteriormente se recib�a en la vairable el nick del proveedor y se ten�a
         que consultar su id. Ahora se manda el id en la variable. Este cambio a�n no se ha
         probado, por lo que puede presentar problemas */
      /********************************************************************************/
    }
  }
  if (isset($prov2)) {
    if ($prov2== "Todos")
      unset($id_prov2);
    else {
      $query = "SELECT id FROM proveedores WHERE nick='$prov2'";

      if (!$resultado = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
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
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_prov3 = db_result($resultado, 0, "id");
    }
  }*/
  $href_prov = isset($id_prov1) ? sprintf("&id_prov1=%d", $id_prov1) : "&prov=Todos";
  $form_prov = isset($id_prov1) ? sprintf("\"id_prov1\" value=%d>\n", $id_prov1) : "\"prov\" value=\"Todos\">\n";
  $form_prov = "<input type=\"hidden\" name=" . $form_prov;
  $query = "SELECT id,nick FROM proveedores ORDER BY id";

  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $num_ren_prov = db_num_rows($resultado);

  $nick_prov = array();
  for ($i=0; $i<$num_ren_prov; $i++) {
    $reng = db_fetch_object($resultado, $i);
    $id = $reng->id;
    //    $nick_prov[$id - ($SQL_TYPE=="mysql")] = $reng->nick;
    $nick_prov[$id] = $reng->nick;
  }

  if ($PROGRAMA == "web")
    $query = "SELECT id,nombre FROM departamento ORDER BY id";
  else if ($PROGRAMA == "video")
    $query = "SELECT id,nombre FROM genero ORDER BY id";

  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }

  $num_ren_depto = db_num_rows($resultado);

  $nm_depto = array();
  for ($i=0; $i<$num_ren_depto; $i++) {
    $reng = db_fetch_object($resultado, $i);
    $id = $reng->id;
    $nm_depto[$id] = $reng->nombre;
  }


  if ($action == "inserta") {
    $query = "SELECT id FROM departamento WHERE nombre='$depto'";

    if ($debug>0)
      echo "<i>$query</i><br>\n";
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al buscar departamento.<br>\n$query<br>\n";
      exit();
    }
    if (db_num_rows($resultado))
      $id_dept = db_result($resultado, 0, id);
    else
      $id_dept = 0;

    if (empty($id_prov1)) {
      if (!empty($prov1)) {
        $query = "<i>SELECT id FROM proveedores WHERE nick='$prov1'</i><br>\n";

        if ($debug>0)
          echo "<i>$query</i>\n";
        if (!$resultado = db_query($query, $conn)) {
          echo "Error al consultar proveedores.<br>\n$query<br>\n";
          exit();
        }
        if (db_num_rows($resultado))
          $id_prov1 = db_result($resultado, 0, id);
        else
          $id_prov1 = 0;
      }
    }
    else
      $id_prov1 = 0;

    $query = "INSERT INTO articulos (codigo, descripcion, pu, descuento, cant, ";
    $query.= "min, max, id_prov1, id_depto, p_costo, prov_clave, iva_porc, divisa, ";
    $query.= "codigo2, pu2, pu3, pu4, pu5, tax_0, tax_1, tax_2, tax_3, tax_4, tax_5, ";
    $query.= "empaque, medida) ";
    $query.= "VALUES ('$codigo', ";
    if (strlen($descripcion))
      $query .= sprintf("'%s', ", str_replace("&quot;", "\"", $descripcion));
    else
      $query .= "'', ";
    $query.= sprintf("%f, %f, %d, %d, %d, ", $pu, $descuento, $ex, $ex_min, $ex_max);
    $query.= sprintf("%d, %d, %f, '%s', %d, ", $id_prov1, $id_dept, $p_costo, $prov_clave, $iva_porc);
    $query.= sprintf("'%3s', '%s', %f, %f, ", $divisa, $codigo2, $precio2, $precio3);
    $query.= sprintf("%f, %f", $precio4, $precio5);
    for ($j=0; $j<$MAXTAX; $j++)
      $query.= sprintf(", %f", $imp_porc[$j]);
    $query.= sprintf(", '%f', '%s'", $u_empaque, $u_medida);
    $query.= ")";
    if ($debug>0)
      echo "<i>$query</i><br>\n";
    $resultado1 = db_query($query, $conn);
    if (!$resultado1) {
      $db_msg1 = db_errormsg($conn);
    }

    $query2 = "INSERT INTO article_desc (code, descripcion, long_desc, img_location) VALUES ";
    $query2.= sprintf("('%s', '%s', '%s', '%s')", $codigo, $descripcion,
                      addslashes(strip_tags($long_desc)), $img_source_name);
    $resultado2 = db_query($query2, $conn);
    if (!$resultado2) {
      $db_msg2 = db_errormsg($conn);
    }

    if (!$resultado1) {
      echo "<b>Error al insertar articulos.</b><br>\n$query<br>\n";
      printf("<i>%s</i><br>\n", $db_msg1);
      exit();
    }
    if (!$resultado2) {
      echo "<b>Error al insertar descripci�n expandida del art�culo</b><br>\n$query2<br>\n";
      printf("<i>%s</i><br>\n", $db_msg2);
    }

    for ($i=0; $i<count($almac); $i++)
      if (inserta_en_almacen($conn, $almac[$i], $codigo) == 0)
        printf("<div class=\"men_res\">Articulo %s %s anexado en almacen %d</div>",
               $codigo, stripslashes($descripcion), $almac[$i]);

    if ($resultado1){
      printf("<b><center>Art&iacute;culo <i>%s %s</i> agregado.</center></b><br>\n",
             $codigo, stripslashes($descripcion));
      $action = "agrega";
      unset($codigo);
    }
  }

  if ($action == "cambia") {
    if (!puede_hacer($conn, $user->user, "invent_cambiar_item")) {
      echo "<h4>Usted no tiene permisos para modificar items</h4>\n";
    }
    else {
      if ($PROGRAMA == "web")
        $query = "SELECT id FROM departamento WHERE nombre='$depto'";
      else if ($PROGRAMA == "video")
        $query = "SELECT id FROM genero WHERE nombre='$depto'";
      if (!$resultado = db_query($query, $conn)) {
        echo "Error al consultar departamentos.<br>\n$query<br>\n";
        exit();
      }
      if (db_num_rows($resultado))
        $id_dept = db_result($resultado, 0, id);
      else
        $id_dept = 0;

      if ($mode == "express") {
        if ($alm>0)
          $tabla = sprintf("almacen_%d", $alm);
        else
          $tabla = "articulos";
        for ($i = 0; $i<$num_ren; $i++) {
          if ($modify[$i]) {
            if ($alm>0) {
              $query = sprintf("UPDATE almacen_%d SET pu=%f, pu2=%f  ",
                             $alm, $pu[$i], $pu2[$i]);
              if (puede_hacer($conn, $user->user, "inventario_fisico"))
                $query.= sprintf(", cant=%f ", $qt[$i] - $item_minus[$i] + $item_add[$i]);
            }
            else {
              $query = sprintf("UPDATE articulos SET p_costo=%f ", $p_costo[$i]);
            }
            $query.= sprintf(" WHERE codigo='%s'", $code[$i]);

            if (!$resultado = db_query($query, $conn)) 
              printf("Error al actualizar articulo %s, $s<br>\n", $code[$i], $desc[$i]);
            else {
              printf("<b>Art&iacute;culo <i>%s %s</i> actualizado.</b><br>\n",
                     $code[$i], stripslashes($desc[$i]));
              $action = "";
            }
          }
        }
      }
      else {
        if (isset($alm) && $alm>0) {
          $query = sprintf("UPDATE almacen_%d SET pu=%f, pu2=%f, pu3=%f, pu4=%f, pu5=%f, ",
                           $alm, $pu, $precio2, $precio3, $precio4, $precio5);
          $query.= sprintf("c_min=%f, c_max=%f, ",
                            $ex_min, $ex_max);
          $query.= sprintf("codigo2='%s', ", $codigo2);
          $query.= sprintf("divisa='%s', ", $divisa);
          $query.= sprintf("tax_0=%.2f, tax_1=%.2f, tax_2=%.2f, tax_3=%.2f, tax_4=%.2f, tax_5=%.2f ", 
                           $imp_porc[0], $imp_porc[1], $imp_porc[2], $imp_porc[3], $imp_porc[4], $imp_porc[5]); 
          $query.= sprintf("WHERE codigo='%s'", $codigo);
        }
        else {
          $query = sprintf("UPDATE articulos SET descripcion='%s', pu=%f, ",
                           $descripcion, $pu, $precio2, $precio3, $precio4, $precio5);
          $query.= sprintf("descuento=%f, id_prov1=%d, id_prov2=%d, id_depto=%d, ",
                           $descuento, $id_prov1, $id_prov2, $id_dept);
          $query.= sprintf("p_costo=%.2f, prov_clave='%s', codigo2='%s', ", $p_costo, $prov_clave, $codigo2);
          $query.= sprintf("iva_porc=%.2f, divisa='%s', ", $iva_porc, $divisa);
          $query.= sprintf("tax_0=%.2f, tax_1=%.2f, tax_2=%.2f, tax_3=%.2f, tax_4=%.2f, tax_5=%.2f, ", 
                           $imp_porc[0], $imp_porc[1], $imp_porc[2], $imp_porc[3], $imp_porc[4], $imp_porc[5]);
          $query.= sprintf("medida='%s', empaque=%f ", $u_medida, $u_empaque);
          $query.= sprintf("WHERE codigo='%s'", $codigo);
        }
        if (isset($debug) && $debug>0)
          echo "<i>$query</i><br>\n";
        $resultado = db_query($query, $conn);

        $query2 = sprintf("UPDATE article_desc SET long_desc='%s' ",
                          addslashes(strip_tags($long_desc)));
        if (!empty($img_source_name))
          $query2.= sprintf(", img_location='%s' ", $img_source_name);
        $query2.= sprintf("WHERE code='%s'", $codigo);

        if (isset($debug) && $debug>0)
          echo "<i>$query2</i><br>\n";
        $resultado2 = db_query($query2, $conn);
        if ($resultado && $resultado2) {

          printf("<b>Art&iacute;culo <i>%s %s</i> actualizado.</b><br>\n",
                 $codigo, stripslashes($descripcion));
          $action = "";
        }
        else {
          echo "<b>Error al actualizar articulos.</b><br>\n$query<br>\n$query2<br>\n";
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

  if (($action == "muestra"  ||  $action == "agrega")) {

    if ($action == "muestra") {
      if ($PROGRAMA == "web") {
        if (isset($alm) && $alm>0) {
          $query = "SELECT alm.*, art.descripcion, art.prov_clave, art.id_prov1, art.id_prov2, art.id_depto, ";
          $query.= "art.iva_porc, art.p_costo FROM almacen_$alm alm, articulos art ";
          $query.= "WHERE alm.codigo='$codigo' AND art.codigo='$codigo'";
        }
        else {
          $query = "SELECT descripcion, prov_clave, id_prov1, id_prov2, id_depto, iva_porc, ";
          $query.= "divisa, empaque, medida, codigo2, pu, p_costo ";
          $query.= "FROM articulos ";
          $query.= "WHERE codigo='$codigo' ";
        }

        if (!$resultado = db_query($query, $conn)) {
          echo "Error al ejecutar $query<br>" . db_errormsg($conn);
          exit();
        }
        if (isset($debug) && $debug>0)
          echo "<i>$query</i><br>\n";

        $reng = db_fetch_object($resultado, 0);
        $val_cod = "value=\"$codigo\"";
        $val_cod2 = sprintf("value=\"%s\"", $reng->codigo2);
        $val_desc = sprintf("value=\"%s\"", htmlspecialchars($reng->descripcion));
        $val_pu = "value=" . $reng->pu;
        $val_pu2 = "value=" . $reng->pu2;
        $val_pu3 = "value=" . $reng->pu3;
        $val_pu4 = "value=" . $reng->pu4;
        $val_pu5 = "value=" . $reng->pu5;
        $val_p_costo = sprintf("value=%.2f", $reng->p_costo);
        $val_disc = "value=" . $reng->descuento;
        $val_u_empaque = sprintf("value=\"%f\"", $reng->empaque);
        $val_u_medida = sprintf("value=\"%s\"", $reng->medida);
        $val_ex = "value=" . $reng->cant;
        $val_min = "value=" . $reng->c_min;
        $val_max = "value=" . $reng->c_max;
        $val_iva_porc =  sprintf("value=\"%.2f\"", $reng->iva_porc);
        $val_prov_clave = sprintf("value=\"%s\"", $reng->prov_clave);
        $val_divisa = $reng->divisa;
        $val_imp_porc[0] = sprintf("value=\"%.2f\"", $reng->tax_0);
        $val_imp_porc[1] = sprintf("value=\"%.2f\"", $reng->tax_1);
        $val_imp_porc[2] = sprintf("value=\"%.2f\"", $reng->tax_2);
        $val_imp_porc[3] = sprintf("value=\"%.2f\"", $reng->tax_3);
        $val_imp_porc[4] = sprintf("value=\"%.2f\"", $reng->tax_4);
        $val_imp_porc[5] = sprintf("value=\"%.2f\"", $reng->tax_5);
        $val_submit = "value=\"Cambiar datos\"";

        $query = "SELECT img_location,long_desc FROM article_desc WHERE code='$codigo'";
        if (!$resultado = db_query($query, $conn)) {
          echo "Error al ejecutar $query<br>" . db_error($conn);
          exit();
        }
        $reng2 = db_fetch_object($resultado, 0);
        $long_desc = $reng2->long_desc;
        $img_location = $reng2->img_location;
      }
      else if ($PROGRAMA == "video") {
        if (isset($alm) && $alm>0) {
          /* Aqui va el query para cat�logo general */
          $query = "SELECT flm.* FROM filme flm WHERE codigo='$codigo' ORDER BY titulo ASC";
        }
        else {
          /*Aqui el c�digo para la sucursal */
          $query = "SELECT flm.* FROM filme flm WHERE codigo='$codigo' ORDER BY titulo ASC";
        }

        if (isset($debug) && $debug>0)
          echo "<i>$query</i><br>\n";
        if (!$db_res = db_query($query, $conn)) {
          echo "Error al ejecutar $query<br>" . db_errormsg($conn);
          exit();
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
        $val_imagen = sprintf("<img src=\"imagenes/video/fichas/%s\">", $ren->nm_imagen);
        $col_w1 = 250;
      }
    }
    if ($action == "agrega") {
      $alm = 0;
      $val_submit = "value=\"Agregar producto\"";
      $val_divisa = $DIVISA_OMISION;
      $val_tit_orig = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=60>\n",
                              "tit_orig", $ren->tit_orig);
      $val_product = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "product", $ren->product);
      $val_anio = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "anio", $ren->anio);
      $val_elenco = sprintf("<input type=\"text\" name=\"%s\">\n", "elenco");
      $val_codigo = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "codigo", $ren->codigo);
      $val_director = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "director", $ren->director);
      $val_genero1 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "genero1", $ren->genero1);
      $val_genero2 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "genero2", $ren->genero2);
      $val_clasif = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "clasif", $ren->clasif);
      $val_duracion = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "duracion", $ren->duracion);
      $val_pais = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=10>\n",
                          "pais", $ren->pais);
      $val_dvd_region1 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=1>\n",
                                 "dvd_region1", $ren->dvd_region1);
      $val_dvd_region2 = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" size=1>\n",
                                 "dvd_region2", $ren->dvd_region2);
      $val_idioma = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "idioma", $ren->idioma);
      $val_subtit = sprintf("<input type=\"text\" name=\"%s\" value=\"%s\">\n", "subtit", $ren->subtit);
      $val_resenia = sprintf("<textarea name=\"%s\" cols=80 rows=8>%s</textarea>\n", "resenia", $ren->resenia);
      $val_imagen = "Ubicaci&oacute;n de la imagen: <input type=\"file\" name=\"img_source\" size=60 value=\"$PWD_DIR/$IMG_DIR/$img_location\">\n";
      $col_w1 = 100;
    }

    // IGM    if ($PROGRAMA == "web") {
    if ($PROGRAMA == "web")
      if (!isset($modulo))
        include ("forms/web/invent_std_item.bdy");
    /*     else if ($modulo == "test")
        include ("bodies/web/invent_pventa.bdy"); */
    else if ($PROGRAMA == "video") {
      include ("forms/video/filme_datos.bdy");
    }
    
    echo "<hr>\n";
  }

  if ($action == "borrar") {
    if (!puede_hacer($conn, $user->user, "invent_borrar_item")) {
      echo "<h4>Usted no cuenta con permisos para borrar art&iacute;culos</h4>\n";
    }
    else {
      $query = "SELECT max(id) FROM almacenes ";
      $resultado = db_query($query, $conn);
      if ((!$resultado) || (!$max_alm = db_result($resultado, 0, 0))) {
          echo "<div class=\"error_ft\">Inconsistencia en cat�logo de almacenes. ";
          echo "Consulte con su administrador del sistema</div><br>\n";
          echo "</body>\n";
          exit();
      }

      if (isset($alm) && $alm>0) {
        $query = "DELETE FROM almacen_$alm WHERE codigo='$codigo'";
        if (!$resultado = db_query($query, $conn))
          echo "<div class=\"error_nf\">Error al eliminar producto del almacen $alm.</div><br>\n";
        else
          echo "Producto eliminado del almacen $alm.<br>\n";
      }
      else {
        $existe_alm = 0;
        for ($i=1; $i<=$max_alm && !$existe_alm; $i++) {
          $existe_alm = busca_codigo($conn, $codigo, $i);
          if ($existe_alm > 0)
            echo "<div class=\"advt\">El producto existe en almacen $i, se cancela su eliminaci�n</div><br>\n";
          else if ($existe_alm < 0)
            echo "<div class=\"error_ft\">Error al consultar producto en almacen $i. Consulte con su administrador del sistema</div><br>\n";
        }
        if (!$existe_alm) {
          $query = "DELETE FROM article_desc WHERE code='$codigo'";
          if (!$resultado = db_query($query, $conn))
            echo "<div class=\"error_nf\">Error al eliminar descripci�n ampliada del producto.</div><br>\n";

          $query = "DELETE FROM articulos WHERE codigo='$codigo'";
          if (!$resultado = db_query($query, $conn)) {
            echo "<div class=\"error_nf\">Error al eliminar articulo del cat�logo general.</div><br>\n";
            exit();
          }
          else
            echo "<b>Art&iacute;culo <i>$codigo</i> eliminado.</b><br>\n";
        }
      }

    }
  }

  if ($action == "ver" && strlen($codigo)) {
    if (!isset($boton) || $boton=="general") {
      include ("bodies/web/invent_item_gral.bdy");
    }
    else {
      include("bodies/web/invent_pventa.bdy");
    }
  }

  if (empty($action) || ($action!="agrega" && $action!="muestra" && $action!="ver" )) {
    /* Marranada para forzar unset de $id_prov1 */
    if ($prov1=="Todos")
      unset($id_prov1);
    if ($PROGRAMA == "web") {
      if ($alm>0)
        $query = "SELECT ar.codigo FROM articulos ar, almacen_$alm al WHERE al.codigo=ar.codigo ";
      else
        $query = "SELECT ar.codigo FROM articulos ar WHERE 0=0 ";
      $query.= isset($id_dept) ? sprintf("AND id_depto=%d ", $id_dept) : "";
      $query.= isset($id_prov1) ? sprintf("AND id_prov1=%d ", $id_prov1) : "";

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
      echo "Error al ejecutar $query<br>\n";
      exit();
    }
    $num_arts = db_num_rows($resultado);



    if (!isset($id_dept))
      $id_dept = count($nm_depto);

    if (!isset($id_prov1)) {
      $id_prov1 = count($nick_prov);
    }

    
    if (isset($alm) && $alm>0)
      echo "<div class=\"head_almacen\">Almacen $alm</div>\n";
    else
      echo "<div class=\"head_almacen\">Cat�logo de productos</div>\n";

    include("forms/invent_clasify.bdy");

    if ($PROGRAMA == "web") {
      if (isset($alm) && $alm>0) {
        /**********  OJO, REVISAR EXISTENCIA MINIMA, DEBE SER DE ALMACEN ************/
        $query = "SELECT DISTINCT al.*, al.pu*d.tipo_cambio as unitario, a.descripcion, a.id_prov1, ";
        $query.= "a.id_depto, a.prov_clave, al.c_min, al.c_max ";
        $query.= "FROM almacen_$alm al, divisas d, articulos a WHERE al.divisa=d.id AND al.codigo=a.codigo ";
      }
      else {
        $query = "SELECT DISTINCT a.*, a.pu*d.tipo_cambio as unitario, ";
        $query.= "a.p_costo*d.tipo_cambio as pcosto FROM articulos a,divisas d WHERE a.divisa=d.id ";
      }

      if (!empty($search) && $id_prov1<count($nick_prov))
        $query .= " AND ";
      $query.= isset($id_dept) ? "AND a.id_depto=$id_dept " : "";

      $query.= isset($id_prov1) && $id_prov1<count($nick_prov) ? sprintf("AND a.id_prov1=%d ", $id_prov1) : "";

      if (isset($mode) && $mode=="baja_ex") {
        $query.= "AND al.cant<al.c_min ";
      }

      if (!empty($search) && (isset($id_prov1) || isset($id_dept) || $mode=="baja_ex"))
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
      default:
        $query.= "a.$order_by ";
      }
      $query.= $order ? "ASC" : "DESC";
    }
    else if($PROGRAMA == "video") {
      $query = "SELECT flm.* FROM filme flm ";
       if (isset($id_dept) || (isset($id_prov1) && $id_prov1<count($nick_prov))
          || (!empty($search) && $id_prov1<count($nick_prov)) || isset($mode))
        $query .= " AND ";
      $query.= isset($id_dept) ? "flm.genero1=$id_dept " : "";

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
      echo "Error al ejecutar $query<br>\n";
      exit();
    }
    $total_renglones = db_num_rows($resultado);

    if ($SQL_TYPE=="mysql")
      $query.= " LIMIT $offset,$limit";
    else if($SQL_TYPE=="postgres")
      $query.= " LIMIT $limit OFFSET $offset";

    if (!$resultado = db_query($query, $conn)) {
      echo "Error al ejecutar $query<br>\n";
      exit();
    }

    if (isset($debug) && $debug>0) {
      echo "<i>$query</i><br>\n";
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

      echo "<br>";
    }
    else {
      echo "<i><center>No hay art&iacute;culos que coincidan en la base de datos</i></center>\n";

    }
  

?>
<form action=<? echo $_SERVER['PHP_SELF'] ?> method=post>
Busqueda r�pida: <input type="text" size=40 name="search">
<input type="hidden" name="mode" value="<? echo $mode ?>">
<? if (isset($alm) && $alm>0) printf("<input type=\"hidden\" name=\"alm\" value=%d>", $alm); ?>
</form>

<table border=0 width="100%">
<?
  for ($i=1; $i<=$total_renglones; $i+=$limit) {
    if (($i-1)%($limit*10) == 0) {
      if ($i>1)
        echo " </tr>\n";
      echo " <tr>\n";
    }
    echo "  <td align=\"center\"><small>";
    if ($i-1 != $offset) {
      $fin_bloque = $i+$limit!=$total_renglones ? $i+$limit-1 : $total_renglones;
      printf("<a href=\"%s?offset=%d&order_by=%s&order=%d&action=%s&mode=%s&alm=%s%s%s",
             $_SERVER['PHP_SELF'], $i-1, $order_by, $order, $action, $mode, $alm, $href_dept, $href_prov);
      if (!empty($search))
        printf("&search=%s", htmlentities(str_replace(" ", "%20", $search)));
      printf("\">%d</a>", $i);
    }
    else {
      printf("<font color=\"#e0e0e0\">%d</font>", $i);
    }
    echo "</small></td>\n";
  }
    if (($i-1)%($limit*10) != 0)
      echo "  <td>&nbsp;</td>\n";
  echo " </tr>\n";
?>
</table>
<hr>
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
    include("bodies/menu/general.bdy");
?>



</BODY>
</HTML>
