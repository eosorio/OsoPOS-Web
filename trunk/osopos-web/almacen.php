<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Almacen Web. Sub-Módulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2003 Eduardo Israel Osorio Hernández

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
    $limit = 3000;


    printf("<b>Almacén %d %s</b><br>\n", $almc, $alm_desc);
    if (!isset($action) || strlen($action)<1) {
      echo "<ul>\n";
      echo "  <li><a href=\"$PHP_SELF?action=agregar&almc=$almc\">Anexar producto del catálogo al almacén</a></li>\n";
      echo "  <li><a href=\"$PHP_SELF?action=borrar&almc=$almc\">Quitar producto del almacén</a></li>\n";
      echo "</ul>\n";
    }
    else if($action=="agregar") {
      $query = "SELECT codigo,descripcion,pu,id_depto,id_prov1 FROM articulos EXCEPT ";
      $query.= "(select al.codigo, ar.descripcion, ar.pu, ar.id_depto, ar.id_prov1 FROM ";
      $query.= "almacen_$almc al, articulos ar WHERE al.codigo=ar.codigo) order by codigo ";
      $query.= $order ? "ASC" : "DESC";

      if (!$db_res = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $total_renglones = db_num_rows($db_res);

      if ($SQL_TYPE=="mysql")
        $query.= " LIMIT $offset,$limit ";
      else if($SQL_TYPE=="postgres")
        $query.= " LIMIT $limit OFFSET $offset ";

      if (!$db_res = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }

      if (isset($debug) && $debug>0) {
        echo "<i>$query</i><br>\n";
      }

      $num_ren = db_num_rows($db_res);
      include("forms/prod_genericos.bdy");
    }
    else if($action=="insertar") {
      for($i=0; $i<count($codigo); $i++)
        inserta_en_almacen($conn, $almc, $codigo[$i]);
    }
    else if($action=="borrar") {
      echo "Para quitar un artículo del almacén, debe dirigirse al listado de productos del almacén ";
      echo "en el <a href=\"invent_web.php\">módulo de inventarios</a>, y presionar sobre la imagen de eliminar.<br>\n";
    }
  }
  echo "<hr>\n";
  include("bodies/web/almacenes_pie.bdy");
  include("bodies/menu/general.bdy");
}
?>
</body>
</HTML>
