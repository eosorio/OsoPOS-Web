<? /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- forms/invent_clasify.bdy -->

  <form action=<? echo "\"$PHP_SELF\"" ?> method="post" name="selecciones">
  <table border=0 width="100%" id="superior">
  <tr>
   <td>
    <input type=hidden name=order_by value="<? echo $order_by ?>">
    <input type=hidden name=order value="<? echo $order ?>">
    <input type=hidden name=offset value=0>
    <small>
Normal <input type=radio name="mode" value="normal"
<? if (empty($mode) || $mode=="normal") echo "checked" ?>>&nbsp;
Express <input type=radio name="mode" value="express"
<? if ($mode=="express") echo "checked" ?>>&nbsp;
Baja ex. <input type=radio name="mode" value="baja_ex"
<? if ($mode=="baja_ex") echo "checked" ?>>
</small>
   </td>
   <td>
    Depto.:
    <select name=depto>
<?
  for ($i=0; $i<count($nm_depto); $i++) {
    echo "   <option";
    if ($nm_depto[$i] == $depto  ||  (isset($id_dept)  &&  $i == $id_dept))
      echo " selected";
    echo ">$nm_depto[$i]\n";
  }
  echo "   <option";
  if ($depto == "Todos"  ||  (isset($id_dept) && $id_dept == count($nm_depto))) {
    echo " selected";
  }
?>
  >Todos
   </select>
  </td>

   <td>
    Proveedor:
   <select name=prov>
<?

  for ($i=0; $i<count($nick_prov); $i++) {
    echo "   <option";
    if ($nick_prov[$i] == $prov  ||  (isset($id_prov) && $i==$id_prov && $prov!="Todos"))
      echo " selected";
    echo ">$nick_prov[$i]\n";
  }
  echo "   <option";
  if ($prov == "Todos")
    echo " selected";
?>
  >Todos</select></td>
 <td>  <input type=submit value="Mostrar"></td>

 <td align="right"><font color="#e0e0e0">

<?
  if ($offset > 0) {
    echo "<a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset-$limit);
    echo "&order_by=$order_by&order=$order&mode=$mode$href_dept$href_prov";
	if (!empty($search))
      printf("&search=%s", htmlentities(str_replace(" ", "%20", $search)));
	echo "\">&lt;-</a>";
  }
  else
    echo "&lt;- ";
  if ($offset) {
    echo " <a href=\"$PHP_SELF?offset=0&order_by=$order_by&order=$order&mode=$mode$href_dept$href_prov";
    if (!empty($search))
      printf("&search=%s", htmlentities(str_replace(" ", "%20", $search)));
	echo "\">Inicio</a> ";
  }
  else
    echo "Inicio";
  if ($offset+$limit < $num_arts) {
    echo " <a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset+$limit);
    echo "&order_by=$order_by&order=$order&mode=$mode$href_dept$href_prov";
    if (!empty($search))
      printf("&search=%s", htmlentities(str_replace(" ", "%20", $search)));
	echo "\">-&gt;</a>";
  }
  else
    echo "-&gt;";
?>
  </font>
   </td>
  </tr>
  </table>
  </form>
<!-- fin de invent_clasify -->
