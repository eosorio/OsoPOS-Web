<? /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- forms/invent_std_item.bdy -->
<form action="<? echo $form_action ?>" name="articulo" method=POST>
<table width=\"100%\">
 <tr>
   <td>C&oacute;digo</td>
<?
    if (isset($codigo)) {
      echo "<td>$codigo <input type=hidden name=codigo $val_cod></td>\n";
    }
    else {
      echo "  <td><input type=\"text\" name=codigo maxlength=20></td>\n";
    }
?>
  <td>Descripci&oacute;n</td>
  <td colspan=3><input type=text name=descripcion maxlength=50
    size=40 <? echo $val_desc ?>></td>
 </tr>
 <tr>
  <td>P.U.</td>
  <td><input type=text name=pu size=10 <? echo $val_pu ?>></td>
  <td>I.V.A.</td>
  <td><input type=text name=iva_porc size=5 <? echo $val_iva_porc ?>>%</td>
  <td>Descuento</td>
  <td><input type=text name=descuento size=5 <? echo $val_disc ?>>%</td>
 </tr>
 <tr>
  <td>P. Costo</td>
  <td><input type=text name=p_costo size=10 <? echo $val_p_costo ?>></td>
  <td>Divisa</td>
  <td colspan=3><input type="text" name="divisa" size=3 <? echo $val_divisa ?>></td>
 </tr>
 <tr>
   <td>Existencia actual</td>
   <td><input type=text name=ex size=4 <? echo $val_ex ?>></td>
   <td>Existencia min.</td>
   <td><input type=text size=4 name=ex_min <? echo $val_min ?>></td>
   <td>Existencia max.</td>
   <td><input type=text size=4 name=ex_max <? echo $val_max ?>></td>
  </tr>
  <tr>
      <td>
        <input type="hidden" name="search" value="<? echo $search ?>">C&oacute;d. prov.
      </td>
      <td><input type=text name=prov_clave <? echo $val_prov_clave ?> size=20></td>
      <td>Proveedor</td>
      <td><select name=prov>
   <?
    for ($i=0; $i<$num_ren_prov; $i++) {
      if (strlen($nick_prov[$i])) {
        echo "   <option";
        if ($i == ($reng->id_prov - ($SQL_TYPE=="mysql")))
          echo " selected";
        echo ">$nick_prov[$i]</option>\n";
      }
    }
   ?>
      </select></td>
      <td>Departamento</td>
      <td><select name=depto>
  <?
    for ($i=0; $i<$num_ren_depto; $i++) {
      if (strlen($nm_depto[$i])) {
        echo "   <option";
        if ($i == ($reng->id_depto - ($SQL_TYPE=="mysql")))
          echo " selected";
        echo ">$nm_depto[$i]\n";
      }
    }
  ?>
  </select></td>
 </tr>

 <tr>
  <td colspan=6 align=right><input type=submit <? echo $val_submit ?>></td>
 </tr>
</table>
</form>