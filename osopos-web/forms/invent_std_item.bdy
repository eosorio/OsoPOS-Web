<? /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ ?>
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

<form action="<? echo $form_action ?>" name="articulo" method="POST">
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
<?
    if (isset($codigo)) {
      echo "<td>$codigo <input type=hidden name=codigo $val_cod></td>\n";
    }
    else {
      echo "  <td><input type=\"text\" name=codigo maxlength=$MAXCOD></td>\n";
    }
?>
     <td>Descripci&oacute;n</td>
     <td colspan=3><input type="text" name="descripcion" maxlength=50
     size=40 <? echo $val_desc ?>></td>
    </tr>
    <tr>
     <td>Cód. alt.</td>
     <td><input type="text" name="codigo2"
         <? printf("maxlength=%d %s", $MAX_COD, $val_cod2) ?>></td>
      <td>
        <input type="hidden" name="search" value="<? echo $search ?>">C&oacute;d. prov.
      </td>
      <td><input type=text name="prov_clave" <? echo "size=$MAXCOD $val_prov_clave" ?>></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td>
   <table border=0 width="100%">
   <tr>
     <td>P. público</td>
     <td><input type="text" name="pu" size=10 <? echo $val_pu ?>></td>
     <td>+<input tpye="text" name="mod_pu1" size=2>%</td>
    </tr>
    <tr>
     <td>Precio 2</td>
     <td><input type="text" name="precio2" size=10 <? echo $val_pu2 ?>></td>
     <td>+<input tpye="text" name="mod_pu2" size=2>%</td>
    </tr>
    <tr>
     <td>Precio 3</td>
     <td><input type="text" name="precio3" size=10 <? echo $val_pu3 ?>></td>
     <td>+<input tpye="text" name="mod_pu3" size=2>%</td>
    </tr>
    <tr>
     <td>Precio 4</td>
     <td><input type="text" name="precio4" size=10 <? echo $val_pu4 ?>></td>
     <td>+<input tpye="text" name="mod_pu4" size=2>%</td>
    </tr>
    <tr>
     <td>Precio 5</td>
     <td><input type="text" name="precio5" size=10 <? echo $val_pu5 ?>></td>
     <td>+<input tpye="text" name="mod_pu5" size=2>%</td>
    </tr>
    <tr>
     <td>P. Costo</td>
     <td><input type=text name="p_costo" size=10 <? echo $val_p_costo ?>></td>
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
     <td colspan=3><input type="text" name="divisa" size=3 <? echo $val_divisa ?>></td>
    </tr>
    <tr>
     <td>Descuento</td>
     <td><input type="text" name="descuento" size=5
        <? echo $val_disc ?>>%</td>
    </tr>
    <tr>
     <td>I.V.A.</td>
     <td><input type="text" name="iva_porc" size=5 <? echo
     $val_iva_porc ?>>%</td>
    </tr>
    <tr>
     <td>Imp. suntuario</td>
     <td><input type="text" name="imp_porc[0]" size=5 <? echo
     $val_imp_porc[0] ?>>%</td>
    </tr>
<?    for ($j=1; $j<$MAXTAX; $j++) { ?>
    <tr>
     <td>Impuesto <? echo $j ?></td>
     <td><input type="text" name="imp_porc[<? echo $j ?>]" size=5 <? echo
     $val_imp_porc[$j] ?>>%</td>
    </tr>
<? } ?>
    </table>
   </td>
   <td valign="top">
    <table>
    <tr>
     <td>Existencia actual</td>
     <td><input type=text name=ex size=4 <? echo $val_ex ?>></td>
    </tr>
    <tr>
     <td>Existencia min.</td>
     <td><input type=text size=4 name=ex_min <? echo $val_min ?>></td>
    </tr>
    <tr>
     <td>Existencia max.</td>
     <td><input type=text size=4 name=ex_max <? echo $val_max ?>></td>
    </tr>
    <tr>
     <td>Ex. act.
     <select name="mas_menos">
      <option value="+">+
      <option value="-">-
     </select></td>
     <td><input type="text" name="modificador" size=4></td>
    </tr>
    <tr>
     <td colspan=2 align="center">
     <input type="button" name="boton_actualiza_ex" value="Calcular"
     onclick="calcula_ex(document.articulo.modificador.value)">
     </td>
    </tr>
    <tr>
     <td colspan=2 align="center">
     <input type="button" name="boton_recarga_ex" value="Deshacer"
     onclick="deshacer(document.articulo.ex, j_ex)">
     </td>
    </tr>
    </table>
   </td>
   <td valign="top">
    <table>
     <tr>
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
     </tr>
     <tr>
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
  </table>
  </td>
 </tr>

 <tr>
  <td colspan=4 align="right">
  <input type="reset" value="Restaurar datos"><input type="submit" <? echo $val_submit ?>>
</td>
 </tr>
</table>
</form>
