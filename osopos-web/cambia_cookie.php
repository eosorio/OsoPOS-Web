<?php
{
  if (isset($_POST['nm_cookie']))
    $nm_cookie = &$_POST['nm_cookie'];
  if (isset($_POST['valor_a']))
    $valor_a = &$_POST['valor_a'];
  if (isset($_POST['url']))
    $url = &$_POST['url'];

  setcookie($nm_cookie, $valor_a);
  header("Location: $url");
}
?>
<html>
<body>
Un momento por favor...
</body>
</html>