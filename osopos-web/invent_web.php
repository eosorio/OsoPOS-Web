<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Invent Web. Módulo de inventarios de OsoPOS Web.

        Copyright (C) 2000 Eduardo Israel Osorio Hernández

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
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Invent v. <? echo $INVENT_VERSION ?></TITLE>
   <style type="text/css">
    body { background: white; font-family: helvetica }
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
    div.notify {font-style: italic; color: red}
   </style>
 

</HEAD>
<BODY BGCOLOR="white" BACKGROUND="imagenes/fondo.gif"
<?
  if ($action == "muestra") {
    echo "onload=\"document.articulo.descripcion.focus()\"";
  }
  else if ($action == "agrega")
    echo "onload=\"document.articulo.codigo.focus()\"";

?>>

<?

  if (!isset($offset))
    $offset = 0;
  if (!isset($limit))
    $limit = 10;
  if (!isset($order_by))
    $order_by = "descripcion";
  if (!isset($order))
     $order = 1; /* Ascendente */
  if(isset($depto)) {
    if ($depto!="Todos") {
      $query = "SELECT id AS id_dept FROM departamento WHERE nombre='$depto'";
      //      if (!$resultado = pg_exec($conn, $query)) {
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
  if (isset($prov)) {
    if ($prov == "Todos")
      unset($id_prov);
    else {
      $query = "SELECT id FROM proveedores WHERE nick='$prov'";
      //      if (!$resultado = pg_exec($conn, $query)) {
      if (!$resultado = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_prov = db_result($resultado, 0, "id");
    }
  }
  $href_prov = isset($id_prov) ? sprintf("&id_prov=%d", $id_prov) : "&prov=Todos";

  $query = "SELECT id,nick FROM proveedores ORDER BY id";
//  if (!$resultado = pg_exec($conn, $query)) {
  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $num_ren_prov = db_num_rows($resultado);

  $nick_prov = array();
  for ($i=0; $i<$num_ren_prov; $i++) {
    $reng = db_fetch_object($resultado, $i);
    $id = $reng->id;
    $nick_prov[$id] = $reng->nick;
  }

  $query = "SELECT id,nombre FROM departamento ORDER BY id";
//  if (!$resultado = pg_exec($conn, $query)) {
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
    //  if (!$resultado = pg_exec($conn, $query)) {
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al buscar departamento.<br>\n$query<br>\n";
      exit();
    }
    if (db_num_rows($resultado))
      $id_dept = db_result($resultado, 0, id);
    else
      $id_dept = 0;

    $query = "SELECT id FROM proveedores WHERE nick='$prov'";
    //  if (!$resultado = pg_exec($conn, $query)) {
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al consultar proveedores.<br>\n$query<br>\n";
      exit();
    }
    if (db_num_rows($resultado))
      $id_prov = db_result($resultado, 0, id);
    else
      $id_prov = 0;

    $query = "INSERT INTO articulos VALUES ('$codigo', ";
    if (strlen($descripcion))
      $query .= sprintf("'%s', ", str_replace("&quot;", "\"", $descripcion));
    else
      $query .= "'', ";
    $query.= sprintf("%f, %f, %d, %d, %d, ", $pu, $descuento, $ex, $ex_min, $ex_max);
    $query.= sprintf("%d, %d, %f, '%s', %d)", $id_prov, $id_dept, $p_costo, $prov_clave, $iva_porc);
    //  if (!$resultado = pg_exec($conn, $query)) {
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al insertar articulos.<br>\n$query<br>\n";
      exit();
    }
    else {
      printf("<b><center>Art&iacute;culo <i>%s %s</i> agregado.</center></b><br>\n",
             $codigo, stripslashes($descripcion));
      $action = "agrega";
      unset($codigo);
    }
  }

  if ($action == "muestra"  ||  $action == "agrega") {
    if ($action == "muestra") {
      $query = "SELECT * FROM articulos WHERE codigo='$codigo'";
      //  if (!$resultado = pg_exec($conn, $query)) {
      if (!$resultado = db_query($query, $conn)) {
        //        echo "Error al ejecutar $query<br>" . pg_errormessage($conn);
        echo "Error al ejecutar $query<br>" . db_error($conn);
        exit();
      }
      $reng = db_fetch_object($resultado, 0);
      $val_cod = "value=\"$codigo\"";
      $val_desc = sprintf("value=\"%s\"", htmlspecialchars($reng->descripcion));
      $val_pu = "value=" . $reng->pu;
      $val_p_costo = sprintf("value=%.2f", $reng->p_costo);
      $val_disc = "value=" . $reng->descuento;
      $val_ex = "value=" . $reng->cant;
      $val_min = "value=" . $reng->min;
      $val_max = "value=" . $reng->max;
      $val_iva_porc =  sprintf("value=\"%.2f\"", $reng->iva_porc);
      $val_prov_clave = sprintf("value=\"%s\"", $reng->prov_clave);
      $val_submit = "value=\"Cambiar datos\"";
      $form_action = "$PHP_SELF?order_by=$order_by&action=cambia&offset=$offset&order=$order&mode=$mode";
    }
    else if ($action == "agrega") {
      $val_submit = "value=\"Agregar producto\"";
      $form_action = "$PHP_SELF?order_by=$order_by&action=inserta&order=$order&mode=$mode";
    }

    include ("forms/invent_std_item.bdy");
    echo "<hr>\n";
  }
  else
  if ($action == "cambia") {
    $query = "SELECT id FROM departamento WHERE nombre='$depto'";
    //  if (!$resultado = pg_exec($conn, $query)) {
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al consultar departamentos.<br>\n$query<br>\n";
      exit();
    }
    if (db_num_rows($resultado))
      $id_dept = db_result($resultado, 0, id);
    else
      $id_dept = 0;

    $query = "SELECT id FROM proveedores WHERE nick='$prov'";
    if (!$resultado = db_query($query, $conn)) {
      //  if (!$resultado = pg_exec($conn, $query)) {
      echo "Error al consultar proveedores.<br>\n$query<br>\n";
      exit();
    }
    if (db_num_rows($resultado))
      $id_prov = db_result($resultado, 0, id);
    else
      $id_prov = 0;


    if ($mode == "express") {
      for ($i = 0; $i<$num_ren; $i++) {
        if ($modify[$i]) {
          $query = sprintf("UPDATE articulos SET descripcion='%s', pu=%f, ",
                           str_replace("&quot;", "\"", $desc[$i]), $pu[$i]);
          $query.= sprintf("cant=%f ", $qt[$i] - $item_minus[$i] + $item_add[$i]);
          $query.= sprintf(" WHERE codigo='%s'", $code[$i]);

          //  if (!$resultado = pg_exec($conn, $query))
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
      $query = sprintf("UPDATE articulos SET descripcion='%s', pu=%f, descuento=%f,",
                       str_replace("&quot;", "\"", $descripcion), $pu, $descuento);
      $query.= "cant=$ex, min=$ex_min, max=$ex_max, id_prov=$id_prov,";
      $query.= sprintf("id_depto=%d, p_costo=%.2f, prov_clave='%s', ", $id_dept, $p_costo, $prov_clave);
      $query.= sprintf("iva_porc=%.2f WHERE codigo='%s'", $iva_porc, $codigo);
    
      //  if (!$resultado = pg_exec($conn, $query)) {
      if (!$resultado = db_query($query, $conn)) {
        echo "Error al actualizar articulos.<br>\n$query<br>\n";
        exit();
      }
      else {
        printf("<b>Art&iacute;culo <i>%s %s</i> actualizado.</b><br>\n",
               $codigo, stripslashes($descripcion));
        $action = "";
      }
    }
  }
  if ($action == "borrar") {
    $query = "DELETE FROM articulos WHERE codigo='$codigo'";
    //  if (!$resultado = pg_exec($conn, $query)) {
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al actualizar articulos.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b>Art&iacute;culo <i>$codigo</i> eliminado.</b><br>\n";
    }
  }


