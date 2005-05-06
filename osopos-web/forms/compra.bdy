<h1>Orden de compra</h1>

<?php
{
  if (empty($action) || $action="cabecera") {

  /* Determinación de la fecha actual */
	if (!isset($dia))
	  $dia = date("j");

	if (empty($mes_s)) {
	  include "include/mes.inc";
	  $mes_s = $meses[date("n")-1];
	}
	if (!isset($mes))
	  $mes = date("n");
	if (!isset($anio))
	  $anio = date("Y");

	if(empty($id_prov)) {
	  echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
?>
<table border=0 width=600> 
<tr>
  <td>Folio</td>
  <td><input type="text" name="folio" size=5 value="<?php printf("%d", $folio+1) ?>"></td>
  <td>Fecha</td><td><input type="text" name="dia" size=2 maxlength=2 value="<?php echo $dia ?>">
	 <select name="mes">
      <?php
        include "include/mes.inc";
        for ($i=0; $i<count($meses); $i++) {
          echo "<option value=$i";
          if ($i == $mes-1)
            echo " selected";
          echo ">$meses[$i]\n";
        }
      ?>
      </select>
      <input type="text" size=4 maxlength=4 name="anio" value="<?php echo $anio ?>">
  </td>
  <td>Proveedor</td><td><?php lista_proveedores(FALSE, "id_prov", "Seleccione uno") ?></td>
</tr>
</table>
<input type="submit" value="Continuar">
<?php
   echo "</form>\n";
	}
	else {
	  $query = "SELECT * FROM proveedores WHERE id=$id_prov";
	  if (!$db_res = db_query($query, $conn)) {
		echo "<div class=\"error_nf\">No puedo consultar proveedores</div><br>\n";
	  }
	  $prov = db_fetch_object($db_res, 0);
?>


<form action="<?php echo $PHP_SELF ?>" method="post">
<table border=0 width="100%">
<tr>
  <?php if ($tipo_movimiento == $TIPO_RECEPCION) { ?>
  <td>Recepción</td><td><input type="text" name=""></td>
  <?php }
   else
	 echo "  <td colspan=2>&nbsp;</td>\n";
  ?>
  <td>Folio</td>
  <td><?php printf("<input type=\"hidden\" name=\"folio\" value=\"%d\">%d", $folio, $folio) ?></td>
  <td>Fecha</td>
  <td><?php printf("%d/%d/%d", $dia, $mes, $anio) ?></td>
  <td>Proveedor</td>
  <td><?php printf("%s<input type=\"hidden\" name=\"id_prov\" value=\"%s\">", $prov->nick, $prov->id) ?></td>
</tr>

<tr>
  <td>Nombre:</td><td colspan=5><?php echo $prov->razon_soc ?></td>
  <td>Impuesto</td>  <td><input type="text" name="iva" size=2 value="<?php echo $IVA_PORCENTAJE ?>"></td>
</tr>

<tr>
  <td>Dirección:</td>
  <td colspan=5>
  <?php printf("%s. %s<br>\n%s, %s. C.P.%d<br>\n", 
			   $prov->calle, $prov->colonia, $prov->ciudad, $prov->estado, $prov->cp) ?></td>
  <td>Descuento</td><td><input type="text" name=""></td>
</tr>

<tr>
  <td>R.F.C.:</td><td colspan=5><?php printf("%s", $prov->rfc) ?></td>
  <td>Desc. financiero</td><td><input type="text" name=""></td>
</tr>

<tr>
  <td rowspan=2>Entregar a:</td><td colspan=3 rowspan=2><textarea name="dom_entrega" cols=40 rows=3></textarea></td>
  <td>Ref. Prov.</td><td><input type="text" name=""></td>
  <td>Fecha recepción</td>
  <td><input type="text" name="rec_dia" size=2 maxlength=2 value="<? echo $rec_dia ?>">
	 <select name="rec_mes">
      <?php
        include "include/mes.inc";
        for ($i=0; $i<count($meses); $i++) {
          echo "<option value=$i";
          if ($i == $mes-1)
            echo " selected";
          echo ">$meses[$i]\n";
        }
      ?>
      </select>
      <input type="text" size=4 maxlength=4 name="rec_anio" value=<?php echo $rec_anio ?>>
  </td>
</tr>


<tr>
  <td>Almacén</td><td><?php  echo lista_almacen($conn, "muestra_alm", "Ninguno") ?></td>
</tr>

<tr>
  <td>Divisa:</td><td><select name="divisa"><?php echo lista_divisas($conn, "MXP") ?></select></td>
  <td colspan=4>&nbsp;</td>
  <td>Tipo de cambio:</td>
  <td><input type="text" name=""></td>
</tr>

<tr>
  <td colspan=8 align="right"><input type="submit"
  value="Continuar"></td>
</tr>


</table>
<?php
  printf("<input type=\"hidden\" name=\"id_prov\" value=\"%d\">\n", $id_prov);
  printf("<input type=\"hidden\" name=\"dia\" value=\"%d\">\n", $dia);
  printf("<input type=\"hidden\" name=\"mes\" value=\"%d\">\n", $mes);
  printf("<input type=\"hidden\" name=\"anio\" value=\"%d\">\n", $anio);

  printf("<input type=\"hidden\" name=\"prov_linea1\" value=\"%s\">\n",
		 $prov->razon_soc);
  printf("<input type=\"hidden\" name=\"prov_linea2\" value=\"%s. %s.\">\n",
		 $prov->calle, $prov->colonia);
  printf("<input type=\"hidden\" name=\"prov_linea3\" value=\"%s, %s. C.P.%d\">\n",
		 $prov->ciudad, $prov->estado, $prov->cp);
  printf("<input type=\"hidden\" name=\"prov_rfc\" value=\"%s\">\n",
		 $prov->rfc);

  printf("<input type=\"hidden\" name=\"tipo_movimiento\" value=\"%d\">\n",
		 $tipo_movimiento);
?>
<input type="hidden" name="action" value="prevista">

</form>
<?php
			  }
  }
else {
?>
<form action="<?php echo $PHP_SELF ?>" method="post">
<table border=0 width="100%">
<tr>
  <th>Cant.</th><th>Producto</th><th>Unidad</th><th>Descuento</th>
  <th>Impuesto</th><th>Costo unitario</th>
</tr>
<tr>
  <td align="center"><input type="text" name="qt[]" size=4></td>
  <td align="center"><input type="text" name="codigo[]" size=20></td>
  <td align="center"><input type="text" name="unidad[]" size=5></td>
  <td align="center"><input type="text" name="descuento[]" size=5></td>
  <td align="center"><input type="text" name="iva[]" size=5></td>
  <td align="center"><input type="text" name="p_costo_unit[]" size=10></td>
</tr>
</table>
</form>
<?php
   }
  
}
?>