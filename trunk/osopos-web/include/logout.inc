<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
{
  @Header("WWW-Authenticate: Basic realm=\"OsoPOS Web\"");	
  @Header("HTTP/1.0 401 Unauthorized");
}
?>
<html>
<head>
  <title>OsoPOS Web - Fin de sesión</title>
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
</head>
<body bgcolor="white" background="imagenes/fondo.gif">
<h4>Ya está fuera del sistema.</h4>
<center><h2>Gracias por usar un producto de</h2>
<br><br><br><br>
<a href="http://elpuntodeventa.com"><img src="imagenes/elpuntodeventa.png" border=0 alt="elpuntodeventa.com"></a>
</center>
</body>
</html>
<?php  exit(); ?>

