<!-- forms/proveedor.inc -->
<table width="100%">
<form action="<? echo "$PHP_SELF?accion=$acc" ?>" method="post">
<tr>
   <td>ID.</td><td><input type="hidden" name="id" value=<? printf("%d", $id) ?>> <? printf("%d", $id) ?></td>
</tr>

<tr>
  <td>Nick</td><td colspan=4><input type="text" name="nick"
    maxlength=15 size=15 <? echo $val_nick ?>></td>
  <td>Raz&oacute;n soc.</td>
  <td colspan=4><input type="text" name="razon_soc" maxlength=60
    size=30 <? echo $val_razon ?>></td>
</tr>
<tr>
<?
    echo "  <td>Calle</td><td colspan=4><input type=\"text\" name=\"calle\" maxlength=30 size=30 $val_calle></td>\n";
    echo "  <td>Colonia</td><td colspan=4><input type=\"text\" name=\"colonia\" maxlength=25 size=25 $val_colonia></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td>Ciudad</td><td colspan=4><input type=\"text\" name=\"ciudad\" maxlength=30 size=30 $val_ciudad></td>\n";
//    echo "  <td>Estado</td><td colspan=4><input type=\"text\" name=\"estado\" maxlength=30 size=30 $val_estado></td>\n";
?>
  <td>Estado</td>
  <td colspan=4><select name="estado">
<?
  if (empty($estado) && !empty($ESTADO_OMISION))
    $dom_edo = $ESTADO_OMISION;

  include("include/estados.inc");
  for ($i=1; $i<=count($estado); $i++) {
    echo "   <option";
    if ($estado[$i] == $dom_edo)
      echo " selected";
    echo ">$estado[$i]\n";
  }
?>
    </select>
  </td>
</tr>

<tr>
  <td>&nbsp;<td>Area<td>N&uacute;mero<td>Ext.<td>&iquest;Es fax?
  <td>&nbsp;<td>Area<td>N&uacute;mero<td>Ext.<td>&iquest;Es fax?
</tr>



<? for($i=0; $i<4; $i+=2) { ?>
<tr>
  <td><img src="imagenes/telefono.gif" width="30" heigth="30"></td>
  <td><input type="text" name="clave_ld[<? printf("%d",  $i) ?>]" maxlength=3 size=3 <? echo
  $val_clave_ld[$i] ?>></td>
  <td><input type="text" name="tel[<? printf("%d",  $i) ?>]" maxlength=7 size=7 <? echo
  $val_tel[$i] ?>></td>
  <td><input type="text" name="ext[<? printf("%d",  $i) ?>]" maxlength=5 size=4 <? echo
  $val_ext[$i] ?>></td>
  <td><input type="checkbox" name="tel_fax[]"
  <? echo "$val_tel_fax[$i] value=$i" ?>></td>

  <td><img src="imagenes/telefono.gif" width="30" heigth="30"></td>
  <td><input type="text" name="clave_ld[<? printf("%d",  $i+1) ?>]" maxlength=3 size=3 <? echo
  $val_clave_ld[$i+1] ?>></td>
  <td><input type="text" name="tel[<? printf("%d",  $i+1) ?>]" maxlength=7 size=7
  <? echo $val_tel[$i+1] ?>></td>
  <td><input type="text" name="ext[<? printf("%d",  $i+1) ?>]" maxlength=5 size=4 echo <?
  $val_ext[$i+1] ?>></td>
  <td><input type="checkbox" name="tel_fax[]" <? echo $val_tel_fax[$i+1] ?>
   value=<? printf("%d", $i+1) ?>></td>
</tr>
<? }
    echo " <tr>\n";
    echo "  <td>Contacto</td>\n";
    echo "  <td colspan=4><input type="text" name=\"contacto\" maxlength=40 size=40 $val_contacto></td>\n";
    echo "  <td>&nbsp;</td><td colspan=4><input type=\"submit\" $val_submit></td>\n";
    echo " </tr>\n";
?>
</table>
