<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Almacen Web. Sub-M�dulo de inventarios de OsoPOS Web.

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
  include("include/pos-var.inc");
  include("include/general_config.inc");
  include("include/invent_config.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
    include("include/pos.inc");
  }

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Invent v. <? echo $INVENT_VERSION ?></TITLE>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
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
   </style>
 

</HEAD>
<body>
<? 
{
  if (!isset($almc) || $almc<1) {
    $query = "SELECT * FROM almacenes ";
    if (!$db_res = db_query($query, $conn)) {
      echo "Error en base de datos<br>\n";
      db_errormsg($db_res);
    }
    include("bodies/cat_almacen.bdy");
  }
  else {
  if (!isset($offset))
    $offset = 0;
  if (!isset($limit))
    $limit = 20;


    printf("<b>Almac�n %d %s</b><br>\n", $almc, $alm_desc);
    if (!isset($action) || strlen($action)<1) {
      echo "<ul>\n";
      echo "  <li><a href=\"$PHP_SELF?action=agregar&almc=$almc\">Anexar producto del cat�logo al almac�n</a></li>\n";
      echo "  <li><a href=\"$PHP_SELF?action=borrar&almc=$almc\">Quitar producto del almac�n</a></li>\n";
      echo "</ul>\n";
    }
    else if($action=="agregar") {

      $query = "SELECT * FROM (SELECT codigo AS codigo, descripcion AS descripcion, ";
      $query.= "id_depto AS id_depto, id_prov1 AS id_prov1 FROM articulos EXCEPT ";
      $query.= "(select al.codigo, ar.descripcion, ar.id_depto, ar.id_prov1 FROM ";
      $query.= "almacen_1 al, articulos ar WHERE al.codigo=ar.codigo AND al.id_alm=$almc)) AS c ";
      if (!empty($busqueda))
        $query.= "WHERE descripcion~*'$busqueda' ";
      //      $query.= "ORDER BY codigo ";
      /*igm*/ $query.= "ORDER BY id_depto, descripcion ";
      $query.= $order ? "ASC " : "DESC ";


      if (!$db_res = db_query($query, $conn)) {
        die ("<div class=\"error_f\">Error al consultar productos no incluidos en almacen $almc</div>");
      }
      $total_renglones = db_num_rows($db_res);

      /*      if ($SQL_TYPE=="mysql")
        $query.= sprintf(" LIMIT %d,%d ", $limit, $pagina*$limit);
      else if($SQL_TYPE=="postgres")
        $query.= sprintf(" LIMIT %d OFFSET %d ", $limit, $pagina*$limit);

      if (!$db_res = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      */
      if (isset($debug) && $debug>0) {
        echo "<i>$query</i><br>\n";
      }

      $query2 = "SELECT id,nombre FROM departamento ORDER BY id";
      if (!$db_res2 = db_query($query2, $conn)) {
        echo "<div class=\"error_nf\">Error al consultar nombres de departamentos</div><br>\n";
      }
      else {
        $nm_deptos = array();
        $num_ren = db_num_rows($db_res2);
        for ($k=0; $k < $num_ren; $k++) {
          $r = db_fetch_object($db_res2, $k);
          $nm_deptos[$r->id] = htmlentities($r->nombre);
        }
      }
      include("forms/prod_genericos.bdy");
    }
    else if($action=="insertar") {
      for($i=0; $i<count($codigo); $i++) {
        if (inserta_en_almacen($conn, $almc, $codigo[$i]) > 0)
          printf("<i>Producto <b>%s %s</b> incluido en almacen %d</i><br>\n",
                 $codigo[$i], articulo_descripcion($conn, $codigo[$i]), $almc);
      }
    }
    else if($action=="borrar") {
      echo "Para quitar un art�culo del almac�n, debe dirigirse al listado de productos del almac�n ";
      echo "en el <a href=\"invent_web.php\">m�dulo de inventarios</a>, y presionar sobre la imagen de eliminar.<br>\n";
    }
  }
  echo "<hr>\n";
  include("bodies/web/almacenes_pie.bdy");
  include("bodies/menu/general.bdy");
}
?>
</body>
</HTML>
