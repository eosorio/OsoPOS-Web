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
      $conn = pg_connect("dbname=$DB_NAME user=$DB_OWNER");
      if (!$conn) {
        echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
        exit();
      }
      $query = "SELECT * FROM users WHERE user='$PHP_AUTH_USER'";
      if (!$result = pg_exec($conn, $query)) {
        echo "<b>Error al consultar password</b><br>\n";
        echo pg_errormessage($conn) . "<br>\n";
        echo "</body></html>\n";
        exit();
      }

      if (!pg_numrows($result))
        $error_pwd = 1;
      else {
        $user = pg_fetch_object($result, 0);

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
