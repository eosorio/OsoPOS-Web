<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

  passsword.php Módulo de contraseñas de OsoPOS Web
        Copyright (C) 2002 Eduardo Israel Osorio Hernández
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
include("include/general_config.inc");
if (isset($salir)) {
  include("include/logout.inc");
}
else {
  include("include/passwd.inc");
}
include("include/auth.inc");
include("include/pos.inc");
?>


<HTML><HEAD><TITLE>OsoPOS Web - Contraseñas</TITLE></HEAD>
<BODY>

<?php
{
  if (isset($action) && $action=="listar") {
    $query = "SELECT id, \"user\", level FROM users ORDER BY \"user\"";

	$db_res = db_query($query, $conn);
	if (!$db_res)
	  return($DB_ERROR);
    $max_usr = db_num_rows($db_res);

    include("bodies/usuarios.bdy");
  }
  else {
    echo "<a href=\"$PHP_SELF?action=listar\">Ver usuarios</a><br>\n";
    echo "<hr>\n";

    if (puede_hacer($conn, $user->user, "usuarios_general")) {

      if (isset($action) && $action=="agregar") {

        $id = agrega_usuario($conn, $login, $new_passwd, $level);
        if ($id>0)
          echo "<i>Usuario $id, $login agregado exitósamente</i><br>\n";
        else if ($id==0)
          printf("<b>El usuario <i>%s</i> ya existe</b><br>\n", $login);
        else
          echo "<b>Error $id</b>. No se pudo agregar usuario $login<br>\n";
      }
      else if (isset($action) && $action=="borrar") {
        if (borra_usuario($conn, $login))
          echo "<i>Usuario $login eliminado</i><br>\n";
        else
          echo "<b>Error.</b>No se pudo eliminar al usuario $login<br>\n";
      }
      else if (isset($action) && $action=="cambiar") {
        if (cambia_usuario($conn, $login, $old_passwd, $level, $new_passwd1))
          echo "<i>Usuario $login cambiado</i><br>\n";
        else
          echo "<b>Error al cambiar usuario</b><br>\n";
      }
      if (!isset($action) || $action!="listar")
        include("bodies/password.bdy");
      //    echo md5("mi_password") . "<br>\n";
    }
  }
}
?>

</BODY>
</HTML>
