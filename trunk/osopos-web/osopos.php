<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 OsoPOS Web 1.18. Sistema de punto de venta en Intranet
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
   <title>OsoPOS v. <? echo $osopos_web_vers ?></title>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <style type="text/css">
    div.notify {font-style: italic; color: red}
   </style>

</head>

<body background="imagenes/fondo.gif">
<center>
<h1>OsoPOS v. <? echo $osopos_web_vers ?></h1>
<h3>Copyright (C) 1999-2003<br>
 E. Israel Osorio H.</h3>
</center>


<h4>Seleccione un modo de operación:</h4>


<ul>
<li><a href="caja_web.php">Módulo de caja en modo normal</a><br>
<li><a href="caja_web.php?mode=express">Módulo de caja en modo express</a><br>
<li><a href="factur_web.php">Módulo de facturación</a><br>
<li><a href="invent_web.php">Control de inventarios</a><br>
<li><a href="mov_invent.php">Movimientos al inventario</a><br>
<li><a href="carro.php">Carrito de compras</a><br>
<li><a href="almacen.php">Administración de almacenes</a><br>
<li><a href="password.php">Administración de usuarios</a><br>
<li><a href="gpl.php">Ver licencia de operación</a><br>
</ul>
<br>
<br>
<p><div style="font-size: 8pt">OsoPOS es gratuito y distribuido con la esperanza de que sea útil, pero
SIN GARANTIA ALGUNA. Consulte la <a href="gpl.php">licencia de uso</a> para más información</div></p>


</body>
</html>
