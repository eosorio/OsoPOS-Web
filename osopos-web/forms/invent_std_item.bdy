<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
<!-- forms/invent_std_item.bdy -->
<script type="text/javascript">
var j_ex;
var j_pu1;
var j_pu2;
var j_pu3;
var j_pu4;
var j_pu5;

function calcula_ex(modificador) {
  indice = document.articulo.mas_menos.selectedIndex;

  /* Hago los dos pasos para acordarme como se hace; ya se que se
  puede obtener el signo en un solo paso */
  if (document.articulo.mas_menos.options[indice].text == "-")
    modificador = modificador * -1;
  j_ex = modificador;
  /*document.articulo.ex.value = eval(modificador) + eval(document.articulo.ex.value);*/
  calcula(document.articulo.ex, modificador);
}

function calcula_precios() {
  j_pu1 = eval(document.articulo.mod_pu1.value)/100 * eval(document.articulo.p_costo.value);
  calcula(document.articulo.pu, j_pu1);

  j_pu2 = eval(document.articulo.mod_pu2.value)/100 * eval(document.articulo.p_costo.value);
  calcula(document.articulo.precio2, j_pu2);

  j_pu3 = eval(document.articulo.mod_pu3.value)/100 * eval(document.articulo.p_costo.value);
  calcula(document.articulo.precio3, j_pu3);

  j_pu4 = eval(document.articulo.mod_pu4.value)/100 * eval(document.articulo.p_costo.value);
  calcula(document.articulo.precio4, j_pu4);

  j_pu5 = eval(document.articulo.mod_pu5.value)/100 * eval(document.articulo.p_costo.value);
  calcula(document.articulo.precio5, j_pu5);
}

function deshacer_precios() {
  deshacer(document.articulo.pu1, j_pu1);
  deshacer(document.articulo.precio2, j_pu2);
  deshacer(document.articulo.precio3, j_pu3);
  deshacer(document.articulo.precio4, j_pu4);
  deshacer(document.articulo.precio5, j_pu5);
}

function sincroniza_precios() {
  document.articulo.pu.value = document.articulo.p_costo.value;
  document.articulo.precio2.value = document.articulo.p_costo.value;
  document.articulo.precio3.value = document.articulo.p_costo.value;
  document.articulo.precio4.value = document.articulo.p_costo.value;
  document.articulo.precio5.value = document.articulo.p_costo.value;
}

function calcula(item, modificador) {
  item.value = eval(item.value) + eval(modificador);
}

function deshacer(item, modificador) {
  item.value = item.value - modificador;
}

</script>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="articulo" method="POST" enctype="multipart/form-data">
<table width="100%" border=1>
<colgroup>
<col width="30%">
<col width="10%">
<col width="20%">
<col width="40%">
</colgroup>
 <tr>
   <td colspan=4>
   <table width="100%">
     <tr>
      <td>C&oacute;digo</td>
<?php
    if (isset($codigo)) {
      echo "<td>$codigo <input type=\"hidden\" name=\"codigo\" $val_cod></td>\n";
    }
    else {
      echo "  <td><input type=\"text\" name=\"codigo\" maxlength=$MAXCOD></td>\n";
    }
?>
     <td>Descripci&oacute;n</td>
     <td colspan=3><?php
     if (isset($alm) && $alm>0)
       printf("%s\n", str_replace("\"", "", str_replace("value=", "", $val_desc)));
     else
       echo "<input type=\"text\" name=\"descripcion\" maxlength=50 size=40 $val_desc>\n";
     ?></td>
    </tr>
    <tr>
     <td>Cód. alt.</td>
     <td><?php
       printf("<input type=\"text\" name=\"codigo2\" maxlength=%d %s>", $MAXCOD, $val_cod2) ?>
       <input type="hidden" name="search" value="<?php echo $search ?>">
     </td>
