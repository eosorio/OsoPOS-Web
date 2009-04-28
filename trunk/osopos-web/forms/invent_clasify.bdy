<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- forms/invent_clasify.bdy -->

  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="selecciones">
  <table border=0 width="100%" id="superior">
  <tr>
   <td>
    <input type="hidden" name="order_by" value="<?php echo $order_by ?>">
    <input type="hidden" name="order" value="<?php echo $order ?>">
    <input type="hidden" name="offset" value=0>
<?php if (isset($alm)) printf("<input type=\"hidden\" name=\"alm\" value=%d>", $alm); ?>
    <small>
Normal <input type="radio" name="mode" value="normal"
<? if (empty($mode) || $mode=="normal") echo "checked" ?>>&nbsp;
Express <input type="radio" name="mode" value="express"
<? if ($mode=="express") echo "checked" ?>>&nbsp;
Baja ex. <input type="radio" name="mode" value="baja_ex"
<? if ($mode=="baja_ex") echo "checked" ?>>
</small>
   </td>
<?php
  if ($PROGRAMA="web") {
?>
   <td>
    Depto.:
    <select name="id_dept">
<?php

  printf("   <option value=\"-1\"");
  if ($id_dept == -1)
    echo " selected";
  echo ">Todos\n";

  while (list($i, $nombre) = each($nm_depto)) {
    printf("   <option value=%d", $i);
    if ($id_dept == $i)
      echo " selected";
    echo ">$nombre\n";
  }
?>
   </select>
  </td>

<?php
  }
else if ($PROGRAMA=="video") {
?>
   <td>
    Género.:
    <select name="depto">
<?
  $d_selected =0;
  for ($i=0; $i<count($nm_depto); $i++) {
    echo "   <option";
    if (!$d_selected && (isset($id_dept) && $nm_depto[$i] == $depto  ||  (isset($id_dept)  &&  $i == $id_dept)))
      echo " selected";
    echo ">$nm_depto[$i]\n";
  }
  echo "   <option value=\"-1\"";
  if (!$d_selected && ($depto == "Todos"  ||  (isset($id_dept) && $id_dept == count($nm_depto)))) {
    echo " selected";
    //    unset($id_dept);
  }
?>
  >Todos
   </select>
  </td>
<?php
  }

  if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
   echo "  <td>Proveedor: ";
   lista_proveedores(FALSE, "id_prov1", "Todos", 1, $id_prov1);
   echo "  </td>\n";
  }
  else
    echo "  <td>&nbsp;</td>\n";
?>
   <td><input type="submit" value="Mostrar"></td>

 <td align="right">
 </td>
 </tr>
 </table>
  <? if ($debug)
	 echo "<input type=\"hidden\" name=\"debug\" value=$debug>\n";
  ?>
  </form>
<!-- fin de invent_clasify -->
