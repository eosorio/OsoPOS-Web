<?  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Carro de Compras. Módulo de inventarios de OsoPOS Web.

        Copyright (C) 2003 Eduardo Israel Osorio Hernández

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
  include("include/pos-var.inc");
  include("include/pos.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }

  if (isset($osopos_carrito))
   if ($accion=="borrar") {
      $i=0;
      while (list ($nombre, $valor) = each ($osopos_carrito))
        setcookie(sprintf("osopos_carrito[%s]", $nombre), "", time() - 3600);
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Movimientos de inventario v. <? echo $MOVINV_VERSION ?></TITLE>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <style type="text/css">
    body { background: white; font-family: helvetica;
           background-image: url(imagenes/fondo.gif) }
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
  if (isset($osopos_carrito) && is_array($osopos_carrito)) {
    echo "<a href=\"$PHP_SELF?accion=borrar\">Borrar carrito</a><br><br>\n";
    echo "Contenido del carrito:<br>\n<br>\n";
    echo "<table border=0 width=600>\n";
    while (list ($nombre, $valor) = each ($osopos_carrito)) {
      echo "<tr>\n";
      printf("  <td>%s</td><td>%.2f</td><td>%s</td>\n", $nombre, $valor, articulo_descripcion($conn, $nombre));
      echo "</tr>\n";
    }
    echo "</table>\n";
  }
  else {
    echo "No hay artículos en el carro de compras<br>\n";
  }

  echo "<hr>\n";
  include("bodies/web/menu.bdy");
?>

