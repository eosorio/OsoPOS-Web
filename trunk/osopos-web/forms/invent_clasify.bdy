<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- forms/invent_clasify.bdy -->

  <form action=<? echo "\"$PHP_SELF\"" ?> method="post" name="selecciones">
  <table border=0 width="100%" id="superior">
  <tr>
   <td>
    <input type="hidden" name="order_by" value="<? echo $order_by ?>">
    <input type="hidden" name="order" value="<? echo $order ?>">
    <input type="hidden" name="offset" value=0>
<? if (isset($alm)) printf("<input type=\"hidden\" name=\"alm\" value=%d>", $alm); ?>
    <small>
Normal <input type="radio" name="mode" value="normal"
<? if (empty($mode) || $mode=="normal") echo "checked" ?>>&nbsp;
Express <input type="radio" name="mode" value="express"
<? if ($mode=="express") echo "checked" ?>>&nbsp;
Baja ex. <input type="radio" name="mode" value="baja_ex"
<? if ($mode=="baja_ex") echo "checked" ?>>
</small>
   </td>
<?
  if ($PROGRAMA="web") {
?>
   <td>
    Depto.:
    <select name="id_dept">
<?php
  $d_selected =0;
//  for ($i=0; $i<count($nm_depto); $i++) {
  while (list($i, $nombre) = each($nm_depto)) {
    printf("   <option value=%d", $i);
    if (!$d_selected && (isset($id_dept) && $nm_depto[$i] == $depto  ||  (isset($id_dept)  &&  $i == $id_dept)))
      echo " selected";
    echo ">$nombre\n";
  }
  printf("   <option value=%d", count($nm_depto));
  if (!$d_selected && ($depto == "Todos"  ||  (isset($id_dept) && $id_dept == count($nm_depto)))) {
    echo " selected";
    unset($id_dept);
  }
?>
  >Todos
   </select>
  </td>

<?
  }
else if ($PROGRAMA=="video") {
?>
   <td>
    G�nero.:
    <select name="depto">
<?
  $d_selected =0;
  for ($i=0; $i<count($nm_depto); $i++) {
    echo "   <option";
    if (!$d_selected && (isset($id_dept) && $nm_depto[$i] == $depto  ||  (isset($id_dept)  &&  $i == $id_dept)))
      echo " selected";
    echo ">$nm_depto[$i]\n";
  }
  echo "   <option";
  if (!$d_selected && ($depto == "Todos"  ||  (isset($id_dept) && $id_dept == count($nm_depto)))) {
    echo " selected";
    unset($id_dept);
  }
?>
  >Todos
   </select>
  </td>
<?php
  }

  if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
   echo "  <td>Proveedor: ";
    lista_proveedores(TRUE, "prov1", "Todos");
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
