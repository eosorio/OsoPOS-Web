<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */

function db_pconnect($db_host, $db_user, $db_passwd, $db_name, $tty, $port)
{
  global $link;

  $link = mysql_pconnect($db_host, $db_user, $db_passwd);
  if ($link)
    return(mysql_select_db($db_name, $link));
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

function db_close($link)
{
  return(mysql_close($link));
}

function db_errormsg($link)
{
  return(mysql_error($link));
}
?>