/* Marranada para forzar unset de $id_prov */
  if ($prov=="Todos")
     unset($id_prov);
  $query = "SELECT * FROM articulos";
{
  if (isset($id_dept) || isset($id_prov) || !empty($search) || (isset($mode) && $mode=="baja_ex"))
    $query .= " WHERE ";
  $query.= isset($id_dept) ? sprintf("id_depto=%d", $id_dept) : "";

  if (isset($id_dept) && isset($id_prov))
    $query .= " AND ";
  $query.= isset($id_prov) ? sprintf("id_prov=%d", $id_prov) : "";

  if (isset($mode) && $mode=="baja_ex") {
    if (isset($id_prov) || isset($id_dept))
      $query.= " AND ";
    $query.= "cant<min";
  }

  if (!empty($search) && (isset($id_prov) || isset($id_dept) || $mode=="baja_ex"))
    $query.= " AND ";
  $query.= !empty($search) ? sprintf("(codigo~*'%s' OR descripcion~*'%s')", $search, $search) : "";

  //  if (!$resultado = pg_exec($conn, $query)) {
  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $num_arts = db_num_rows($resultado);
}  

  include("forms/invent_clasify.bdy");


  $query = "SELECT * FROM articulos";
  if (isset($id_dept) || isset($id_prov) || !empty($search) || (isset($mode) && $mode=="baja_ex"))
     $query .= " WHERE ";
  $query.= isset($id_dept) ? "id_depto=$id_dept " : "";

  if (isset($id_dept) && isset($id_prov))
     $query .= " AND ";
  $query.= isset($id_prov) ? sprintf("id_prov=%d", $id_prov) : "";

  if (isset($mode) && $mode=="baja_ex") {
    if (isset($id_prov) || isset($id_dept))
      $query.= " AND ";
    $query.= "cant<min";
  }

  if (!empty($search) && (isset($id_prov) || isset($id_dept) || isset($id_dept) || $mode=="baja_ex"))
    $query.= " AND ";
  $query.= !empty($search) ? sprintf("(codigo~*'%s' OR descripcion~*'%s')", $search, $search) : "";

  $query.= " ORDER BY ";
  switch ($order_by) {
    case "id_dept" : 
      $query.= "\"id_depto\" ";
      break;
    default:
      $query.= "\"$order_by\" ";
  }
  $query.= $order ? "ASC" : "DESC";
