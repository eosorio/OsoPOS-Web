<? // -*- mode: C; indent-tabs-mode: nil; c-basic-offset: 2 -*-

if (!isset($DB_INCLUDE)) {
  $DB_INCLUDE = "include/postgres.inc";
 include $DB_INCLUDE;
}

if(!$connected) {
  $db_name = "intranet";
  $db_host = "localhost";
  $db_passwd = "";
  $db_user = "iosorio";
  $db_tty = "";
  $db_port = "";
  $connection = db_pconnect($db_host, $db_user, $db_passwd, $db_name, $db_tty, $db_port);
  if(!$connection) {
    echo "<br><p>Falla en la conexión a la base de datos <i>$db_name</i><br>";
    echo db_errormsg($link) . "<br>";
	unset($DB_INCLUDE);
    exit;
  }
  $connected = 1;
}

?>
