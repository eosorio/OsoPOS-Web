<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
{
  @Header("WWW-Authenticate: Basic realm=\"OsoPOS Web\"");	
  @Header("HTTP/1.0 401 Unauthorized");
  echo "<HTML><HEAD><TITLE>OsoPOS - Web Invent v. 0.3</TITLE></HEAD>\n";
  echo "<BODY BGCOLOR=\"white\" BACKGROUND=\"imagenes/fondo.gif\">\n";
    /*igm*/ echo "Passwd: $passwd<br>\n";
  echo "<h4>Ya está fuera del sistema.</h4>";
  echo "<center><h2>Gracias por usar un producto de</h2>\n";
  echo "<br><br><br><br>\n";
  echo "<a href=\"http://elpuntodeventa.com\"><img src=\"imagenes/linucs.jpg\" border=0 alt=\"LINUCS\"></a>";
  echo "</center>\n";
  echo "</BODY>\n</HTML>\n";
  exit();
}
