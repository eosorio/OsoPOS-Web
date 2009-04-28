Agregar usuario:
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table border="0" width="400">
<tr>
  <td align="right">login:</td>
  <td><input type="text" size="15" name="login"></td>
</tr>
<tr>
  <td align="right">Contraseña:</td>
  <td><input type="password" name="new_passwd" size="20"></td>
</tr>
<tr>
  <td align="right">Nivel:</td>
  <td><input type="text" name="level" size="1"></td>
</tr>
<tr>
  <td colspan="2"><input type="submit" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="action" value="agregar">
</form>
<hr>
Borrar usuario (no se solicitará confirmación)
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table border="0" width="400">
<tr>
  <td align="right">login:</td>
  <td><input type="text" size="15" name="login"></td>
</tr>
<tr>
  <td colspan="2"><input type="submit" value="Borrar"></td>
</tr>
</table>
<input type="hidden" name="action" value="borrar">
</form>
<hr>
Cambiar contraseña
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table border="0" width="400">
<tr>
  <td align="right">login:</td>
  <td><input type="text" size="15" name="login"></td>
</tr>
<tr>
  <td align="right">Contraseña anterior:</td>
  <td><input type="password" name="old_passwd" size="20"></td>
</tr>
<tr>
  <td align="right">Nueva contraseña:</td>
  <td><input type="password" name="new_passwd1" size="20"></td>
</tr>
<tr>
  <td align="right">Confirma nueva contraseña:</td>
  <td><input type="password" name="new_passwd2" size="20"></td>
</tr>
<tr>
  <td align="right">Nivel:</td>
  <td><input type="text" name="level" size="1"></td>
</tr>
<tr>
  <td colspan="2"><input type="submit" value="Cambiar"></td>
</tr>
</table>
<input type="hidden" name="action" value="cambiar">
</form>
<hr>
