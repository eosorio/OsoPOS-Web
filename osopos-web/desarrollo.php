<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Beta test. Módulo de pruebas de OsoPOS Web.

        Copyright (C) 2004,2008 Eduardo Israel Osorio Hernández

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

  if ($action=="add2cart") {
    $item_agregado = agrega_carrito_item($conn, $codigo, $qt);
    //    setcookie("osopos_carrito[$codigo]", $qt);
  }

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
   <meta name="Author" content="E. Israel Osorio Hernández">
   <TITLE>OSOPoS Web - Módulo de desarrollo</TITLE>
   <?php include("menu/menu_principal.inc"); ?>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/extras.css">
   <style type="text/css">
   </style>
 

</HEAD>
<body>
<?php
  include("menu/menu_principal.bdy");
?>

<p>En esta sección encontrará funciones que se irán incorporando a OsoPOS
Web. Aún están en periodo de desarrollo o de pruebas y por
consiguiente son INESTABLES. Úselas bajo su propio riesgo</p>

<a href="compra.php">Órdenes de compras y recepciones de
proveedor</a><br>
<a href="ventas_web.php">Consulta de
ventas</a><br>
<a href="ventas_web.php?action=reimpresion">Reimpresión de tickets de
venta</a><br>
<a href="alta_express.php">Alta express de productos</a><br>
<a href="invent_web_estadistica.php">Estadísticas por artículo</a><br>
<a href="lista_precios.php">Lista de precios y existencias</a><br>
<a href="ultima_fecha_venta.php">Ultima fecha de ventas de productos</a><br>
<a href="articulos_propiedades.php">Propiedades de artículos</a><br>
<br>

<?php
    db_close($conn);
?>
</body>
</html>