<?php
     if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
       echo "     <td>\n";
       echo "        C&oacute;d. prov.\n";
       echo "     </td>\n";
       echo "     <td>\n";
       if (isset($alm) && $alm>0)
         printf("%s\n", str_replace("value=", "", $val_prod_clave));
       else
         echo "<input type=text name=\"prov_clave\" size=$MAXCOD $val_prov_clave>";
     }
     else
       echo "      <td>&nbsp;</td><td>&nbsp;</td>\n";
?>
     </td>
     </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td>
   <table border=0 width="100%">
   <tr>
     <td>P. público</td>
     <td><input type="text" name="pu" size=10 <?php echo $val_pu ?>></td>
     <td>+<input tpye="text" name="mod_pu1" size=2>%</td>
    </tr>
     <?php if ($alm>0) { ?>
    <tr>
     <td>Precio 2</td>
     <td><input type="text" name="precio2" size=10 <?php echo $val_pu2 ?>></td>
     <td>+<input tpye="text" name="mod_pu2" size=2>%</td>
    </tr>
    <tr>
     <td>Precio 3</td>
     <td><input type="text" name="precio3" size=10 <?php echo $val_pu3 ?>></td>
     <td>+<input tpye="text" name="mod_pu3" size=2>%</td>
    </tr>
    <tr>
     <td>Precio 4</td>
     <td><input type="text" name="precio4" size=10 <?php echo $val_pu4 ?>></td>
     <td>+<input tpye="text" name="mod_pu4" size=2>%</td>
    </tr>
    <tr>
     <td>Precio 5</td>
     <td><input type="text" name="precio5" size=10 <?php echo $val_pu5 ?>></td>
     <td>+<input tpye="text" name="mod_pu5" size=2>%</td>
    </tr>
<?php
  }
  if (puede_hacer($conn, $user->user, "invent_ver_pcosto") && !isset($alm)) {
 ?>

    <tr>
     <td>P. Costo</td>
     <td><?php
     if (isset($alm) && $alm>0)
       printf("%.2f\n", str_replace("value=", "", $val_p_costo));
     else 
       echo "<input type=\"text\" name=\"p_costo\" size=10 $val_p_costo>"
     ?></td>
    </tr>
    <tr>
     <td colspan=2 align="center">
     <input type="button" name="boton_actualiza_pu" value="Calcular precios"
     onclick="calcula_precios()">
     </td>
    </tr>
    <tr>
     <td colspan=2 align="center">
     <input type="button" name="boton_sincroniza_pu" value="Sincronizar precios"
     onclick="sincroniza_precios()">
     </td>
    </tr>
<?php } ?>
    <tr>
     <td colspan=2 align="center">
     <input type="button" name="boton_recarga_pu" value="Deshacer"
     onclick="deshacer_precios()">
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table width=200>
    <tr>
     <td>Divisa</td>
     <td colspan=3><input type="text" name="divisa" size=3 <?php echo $val_divisa ?>></td>
    </tr>
    <tr>
     <td>Descuento</td>
     <td><input type="text" name="descuento" size=5
        <?php echo $val_disc ?>>%</td>
    </tr>
    <tr>
     <td>I.V.A.</td>
     <td><input type="text" name="iva_porc" size=5 <?php echo
     $val_iva_porc ?>>%</td>
    </tr>
    <tr>
     <td>Imp. suntuario</td>
     <td><input type="text" name="imp_porc[0]" size=5 <?php echo
     $val_imp_porc[0] ?>>%</td>
    </tr>
