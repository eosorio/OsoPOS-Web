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
      while (list ($nombre, $valor) = each ($osopos_carrito))
        setcookie(sprintf("osopos_carrito[%s]", $nombre), "", time() - 3600);
    }
    else if ($accion=="borra_item")
      setcookie(sprintf("osopos_carrito[%s]", $item), "", time() - 3600);
    else if ($accion=="escribir") {
      while (list ($nombre, $valor) = each ($osopos_carrito))
        setcookie(sprintf("osopos_carrito[%s]", $nombre), $item[$nombre]);
      reset($osopos_carrito);
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Carrito de compras v. 0.02</TITLE>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <style type="text/css">
    div.notify {font-style: italic; color: red}
    div.head_almacen { text-align: center; font-size: big; font-weight: bold }
   </style>
 

</HEAD>
<body>

<?php
{
  include("bodies/menu/carrito.bdy");
  echo "<hr>\n";
  if (isset($osopos_carrito) && is_array($osopos_carrito)) {
  $descripcion = lista_campo($conn, array_keys($osopos_carrito), "descripcion", "articulos");
    if (isset($accion) && $accion=="cambiar") {
      echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
      echo "<table border=0 width=650>\n";
      while (list ($nombre, $valor) = each ($osopos_carrito)) {
        echo "<tr>\n";
        printf("  <td>%s</td>\n",  $nombre);
        printf("  <td><input type=\"text\" name=\"item[%s]\" size=4 value=\"%.2f\"></td>\n", $nombre, $valor);
        printf("  <td>%s</td>\n", $descripcion[$nombre]);
        echo "</tr>\n";
      }
      echo "</table>\n";
      echo "<input type=\"submit\" value=\"Aceptar\">\n";
      echo "<input type=\"hidden\" name=\"accion\" value=\"escribir\">\n";
      echo "</form>\n";
    }
    else if (isset($accion) && $accion=="borra_item") {
      printf("Articulo %s, %s eliminado del carrito<br>\n", $item, $descripcion[$item]);
    }
    else {
      if ($accion=="escribir")
        $lista = $item;
      else
        $lista = $osopos_carrito;
      echo "Contenido del carrito:<br>\n<br>\n";
      echo "<table border=0 width=600>\n";
      while (list ($nombre, $valor) = each ($lista)) {
        echo "<tr>\n";
        echo "  <td><form action=\"$PHP_SELF\" method=\"post\">\n";
        echo "     <input type=\"image\" src=\"imagenes/borrar.png\"><input type=\"hidden\" name=\"accion\" value=\"borra_item\">\n";
        echo "     <input type=\"hidden\" name=\"item\" value=\"$nombre\">\n";
        echo "  </td>\n";
        printf("  <td>%s</td><td>%.2f</td><td>%s</td>\n", $nombre, $valor, $descripcion[$nombre]);
        echo "</tr>\n";
      }
      echo "</table>\n";
    }
  }
  else {
    echo "No hay artículos en el carro de compras<br>\n";
  }

  echo "<hr>\n";
  include("bodies/web/menu.bdy");
}
?>

