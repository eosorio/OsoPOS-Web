<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->

Introduzca los datos del cliente. Cuando termine, apriete el bot&oacute;n de  continuar.


<form action="<? echo $PHP_SELF ?>" name="forma_cliente" method="post">
<table BORDER=0 WIDTH="100%" name="cliente">
<tbody>
 <tr>
  <td align="right" height=10>Folio
  <td colspan=2 height=10>
    <input type="text" value="<? printf("%d", $id+1) ?>" name=id size=4>

  <td align="right" height=10>Venta No.
  <td><input type="text" name="id_venta" size=4 value="<? echo $id_venta ?>">
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
  <td align="right">Raz&oacute;n social
  <td colspan=4><input type="text" name="razon_soc" size=40 maxlength=50 value="<? echo $razon_soc ?>">
  <td align="right">R.F.C.
  <td><input type="text" name=rfc size=15 maxlength=13
<?php
  if(isset($rfc)) {
    echo " value=\"$rfc\">\n";
    echo "<input type=\"hidden\" name=\"accion\" value=\"agregar\">";
  }
?>
  <td align="right"><font face="helvetica,arial" >C.U.R.P.</font>
  <td><font face="helvetica,arial" >
    <input type="text" value="<? echo $curp ?>" name=curp size="<? echo $MAXCURP ?>"
    maxlength="<? echo $MAXCURP ?>"></font>

 </tr>
 <tr>
  <td align="right"><font face="helvetica,arial" >Domicilio</font>
  <td colspan=4><font face="helvetica,arial" >
    <input type="text" name=dom_calle size=40 maxlength=30 value="<?php echo $dom_calle ?>"></font>
  <td align="right"><font face="helvetica,arial" >N&uacute;mero</font>
  <td><font face="helvetica,arial" ><input type="text" name="dom_ext" size=15 maxlength=15 value="<? echo $dom_ext ?>"></font>
  <td align="right">Interior</td>
  <td><font face="helvetica,arial" >
    <input type="text" name=dom_int size=4 maxlength=7 value="<? echo $dom_int ?>"></font>

 </tr>
 <tr>
  <td align="right"><font face="helvetica,arial" >Colonia:</font>
  <td colspan=4><font face="helvetica,arial" >
    <input type="text" name=dom_col size=40 value="<? echo $dom_col ?>"></font>
  <td align="right"><font face="helvetica,arial" >C.P.</font>
  <td colspan=3><font face="helvetica,arial" >
    <input type="text" name=dom_cp size=5 maxlength=5 value=<? echo $dom_cp ?>></font>

 </tr>
 <tr>
  <td align="right"><font face="helvetica,arial" >Ciudad</font>
  <td colspan=4><font face="helvetica,arial" >
    <input type="text" name=dom_ciudad size=40 value=<? echo "\"$dom_ciudad\""?>></font>
  <td align="right"><font face="helvetica,arial" >Estado</font>
  <td colspan=3><select name=dom_edo>
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
   <font face="helvetica,arial" color="blue">
   <input type=submit value="Continuar"></font>
   <input type=reset value="Limpiar datos">
 </tr>
 <tr>
  <td colspan=9 align=right><hr>
 </tr>
 <tr>
  <td colspan=9 align=right><font face="helvetica,arial" size="+1">
   <a href="busca_cliente.php?php_anterior=<? echo $PHP_SELF
   ?>&id_venta=<? echo $id_venta ?>">Buscar cliente</a> |
   <a href="factur_web_muestra.php">Listado de facturas</a></font>
 </tr>

</tbody>
</table>


</form>

</div>
