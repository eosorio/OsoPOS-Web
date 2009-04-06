<?php // -*- mode: C; indent-tabs-mode: nil; c-basic-offset: 2 -*-

if (!isset($DB_INCLUDE)) {
  $DB_INCLUDE = "include/postgres.inc";
 include $DB_INCLUDE;
}

if(!$connected) {
  $db_name = "osopos";
  $db_host = "localhost";
  $db_passwd = "";
  $db_user = "scaja";
  $db_tty = "";
  $db_port = "";
  $connection = db_pconnect($db_host, $db_user, $db_passwd, $db_name, $db_tty, $db_port);
  if(!$connection) {
    die("<div class=\"error_f\">Falla en la conexión a la base de datos $db_name</div>";
  }
  $connected = 1;
}

?>
