<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */

function db_pconnect($db_host, $db_user, $db_passwd, $db_name, $tty = "", $port = "")
{
  //  global $link;

  $link = mysql_pconnect($db_host, $db_user, $db_passwd);
  if ($link)
    if (mysql_select_db($db_name, $link) == 0)
      return(0);
    else
      return($link);
  else
    return(0);
}

function db_connect($db_host, $db_user, $db_passwd, $db_name, $tty = "", $port = "")
{
  //  global $link;

  $link = mysql_connect($db_host, $db_user, $db_passwd);
  if ($link && mysql_select_db($db_name, $link))
    return ($link);
  else
    return(0);
}

function db_query($query, $link_id)
{
  return (mysql_query($query, $link_id));
}

function db_num_rows($resultado)
{
  return(mysql_num_rows($resultado));
}

function db_fetch_row($resultado_db)
{
  return(mysql_fetch_row($resultado_db));
}

function db_fetch_object($resultado_db, $row)
{
  return(mysql_fetch_object($resultado_db));
}

function db_affected_rows($resultado_bd)
{
  return(mysql_affected_rows($resultado));
}

function db_result($resultado, $num_ren, $num_campo)
{
  return(mysql_result($resultado, $num_ren, $num_campo));
}

function db_close($link)
{
  return(mysql_close($link));
}

function db_errormsg($link)
{
  if (!$link || $link=-1)
    return(mysql_error());
  else
    return(mysql_error($link));
}
?>
