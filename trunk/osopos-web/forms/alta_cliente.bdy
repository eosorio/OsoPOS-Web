<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->
<h2>Alta de cliente</h2>
<form action="<?php echo $PHP_SELF ?>" method="post">
<table cellpadding=3 width="800px" style="border-style: ridge; border-color: lime; border-width: thick">
<tr>
  <td class="item_tit">Ap. Paterno</td>
  <td><input type="text" name="ap_paterno" size=20></td>
  <td class="item_tit">Ap. Materno</td>
  <td><input type="text" name="ap_materno" size=20></td>
</tr>
<tr>
  <td class="item_tit">Nombres</td>
  <td><input type="text" name="nombres" size=40></td>
  <td class="item_tit">R.F.C.</td>
  <td><input type="text" name="rfc" size=15></td>
</tr>
</table>
<table cellpadding=3 width="800px" style="border-style: ridge; border-color: blue; border-width: thick">
<tr>
  <td colspan=6>Domicilio</td>
</tr>
 <tr>
  <td class="item_tit">Calle</td>
  <td><input type="text" name="dom_calle" size=40 maxlength=30></td>
  <td class="item_tit">N&uacute;mero</td>
  <td><input type="text" name="dom_numero" size=15 maxlength=15></td>
  <td class="item_tit">Interior</td>
  <td><input type="text" name="dom_inter" size=4 maxlength=7></td>
 </tr>
 <tr>
  <td class="item_tit">Colonia</td>
  <td><input type="text" name="dom_col" size=40></td>
  <td class="item_tit">C.P.</td>
  <td colspan=3><input type="text" name="dom_cp" size=5 maxlength=5></td>
 </tr>
 <tr>
  <td class="item_tit">Ciudad</td>
  <td><input type="text" name="dom_ciudad" size=40></td>
  <td class="item_tit">Estado</td>
  <td colspan=3><select name="dom_edo">
  <option>-- Sin estado --
  <?php
  if (empty($estado) && !empty($ESTADO_OMISION))
    $dom_edo = $ESTADO_OMISION;

 include "include/estados.inc";
 for ($i=1; $i<=count($estado); $i++){
   echo "    <option";
   if ($estado[$i] == $dom_edo)
     echo " selected";
   echo ">$estado[$i]\n";
 }
 ?>
   </select></td>
 </tr>
 <tr>
  <td class="item_tit">Tel. casa</td>
  <td><input type="text" name="dom_tel_casa"></td>
  <td class="item_tit">Tel. trabajo</td>
  <td><input type="text" name="dom_tel_trabajo"></td>
</tr>
</table>
<table cellpadding=3 width="800px" style="border-style: ridge;  border-color: olive; border-width: thick">
<colgroup>
  <col><col><col>
</colgroup>
<tr>
  <td colspan=3>Datos de contacto</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td style="text-align: left; font-weight: bold; border-bottom: solid olive; border-width: thin">Referencia 1</td>
  <td style="text-align: left; font-weight: bold; border-bottom: solid olive; border-width: thin">Referencia 2</td>
</tr>
<tr>
  <td class="item_tit" style="border-right: solid olive; border-width: thin">Nombre</td>
  <td><input type="text" name="referencia1" size=40
 maxlength=120></td>
  <td><input type="text" name="referencia2" size=40
 maxlength=120></td>
</tr>
<tr>
  <td class="item_tit" style="border-right: solid olive; border-width: thin">Relación</td>
  <td><input type="text" name="relacion_ref1" size=30
 maxlength=40></td>
 <td><input type="text" name="relacion_ref2" size=30
 maxlength=40></td>
</tr>
<tr>
  <td class="item_tit" style="border-right: solid olive; border-width: thin">Telefono</td>
  <td><input type="text" name="dom_tel_ref1"></td>
  <td><input type="text" name="dom_tel_ref2"></td>
</tr>
</table>
<table cellpadding=3 width="800px" style="border-style: ridge; border-color: gray; border-width: thick">
<colgroup>
  <col width="25%" span=4>
</colgroup>
<tr>
  <td colspan=4>Estad&iacute;sticas</td>
</tr>
<tr>
  <td class="item_tit">Fecha de nacimiento</td>
  <td>
     <input type="text" name="n_dia" size=2>
     <input type="text" name="n_mes" size=2>
     <input type="text" name="n_anio" size=2>
  </td>
  <td class="item_tit">Sexo</td>
  <td><input type="radio" name="sexo" value="m">M
    <input type="radio" name="sexo" value="f">F
  </td>
</tr>
<tr>
  <td class="item_tit">Ocupación</td>
  <td><input type="text" name="ocupacion"></td>
  <td class="item_tit">Edo. Civil</td><td><input type="text" name="edo_civil"></td>
</tr>
</table>
<br>
<input type="hidden" name="accion" value="registrar">
<input type="submit" value="Registrar cliente">
</form>
<hr>
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="hidden" name="accion" value="consulta">
<input type="image" src="imagenes/lupa.png"> Buscar
</form>