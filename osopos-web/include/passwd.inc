<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
passwd.inc 0.0.3-1. include file for osopos_web

        Copyright (C) 2000-2009 Eduardo Israel Osorio Hernández
        desarrollo@elpuntodeventa.com

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
  $error_pwd = 0;
  if(!isset($_SERVER['PHP_AUTH_USER'])) {
    @Header("WWW-Authenticate: Basic realm=\"OsoPOS Web\"");
    @Header("HTTP/1.0 401 Unauthorized");
    $error_pwd = 1;
  } 
  else {

    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      $passwd = sha1($_SERVER['PHP_AUTH_PW']);
      $con_pwd = db_connect($DB_HOST, $DB_USER, $DB_PASSWD, $DB_NAME, "", $DB_PORT);
      if ($con_pwd == 0  &&  $SQL_TYPE == "postgres") {
        echo "ERROR: Al conectarse a la base de datos $DB_NAME<br />\n";
        echo db_errormsg($con_pwd);
        echo "</body></html>";
        exit();
      }
      else if ($con_pwd==-1  &&  $SQL_TYPE == "mysql") {
        echo "Error al seleccionar la base de datos $DB_NAME<br />\n";
        /*igm*/ echo "#" . mysql_error() . "#";
        echo db_errormsg($con_pwd);
        echo "<br />\n";
      }
      else if ($con_pwd==-2  &&  $SQL_TYPE == "mysql") {
        echo "Error al conectarse al servidor $DB_HOST<br />\n";
        echo db_errormsg($con_pwd);
        echo "<br />\n";
      }
     
      $query = sprintf("SELECT * FROM users WHERE \"user\"='%s'", $_SERVER['PHP_AUTH_USER']);

      if (!$result = db_query($query, $con_pwd)) {
        echo "<b>Error al consultar password</b><br />\n";

        echo db_errormsg($con_pwd) . "<br />\n";
        echo "</body></html>\n";
        exit();
      }

      if (!db_num_rows($result))
        $error_pwd = 1;
      else {
        $user = db_fetch_object($result, 0);

        if ($user->passwd != $passwd)
          $error_pwd = 1;

      }
      db_close($con_pwd);
      $conn = db_connect($DB_HOST, $DB_USER, $DB_PASSWD, $DB_NAME, "", $DB_PORT);
    }
    else {
      echo "<html><head><title>OsoPOS Web v. 0.3</title></head>\n";
      echo "<body bgcolor=\"white\" background=\"imagenes/fondo.gif\">\n";
      echo "<b>Es necesario que se registre para usar OsoPOS</b><br />\n";
    }
  }
  if ($error_pwd>0) {
    include("include/logout.inc");
  }
}
?>