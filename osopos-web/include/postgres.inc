<?php /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */

function db_pconnect($db_host, $db_user, $db_passwd, $db_name, $tty = "", $port = "")
{
  if ($db_host)
    $str_con = "host=$db_host";
  if ($db_user)
    $str_con .= " user=$db_user";
  if ($db_passwd)
    $str_con .= " password=$db_passwd";
  if ($tty)
    $str_con .= " tty=$tty";
  if ($port)
    $str_con .= " port=$port";
  if ($db_name)
    $str_con .= " dbname=$db_name";
  return(pg_pconnect($str_con));
}

function db_connect($db_host, $db_user, $db_passwd, $db_name, $tty = "", $port = "")
{
  if ($db_host)
    $str_con = "host=$db_host";
  if ($db_user)
    $str_con .= " user=$db_user";
  if ($db_passwd)
    $str_con .= " password=$db_passwd";
  if ($tty)
    $str_con .= " tty=$tty";
  if ($port)
    $str_con .= " port=$port";
  if ($db_name)
    $str_con .= " dbname=$db_name";
  return(pg_connect($str_con));
}

function db_query($query, $link_id)
{
  if (strstr($query, "LIKE") && strstr($query, "%")) {
      $query = str_replace("%", "", $query);
      $query = str_replace("LIKE", "~", $query);
  }
  if (strstr($query, "concat")) {
    $sub_inicio = strpos($query, "concat(");
    $sub_final = strpos($query, ")", $sub_inicio);
    $sub_cadena_orig = substr($query, $sub_inicio, $sub_final-$sub_inicio+1);
    $sub_cadena = str_replace(",", "||", $sub_cadena_orig);
    $sub_cadena = str_replace("concat", "", $sub_cadena);
    $query = str_replace($sub_cadena_orig, $sub_cadena, $query);
  }
  return (pg_query($link_id, $query));
}

function db_num_rows($resultado)
{
  return(pg_numrows($resultado));
}

function db_fetch_row($resultado_db, $row)
{
  return(@pg_fetch_row($resultado_db, $row));
}

function db_fetch_object($resultado_bd, $row, $tipo_resultado = "")
{
  if ($tipo_resultado != "")
    return(@pg_fetch_object($resultado_bd, $row, $tipo_resultado));
  else
    return(@pg_fetch_object($resultado_bd, $row));
}

function db_fetch_array($resultado_bdm, $ren, $tipo_resultado = "")
{
  return(@pg_fetch_array($resultado, $ren, $tipo_resultado));

}

function db_affected_rows($resultado_bd)
{
  return(pg_cmdtuples($resultado_bd));
}

function db_close($link)
{
  return(pg_close($link));
}

function db_result($resultado, $num_ren, $num_campo)
{
  return(pg_result($resultado, $num_ren, $num_campo));
}

function db_errormsg($link_id)
{
  return(pg_errormessage($link_id));
}

function db_get_last_oid($result_id)
     /* Devuelve el último identificador de objeto de la tupla insertada con INSERT */
{
  return(pg_getlastoid($result_id));
}

function db_fieldisnull($result_id, $row, $field = "")
{
  return(pg_fieldisnull($result_id, $row, $field));
}
?>
