<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->

Introduzca los datos del cliente. Cuando termine, apriete el bot&oacute;n de  continuar.


<form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="forma_cliente" method="post">
<table BORDER=0 WIDTH="100%" name="cliente">
<tbody>
 <tr>
  <td align="right" height=10>Folio</td>
  <td colspan=2 height=10>
    <input type="text" value="<?php printf("%d", $id+1) ?>" name="id" size=4>
  </td>
  <td align="right" height=10>Venta No.</td>
  <td><input type="text" name="id_venta" size=4 value="<?php echo $id_venta ?>"></td>
  <td align="right" width="40%" height=10>Fecha
  <td colspan=3 width="40%" height=10>
      <input type="text" name="dia" size=2 maxlength=2
      value="<? echo $dia ?>">
      <select name=mes_s>
      <?
        include "include/mes.inc";
        for ($i=0; $i<count($meses); $i++) {
          echo "<option";
          if ($i == $mes-1)
            echo " selected";
          echo ">$meses[$i]\n";
        }
      ?>
      </select>
      <input type="text" size=4 maxlength=4 name="anio" value=<? echo $anio ?>>
      <input type="hidden" name="fase" value=1>
  </td>
 </tr>
 <tr>
  <td align="left" colspan=8><small><b>Datos del cliente</b></small></td>
 </tr>
 <tr>
  <td align="right">I.D.</td>
  <td><input type="text" name="id_cliente" size=6> <a href="#"><img src="imagenes/lupa.png" onclick="abreVentana('busca_clientes.php')" border=0></a>
 </tr>
 <tr>
  <td align="right">Raz&oacute;n social
  <td colspan=4><input type="text" name="razon_soc" size=40 maxlength=50 value="<? echo $razon_soc ?>">
  <td align="right">R.F.C.
  <td><input type="text" name="rfc" size=15 maxlength=13
<?php
  if(isset($rfc)) {
    echo " value=\"$rfc\">\n";
    echo "<input type=\"hidden\" name=\"accion\" value=\"agregar\">";
  }
?>
  <td align="right">C.U.R.P.
  <td>
    <input type="text" value="<? echo $curp ?>" name=curp size="<? echo $MAXCURP ?>"
    maxlength="<? echo $MAXCURP ?>">

 </tr>
 <tr>
  <td align="right">Domicilio
  <td colspan=4>
    <input type="text" name=dom_calle size=40 maxlength=30 value="<?php echo $dom_calle ?>">
  <td align="right">N&uacute;mero
  <td><input type="text" name="dom_ext" size=15 maxlength=15 value="<? echo $dom_ext ?>">
  <td align="right">Interior</td>
  <td>
    <input type="text" name=dom_int size=4 maxlength=7 value="<? echo $dom_int ?>">

 </tr>
 <tr>
  <td align="right">Colonia:
  <td colspan=4>
    <input type="text" name="dom_col" size=40 value="<? echo $dom_col ?>">
  <td align="right">C.P.
  <td colspan=3>
    <input type="text" name="dom_cp" size=5 maxlength=5 value=<? echo $dom_cp ?>>

 </tr>
 <tr>
  <td align="right">Ciudad
  <td colspan=4>
    <input type="text" name="dom_ciudad" size=40 value=<? echo "\"$dom_ciudad\""?>>
  <td align="right">Estado
  <td colspan=3><select name="dom_edo">
  <option>-- Sin estado --
  <? 
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
   </select>
 </tr>
 <tr>
  <td colspan=9 align="right">
  <?
   if (!file_exists($TMP_VENTA))
	 echo "<input type=\"hidden\" name=\"accion\" value=\"articulos\">\n";
   else
     echo "<input type=\"hidden\" name=\"accion\" value=\"mostrar\">\n";
  ?>
   <font color="blue">
   <input type=submit value="Continuar"></font>
   <input type=reset value="Limpiar datos">
 </tr>
 <tr>
  <td colspan=9 align=right><hr>
 </tr>
 <tr>
  <td colspan=9 align=right><font size="+1">
   <a href="factur_web_muestra.php">Listado de facturas</a></font>
 </tr>

</tbody>
</table>


</form>

</div>
