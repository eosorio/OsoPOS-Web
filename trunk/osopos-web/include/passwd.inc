<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
{

  if(!isset($PHP_AUTH_USER)) {
    @Header("WWW-Authenticate: Basic realm=\"OsoPOS Web\"");
    @Header("HTTP/1.0 401 Unauthorized");
    $error_pwd = 1;
  } 
  else {

    if (isset($PHP_AUTH_USER) && isset($PHP_AUTH_PW)) {
      $passwd = md5($PHP_AUTH_PW);
      //      $conn = pg_connect("host=$DB_HOST dbname=$DB_NAME user=$DB_OWNER");
      $conn = db_connect($DB_HOST, $DB_OWNER, "",  $DB_NAME);
      /*igm*/ /*$conn = mysql_connect($DB_HOST, $DB_OWNER, "");*/
      if ($conn == 0) {
        echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n";
        echo db_errormsg($conn);
        echo "</body></html>";
        exit();
      }
      /*      if (mysql_select_db($DB_NAME, $conn) == 0) {
        echo "Error al seleccionar la base de datos $DB_NAME<br>\n";
        echo db_errormsg($conn);
        echo "<br>\n";
        }*/
     
      $query = "SELECT * FROM users WHERE user='$PHP_AUTH_USER'";
      //      if (!$result = pg_exec($conn, $query)) {
      if (!$result = db_query($query, $conn)) {
        echo "<b>Error al consultar password</b><br>\n";
        //        echo pg_errormessage($conn) . "<br>\n";
        echo db_errormsg($conn) . "<br>\n";
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
    }
    else {
      echo "<HTML><HEAD><TITLE>OsoPOS Web v. 0.3</TITLE></HEAD>\n";
      echo "<BODY BGCOLOR=\"white\" BACKGROUND=\"imagenes/fondo.gif\">\n";
      echo "<b>Es necesario que se registre para usar OsoPOS</b><br>\n";
    }
  }
  if ($error_pwd) {
    include("include/logout.inc");
  }
}
?>
