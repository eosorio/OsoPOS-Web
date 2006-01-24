<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Impresión de etiquetas. Módulo de etiquetas de OsoPOS Web.

        Copyright (C) 2003-2004 Eduardo Israel Osorio Hernández

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

  if (isset($_POST["accion"]))
    $accion = $_POST["accion"];


  if (isset($osopos_carrito))
    if ($accion=="escribir") {
      while (list ($nombre, $valor) = each ($osopos_carrito))
        setcookie(sprintf("osopos_carrito[%s]", $nombre), $item[$nombre]);
      reset($osopos_carrito);
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Impresión de etiquetas v. 0.02</TITLE>
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
  /* Aqui comienza el parche para evitar cookies en carrito de compras */
  $osopos_carrito = lee_carrito($conn);
  if ($osopos_carrito == $DB_ERROR)
    die("<div class=\"error_f\">No puedo consultar el carro virtual</div>\n");
  /* Aqui terminal el parche */

  include("bodies/menu/etiquetas.bdy");
  echo "<hr>\n";
  if (isset($osopos_carrito) && is_array($osopos_carrito)) {
    $descripcion = lista_campo($conn, array_keys($osopos_carrito), "descripcion", "articulos");
    if (isset($accion) && $accion=="cambiar") {
      printf("<form action=\"%s\" method=\"post\">\n", $_SERVER['PHP_SELF']);
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
    else if (isset($accion) && $accion=="imprimir") {
      $item_desc = substr(articulo_descripcion($conn, $item), 0, 50);

      $imp_buf = sprintf("\n");
      $imp_buf.= sprintf("FK\"osopos\"\n");
      $imp_buf.= sprintf("FS\"osopos\"\n");
      $imp_buf.= sprintf("V00,20,N,\"Codigo:\"\n");
      $imp_buf.= sprintf("V01,20,N,\"Descripcion:\"\n");
      $imp_buf.= sprintf("q305\n");
      $imp_buf.= sprintf("Q185,18+0\n");
      $imp_buf.= sprintf("S2\n");
      $imp_buf.= sprintf("D4\n");
      $imp_buf.= sprintf("ZT\n");
      $imp_buf.= sprintf("TTh:m\n");
      $imp_buf.= sprintf("TDy2.mn.dd\n");
      $imp_buf.= sprintf("B30,19,0,1,2,8,41,B,V00\n");
      $imp_buf.= sprintf("A5,100,0,1,1,1,N,V01\n");
      $imp_buf.= sprintf("FE\n");
      $imp_buf.= sprintf("\n");

      $imp_buf.= sprintf("FR\"osopos\"\n");
      $imp_buf.= sprintf("?\n");
      $imp_buf.= sprintf("$item\n");
      $imp_buf.= sprintf("$item_desc\n");
      $imp_buf.= sprintf("P%d\n", $osopos_carrito[$item]);
      $imp_buf.= sprintf("\n");

      $cola_etiquetas =  lee_config($conn, "COLA_ETIQUETAS");
      $cmd_impresion =  lee_config($conn, "CMD_IMPRESION");

      $linea = "$cmd_impresion -P $cola_etiquetas";
      $impresion = popen($linea, "w");
      if (!$impresion) {
        echo "<b>Error al ejecutar <i>$cmd_impresion $nm_archivo</i></b><br>\n";
      }
      else {
        /*igm */ //echo "<pre>$imp_buff</pre>";
        if ($osopos_carrito[$item] > 1)
          printf("<center><i>%d etiquetas impresas.</i></center>\n", $osopos_carrito[$item]);
        else
          echo "<center><i>1 etiqueta impresa.</i></center>\n";
        fputs($impresion, $imp_buf);
        pclose($impresion);
      }

    }
    else {
      if ($accion=="escribir")
        $lista = $item;
      else
        $lista = $osopos_carrito;
      echo "Contenido del carrito:<br>\n<br>\n";
      printf("<form action=\"%s\" method=\"post\">\n", $_SERVER['PHP_SELF'];
      echo "<table border=0 width=600>\n";
      while (list ($nombre, $valor) = each ($lista)) {
        echo "<tr>\n";
        echo "  <td>\n";
        echo "     <input type=\"radio\" name=\"item\" value=\"$nombre\">\n";
        echo "  </td>\n";
        printf("  <td>%s</td><td>%.2f</td><td>%s</td>\n", $nombre, $valor, $descripcion[$nombre]);
        echo "</tr>\n";
      }
      echo "</table>\n";
      echo "<input type=\"submit\" value=\"Imprimir etiquetas\">\n";
      echo "<input type=\"hidden\" name=\"accion\" value=\"imprimir\">\n";
      echo "</form>\n";
    }
  }
  else {
    echo "No hay artículos en el carro de compras<br>\n";
  }

  echo "<hr>\n";
  include("bodies/web/menu.bdy");
}
?>