//  if (!$resultado = pg_exec($conn, $query)) {
  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $total_renglones = db_num_rows($resultado);

  if ($SQL_TYPE == "postgres") {
    $query.= " LIMIT $limit OFFSET $offset";
  //  if (!$resultado = pg_exec($conn, $query)) {
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al ejecutar $query<br>\n";
      exit();
    }
  }


  if ($num_ren = db_num_rows($resultado)) {

    if ($mode == "express") {
      include("forms/invent_exp_main.bdy");
    }
    else {
      include("bodies/invent_std_main.bdy");
    }
    echo "<br>";
  }
  else {
    echo "<i><center>No hay art&iacute;culos que coincidan en la base de datos</i></center>\n";
  }


?>

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
      printf("<a href=\"%s?offset=%d&order_by=%s&order=%d&action=%s&mode=%s%s%s",
             $PHP_SELF, $i-1, $order_by, $order, $action, $mode, $href_dept, $href_prov);
      if (!empty($search))
        printf("&search=%s", htmlentities(str_replace(" ", "%20", $search)));
      printf("\">%d</a>", $i);
    }
    else {
      printf("<font color=\"#e0e0e0\">%d%d</font>", $i, $fin_bloque);
    }
    echo "</small></td>\n";
  }
    if (($i-1)%($limit*10) != 0)
      echo "  <td>&nbsp;</td>\n";
  echo " </tr>\n";
?>
</table>
<?


  db_close($conn);
?>
<form action=<? echo $PHP_SELF ?> method=post>
Busqueda rápida: <input type=text size=40 name="search">
</form>
<hr>
<div align="right"><font face="helvetica,arial">
<a href=<? echo "\"$PHP_SELF?offset=$offset&action=agrega&mode=$mode$href_dept$href_prov\"" ?>>
Agregar producto</a> |
<a href="depto.php">Departamentos</a> |
<a href="proveedor.php">Proveedores</a> |
<a href="<? echo $PHP_SELF ?>?salir=1">Salir del sistema</a>
</font>
</div>


</BODY>
</HTML>
