<?  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

  auth.inc Módulo de contraseñas de OsoPOS Web
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

function existe_usuario($db_conn, $nombre, $password, $nivel) {
  $query = "SELECT id FROM users where \"user\"='$nombre'  ";
  $query.= sprintf("AND passwd='%s' AND level=%d ", md5($password), $nivel);

  $db_res = db_query($query, $db_conn);
  if (!$db_res)
	return($DB_ERROR);

  return (@db_result($db_res, 0, 0));
}

function agrega_usuario($db_conn, $nombre, $password, $nivel) {
  if (!existe_usuario($db_conn, $nombre, $password, $nivel)) {
	$query = "INSERT INTO users (\"user\", passwd, level) VALUES ";
	$query.= sprintf("('%s', '%s', %d)",
					 $nombre, md5($password), $nivel);

	$db_res = db_query($query, $db_conn);
	if (!$db_res)
	  return($DB_ERROR);

	return (existe_usuario($db_conn, $nombre, $password, $nivel));
  }
  return(0);
}

function borra_usuario($db_conn, $nombre) {
  if (!existe_usuario($db_conn, $nombre, $password, $nivel)) {
	$query = "DELETE FROM users WHERE \"user\"='$nombre' ";
	//	$query.= sprintf("AND passwd='%s' ", md5($password));

	$db_res = db_query($query, $db_conn);
	if (!$db_res)
	  return($DB_ERROR);

	return(1);
  }
  else
	return(0);
}

function cambia_usuario($db_conn, $nombre, $ant_passwd, $nivel, $nvo_passwd) {
  $id = existe_usuario($db_conn, $nombre, $ant_passwd, $nivel);
  if ($id) {
	$query = sprintf("UPDATE users SET \"user\"='%s', passwd='%s', level=%d ",
					 $nombre, md5($nvo_passwd), $nivel);
	$query.= sprintf("WHERE id=%d ", $id);
	$db_res = db_query($query, $db_conn);
	if (!$db_res)
	  return($DB_ERROR);

	return($id);
  }
  else
	return(0);
}