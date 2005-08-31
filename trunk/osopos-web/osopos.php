<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 OsoPOS Web 1.21. Sistema de punto de venta en Intranet
        Copyright (C) 1999-2003 Eduardo Israel Osorio Hernández
        iosorio@elpuntodeventa.com

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


  include("include/general_config.inc");
  include("include/pos-var.inc");

{
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }


}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS v. 1.21</title>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <style type="text/css">
    div.notify {font-style: italic; color: red}
   </style>

</head>

<body>
<center>
<h1>OsoPOS v. 1.21</h1>
<h3>Copyright (C) 1999-2005<br>
 E. Israel Osorio H.<br>
elpuntodeventa.com</h3>
</center>


<h4>Seleccione un modo de operación:</h4>


<ul>
<li><a href="submenu.php?modulo=caja">Caja</a><br>
<li><a href="factur_web.php">Facturación</a><br>
<li><a href="submenu.php?modulo=inventarios">Inventarios</a><br>
<li><a href="clientes.php">Clientes <small>(Versión de desarrollo)</small></a><br>
<li><a href="submenu.php?modulo=estadisticas">Estadísticas y consultas</a><br>
<li><a href="carro.php">Carrito de compras</a><br>
<li><a href="submenu.php?modulo=mantto">Mantenimiento del sistema</a><br>
<li><a href="gpl.php">Ver licencia de operación</a><br>
</ul>
<br>
<br>
<p><div style="font-size: 8pt">OsoPOS es gratuito y distribuido con la esperanza de que sea útil, pero
SIN GARANTIA ALGUNA. Consulte la <a href="gpl.php">licencia de uso</a> para más información</div></p>

</body>
</html>