<?php    for ($j=1; $j<$MAXTAX; $j++) { ?>
    <tr>
     <td>Impuesto <?php echo $j ?></td>
     <td><input type="text" name="imp_porc[<?php echo $j ?>]" size=5 <?php echo
     $val_imp_porc[$j] ?>>%</td>
    </tr>
<?php } ?>
    </table>
   </td>
   <td valign="top">
    <table>
	<tr>
	  <td>Unidad de medida</td>
	  <td><input type="text" name="u_medida" size=4 <?php echo $val_u_medida ?>></td>
	</tr>
	<tr>
	  <td>Unidad de empaque</td>
	  <td><input type="text" name="u_empaque" size=4 <?php echo $val_u_empaque ?>></td>
	</tr>
	<?php if (isset($alm) && $alm>0) {
	  if ($action!="agrega") { ?>
    <tr>
     <td>Existencia actual</td>
     <td>
     <input type="hidden" name="ex" <?php echo $val_ex ?>>
     <?php printf("%.2f", str_replace("value=", "", $val_ex)) ?>
     </td>
    </tr>
     <?php } ?>
    <tr>
     <td>Existencia min.</td>
     <td><input type="text" size=4 name="ex_min" <?php echo $val_min ?>></td>
    </tr>
    <tr>
     <td>Existencia max.</td>
     <td><input type="text" size=4 name="ex_max" <?php echo $val_max ?>></td>
    </tr>
<?php
}
 ?>

    </table>
   </td>
   <td valign="top">
    <table>
<?php
     if (puede_hacer($conn, $user->user, "invent_ver_prov")) {
?>
     <tr>
      <td>Proveedor 1</td>
      <td><?php lista_proveedores(FALSE, "id_prov1", $reng->id_prov1) ?></td>
     </tr>
     <tr>
      <td>Proveedor 2</td>
      <td><?php lista_proveedores(FALSE, "id_prov2", $reng->id_prov2) ?></td>
     </tr>
<?php } ?>
     <tr>
      <td>Depto./Línea</td>
      <td>
  <?php
    if (!isset($alm) || $alm==0) {
      echo "          <select name=depto>\n";
      for ($i=0; $i<$num_ren_depto; $i++) {
        if (strlen($nm_depto[$i])) {
          echo "   <option";
          if ($i == ($reng->id_depto))
          echo " selected";
          echo ">$nm_depto[$i]\n";
        }
      }
      echo "        </select>\n";
    }
    else
      printf("%s\n", $nm_depto[$reng->id_depto]);
  ?>
  </td>
  </tr>
  </table>
  </td>
 </tr>

 <tr>
  <td colspan=4 align="right">
  <input type="reset" value="Restaurar datos"><input type="submit" <?php echo $val_submit ?>>
</td>
 </tr>
</table>

<table width="100%">
<tr>
  <td>Descripción ampliada del producto:</td><td>&nbsp;</td>
</tr>
<tr>
  <td width="100%">
  <textarea name="long_desc" cols=80 rows=8><?php echo $long_desc ?></textarea>
  </td>
  <td>
<?php if ($action!="agrega")
     printf("   <img src=\"%s/%s\">\n", $IMG_DIR, $img_location);
   else
	 echo "&nbsp;\n";
?>
  </td>
</tr>
<tr>
  <td colspan=2>Ubicación de la imagen:
  <input type="file" name="img_source" size=60 value="<?php echo "$PWD_DIR/$IMG_DIR/$img_location" ?>">
<?php
  if (isset($debug) && $debug>0)
    echo "  <input type=\"hidden\" name=\"debug\" value=\"$debug\">\n"; 
  if (isset($alm) && $alm>0)
    echo "  <input type=\"hidden\" name=\"alm\" value=\"$alm\">\n";
?>
  </td>
</tr>
</table>
<input type="hidden" name="order_by" value="<?php echo $order_by ?>">
<input type="hidden" name="offset" value="<?php echo $offset ?>">
<input type="hidden" name="order" value="<?php echo $order ?>">
<input type="hidden" name="mode" value="<?php echo $mode ?>">
<?php
{
if ($action=="agrega")
  echo "<input type=\"hidden\" name=\"action\" value=\"inserta\">\n";
else if ($action=="muestra")
  echo "<input type=\"hidden\" name=\"action\" value=\"cambia\">\n";
}
?>

</form>
