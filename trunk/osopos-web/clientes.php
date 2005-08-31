<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Clientes. Módulo de clientes de OsoPOS Web.

        Copyright (C) 2000-2004 Eduardo Israel Osorio Hernández

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

  if (isset($_COOKIE["alm"]))
    $alm = $_COOKIE["alm"];
  else
    $alm = $ALM_DEF;

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

  if (isset($_POST["order_by"]))
    $order_by = $_POST["order_by"];
  else if (isset($_GET["order_by"]))
    $order_by = $_GET["order_by"];

  if (isset($_POST["order"]))
    $order = $_POST["order"];
  else if (isset($_GET["order"]))
    $order = $_GET["order"];

  /* Fin de variables globales */

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
   <TITLE>OSOPoS Web - Clientes</TITLE>
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
<body>
<?php
{

  if (isset($_GET['accion']))
    $accion = $_GET['accion'];
  else if (isset($_POST['accion']))
    $accion = $_POST['accion'];
  else
    $accion = "ver";

  include("bodies/menu/general.bdy");
  include("bodies/menu/clientes.bdy");
  echo "<hr>\n";
  if ($accion=="agregar") {

    $nombres = $_POST['nombres'];
    $ap_paterno = $_POST['ap_paterno'];
    $ap_materno = $_POST['ap_materno'];
    $nombre_comer = $_POST['nombre_comercial'];


    $query = "INSERT INTO clientes (nombres, ap_paterno, ap_materno, tipo_cliente, ";
    $query.= "sexo, nombre_comer, dom_principal, email, url, telefono1, telefono2, ";
    $query.= "fax, contacto, observaciones, rfc) VALUES ( ";
    $query.= sprintf("'%s', '%s', '%s', %d, ",
                     $nombres, $ap_paterno, $ap_materno, $_POST['tipo_cliente']);
    $query.= sprintf("'%s', '%s', %d, '%s', '%s', '%s', '%s', ",
		     $_POST['sexo'], $nombre_comer, $_POST['dom_principal'], $_POST['email'], $_POST['url'],
		     $_POST['telefono1'], $_POST['telefono2']);
    $query.= sprintf("'%s', '%s', '%s', '%s') ", $_POST['fax'], $_POST['contacto'], $_POST['observaciones'], $_POST['rfc']);

    if (!$resultado = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al insertar datos de cliente</div>\n";
      die($mens);
    }
    
    $query = "SELECT id FROM clientes WHERE nombres='$nombres' AND ap_paterno='$ap_paterno' ";
    $query.= "AND ap_materno='$ap_materno' AND nombre_comer='$nombre_comer' ";

    if (!$resultado = db_query($query, $conn)) {
      $mens = "<div class=\"error_nf\">Error al consultar datos de cliente</div>\n";
    }
    else {
      $id_cliente = db_result($resultado, 0, "id");
      $dom_nombre = $_POST['dom_nombre'];

      $query = "INSERT INTO domicilios (id_cliente, dom_nombre, dom_calle, dom_numero, dom_inter, ";
      $query.= "dom_col, dom_mpo, dom_ciudad, dom_edo_id, dom_cp, ";
      $query.= "dom_pais_id, dom_telefono) VALUES (";
      $query.= sprintf("%d, '%s', '%s', '%s', '%s', ",
                       $id_cliente, $dom_nombre, $_POST['dom_calle'], $_POST['dom_numero'], $_POST['dom_inter']);
      $query.= sprintf("'%s', '%s', '%s', %d, %d, ", 
                       $_POST['dom_col'], $_POST['dom_mpo'], $_POST['dom_ciudad'], $_POST['dom_edo_id'], $_POST['dom_cp']);
      $query.= sprintf("%d, '%s')", $_POST['dom_pais_id'], $_POST['dom_telefono']);

      if (!$resultado = db_query($query, $conn)) {
        $mens = "<div class=\"error_nf\">Error al insertar domicilio del cliente $id_cliente</div>\n";
      }
      else {
        $query = "SELECT id FROM domicilios WHERE id_cliente=$id_cliente AND dom_nombre='$dom_nombre' ";

        if (!$resultado = db_query($query, $conn)) {
          $mens = "<div class=\"error_nf\">Error al consultar domicilio del cliente $id_cliente</div>\n";
        }
        else {
          $id_domicilios = db_result($resultado, 0, "id");

          $query = "UPDATE clientes SET dom_principal=$id_domicilios WHERE id=$id_cliente ";


          if (!$resultado = db_query($query, $conn)) {
            $mens = "<div class=\"error_nf\">Error al resgitrar domicilio principal del cliente</div>\n";
          }
 
        } /* fin de consulta de id domicilio principal */
      }  /* fin de inserción de domicilio de cliente */
    }
    $accion = "ver";
  } /* Fin de accion == agregar */

  if ($accion == "ver") {
    if (empty($order_by))
      $order_by = "id";
    if (empty($order))
      $order = 0;
    /*igm*/ $limit = 100;

    $query = "SELECT id, nombre_comer, telefono1, email, contacto, tipo_cliente ";
    $query.= sprintf("FROM clientes ORDER BY %s ", $order_by);
    if ($order==1)
      $query.= "DESC ";
    else
      $query.= "ASC ";


    if (!$db_res = db_query($query, $conn)) {
      echo "<div class=\"error_f\">Error al consultar clientes</div><br>\n";
      exit();
    }

    $num_r = db_num_rows($db_res);
    include("bodies/clientes_principal.bdy");
  }
  else if($accion == "nuevo") {
    include("forms/clientes_alta.bdy");
  }
  $db_close($conn);
}
?>
</body>
</html>