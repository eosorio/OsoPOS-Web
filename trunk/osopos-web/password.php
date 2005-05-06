<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

  passsword.php M�dulo de contrase�as de OsoPOS Web
        Copyright (C) 2002-2004 Eduardo Israel Osorio Hern�ndez
        desarrollo@elpuntodeventa.com

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
if (isset($salir)) {
  include("include/logout.inc");
}
else {
  include("include/passwd.inc");
}
include("include/auth.inc");
include("include/pos.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
  <TITLE>OsoPOS Web - Contrase�as</TITLE>
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
</HEAD>
<BODY>

<?php
{
  if (isset($action) && $action=="listar") {
    $query = "SELECT * FROM users ORDER BY \"user\"";

	$db_res = db_query($query, $conn);
	if (!$db_res)
	  return($DB_ERROR);
    $max_usr = db_num_rows($db_res);

    $query = "SELECT max(id) FROM modulo ";
	$db_res2 = db_query($query, $conn);
	if (!$db_res2)
	  return($DB_ERROR);
    $max_idmodulo = db_result($db_res2, 0, 0);

    include("bodies/usuarios.bdy");
  }
  else {
    echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
    echo "  <input type=\"hidden\" name=\"action\" value=\"listar\">\n";
    echo "  <input type=\"image\" src=\"imagenes/lupa.png\">\n";
    echo "Ver usuarios<br>\n";
    echo "</form>\n";
    //    echo "<a href=\"$PHP_SELF?action=listar\">Ver usuarios</a><br>\n";
    echo "<hr>\n";

    if (puede_hacer($conn, $user->user, "usuarios_general")) {

      if (isset($action) && $action=="agregar") {

        $id = agrega_usuario($conn, $login, $new_passwd, $level, $name);
        if ($id>0) {
          echo "<i>Usuario $id, $login agregado exit�samente</i><br>\n";
          include("forms/usuarios_modulos.bdy");
        }
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
      else if (isset($action) && $action=="modulos_usuario") {
        if (alta_modulos_usuario($conn, $login, $modulo))
          echo "<i>Se otorgaron los permisos correspondientes al usuario $login</i><br>\n";
        else
          echo "<b>Error al dar acceso a m�dulos al usuario $login</b><br>\n";
      }
      if (!isset($action) || $action!="listar" && $action!='agregar')
        include("bodies/password.bdy");
      //    echo md5("mi_password") . "<br>\n";
    }
  }
  db_close($conn);
  include("bodies/menu/general.bdy");
}
?>

</BODY>
</HTML>
