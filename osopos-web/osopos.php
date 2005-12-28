<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 OsoPOS Web 1.21. Sistema de punto de venta en Intranet
        Copyright (C) 1999-2003 Eduardo Israel Osorio Hern�ndez
        iosorio@elpuntodeventa.com

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
   <meta name="Author" content="E. Israel Osorio Hern�ndez">
   <title>OsoPOS v. 1.21</title>
<?php include("menu/menu_principal.inc"); ?>

   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <style type="text/css">
    div.notify {font-style: italic; color: red}
   </style>

</head>

<body>
<?php include("menu/menu_principal.bdy"); ?>
<center>
<h1>OsoPOS v. 1.21</h1>
<h3>Copyright (C) 1999-2005<br>
 E. Israel Osorio H.<br>
elpuntodeventa.com</h3>
</center>


<br>
<br>
<p><div style="font-size: 8pt">OsoPOS es gratuito y distribuido con la esperanza de que sea �til, pero
SIN GARANTIA ALGUNA. Consulte la <a href="gpl.php">licencia de uso</a> para m�s informaci�n</div></p>

</body>
</html>
