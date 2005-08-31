<!-- bodies/factur_prevista.bdy -->
<table border=0 width="100%">
<tbody>
 <tr>
  <td>
<b>Cliente:</b><br>
<?php
  echo "$razon_soc<br>\n";
  printf("%s %s", $dom_calle, $dom_ext);
  if (strlen($dom_int))
    echo "-$dom_int";
  echo "<br>\n";
  if (strlen($dom_col))
    echo "Col. $dom_col. ";
  if (!empty($dom_cp))
    printf("C.P. %d<br>\n", $dom_cp);
  echo "$dom_ciudad, $dom_edo<br>\n";
  echo "<b>R.F.C.</b> $rfc<br>\n";
?>
  
  <td>
  Folio: <? echo $id ?><br>
  Fecha: 
  <? 
    echo "$dia-$mes-$anio<br>\n";
  ?>
  Observaciones: <br><br><br><br>
  

</tbody>
</table>
<hr>

<form action="<?php echo $PHP_SELF ?>" method="post">
<table width="100%" border=0>
<thead>
 <tr>
  <th width="10%">Clave</th><th width="5%">Ct.</th>
  <th width="65%">Descripci&oacute;n</th>
  <th width="10%">P.U.</th>
  <th width="10%">Importe</th>
 </tr>

<tbody>

<?php
  for ($i=0; $i<$num_arts && $i<10; $i++) {
	if (isset($desc) && count($desc)) {
      /* si los datos vienen de una forma */
	  $articulo[$i]->iva_porc = $iva_porc[$i];
	  $articulo[$i]->tax_0 = $tax_0_porc[$i];
	  $articulo[$i]->tax_1 = $tax_1_porc[$i];
	  $articulo[$i]->tax_2 = $tax_2_porc[$i];
	  $articulo[$i]->tax_3 = $tax_3_porc[$i];
	  $articulo[$i]->tax_4 = $tax_4_porc[$i];
	  $articulo[$i]->tax_5 = $tax_5_porc[$i];
	  $articulo[$i]->pu = $pu[$i];
	  $articulo[$i]->codigo = $codigo[$i];
	  $articulo[$i]->cant = $cant[$i];
	  $articulo[$i]->desc = $desc[$i];
	}

    if ($FACTUR_IVA_INCLUIDO) {
	  $razon_impuesto = $articulo[$i]->iva_porc;
    }

    if ($FACTUR_IMPUESTO_INCLUIDO[0]) {

	  $razon_impuesto += $articulo[$i]->tax_0;
    }
    if ($FACTUR_IMPUESTO_INCLUIDO[1]) {
	  $razon_impuesto += $articulo[$i]->tax_1;
    }
    if ($FACTUR_IMPUESTO_INCLUIDO[2]) {
	  $razon_impuesto += $articulo[$i]->tax_2;
    }
    if ($FACTUR_IMPUESTO_INCLUIDO[3]) {
	  $razon_impuesto += $articulo[$i]->tax_3;
    }
    if ($FACTUR_IMPUESTO_INCLUIDO[4]) {
	  $razon_impuesto += $articulo[$i]->tax_4;
    }
    if ($FACTUR_IMPUESTO_INCLUIDO[5]) {
	  $razon_impuesto += $articulo[$i]->tax_5;
    }


    if ($razon_impuesto) {
      $articulo[$i]->pu = $articulo[$i]->pu / (1+($razon_impuesto/100));
	}
	$iva += $articulo[$i]->pu * ($articulo[$i]->iva_porc/100) * $articulo[$i]->cant;
	$impuesto[0] += $articulo[$i]->pu * ($articulo[$i]->tax_0/100) * $articulo[$i]->cant;
	$impuesto[1] += $articulo[$i]->pu * ($articulo[$i]->tax_1/100) * $articulo[$i]->cant;
	$impuesto[2] += $articulo[$i]->pu * ($articulo[$i]->tax_2/100) * $articulo[$i]->cant;
	$impuesto[3] += $articulo[$i]->pu * ($articulo[$i]->tax_3/100) * $articulo[$i]->cant;
	$impuesto[4] += $articulo[$i]->pu * ($articulo[$i]->tax_4/100) * $articulo[$i]->cant;
	$impuesto[5] += $articulo[$i]->pu * ($articulo[$i]->tax_5/100) * $articulo[$i]->cant;

    $subtotal += $articulo[$i]->pu * $articulo[$i]->cant;
  
    if (!($i%2))
      $bgcolor = "bgcolor=\"#fdffd8\"";
    else
      $bgcolor = "";
?>

 <tr>
  <td <? echo $bgcolor ?>><? echo $articulo[$i]->codigo ?>&nbsp;
  <input type="hidden" name="codigo[<? echo $i ?>]" value="<? echo $articulo[$i]->codigo ?>"></td>
  <td <? echo $bgcolor ?> align=center><? echo $articulo[$i]->cant ?>
  <input type="hidden" name="cant[<? echo $i ?>]" value="<? echo $articulo[$i]->cant ?>"></td>
  <td width="0*" <? echo $bgcolor ?>><? echo htmlspecialchars(stripslashes($articulo[$i]->desc)) ?>&nbsp;
  <input type="hidden" name="desc[<? echo $i ?>]" value="<? echo htmlspecialchars(stripslashes($articulo[$i]->desc)) ?>"></td>
  <td <? echo $bgcolor ?> align="right"><? printf("%.2f",  $articulo[$i]->pu) ?>
  <input type="hidden" name="pu[<? echo $i ?>]" value="<? echo $articulo[$i]->pu ?>"></td>
  <td <? echo $bgcolor ?> align="right"><? printf("%.2f",  $articulo[$i]->pu*$articulo[$i]->cant) ?></td>
 </tr>

<?php
	}  /* for */
?>

 <tr>
  <td colspan=3>
    <table margin=0 width="100%">
    <tr>
	  <td class="campo"><small><b>Garantía:</b></small></td>
	  <td class="campo"><small><b>Observaciones:</b></small>
	  <input type=hidden name=id value="<?php printf("%d", $id) ?>">
      <input type=hidden name=anio value="<?php printf("%d", $anio) ?>">
	  <input type=hidden name=mes value="<?php printf("%d", $mes) ?>">
	  <input type=hidden name=dia value="<?php printf("%d", $dia) ?>">
      <input type=hidden name=rfc value="<?php echo $rfc ?>">
      <input type=hidden name=razon_soc value="<?php echo $razon_soc ?>">
      <input type=hidden name=dom_calle value="<?php echo $dom_calle ?>">
      <input type=hidden name=dom_ext value="<?php echo $dom_ext ?>">
      <input type=hidden name=dom_int value="<?php echo $dom_int ?>">
      <input type=hidden name=dom_col value="<?php echo $dom_col ?>">
      <input type=hidden name=dom_ciudad value="<?php echo $dom_ciudad ?>">
      <input type=hidden name=dom_edo value="<?php echo $dom_edo ?>">
      <input type=hidden name=dom_cp value="<?php echo $dom_cp ?>">
      </td>
    </tr>

    <tr>
     <td class="campo">
       <input type="text" name="garantia" size=10>
     </td>
     <td class="campo" colspan=2 rowspan=3>
      <textarea name="observaciones" cols=<? printf("%d", $OBS_MAXCOLS) ?>
      rows=<? printf("%d", $OBS_MAXRENS) ?>><? echo $OBS_DEFAULT ?></textarea>
     </td>
    </tr>
  </table>
  </td>

  <td align="right" colspan=2>
     <table border=0 width="100%">
     <tr>
       <td class="right"><b>Subtotal</b></td>
       <td class="moneda"><b><? printf("%.2f", $subtotal) ?></b>
       <input type="hidden" name="subtotal" value=<?php echo $subtotal ?>></td>
     </tr>

     <tr>
     <td class="right"><b>I.V.A.</b></td>
     <td class="moneda"><b><? printf("%.2f", $iva) ?></b>
     <input type="hidden" name="iva" value=<? echo $iva ?>></td>
    </tr>

<? if ($DESGLOSAR_IMPUESTO[0]) { ?>
    <tr>
     <td class="right"><b>I.E.P.S.</b></td>
     <td class="moneda"><b><? printf("%.2f", $impuesto[0]) ?></b>
     <input type="hidden" name="impuesto[0]" value=<? printf("%.2f", $impuesto[0]) ?>></td>
    </tr>
<? }
  $impuestos = $impuesto[0] + $iva;

  for ($i=1; $i<$MAXTAX; $i++) {
	$impuestos += $impuesto[$i];
    if ($DESGLOSAR_IMPUESTO[$i]) { ?>
    <tr>
     <td class="right"><b><? echo "Impuesto $i" ?></b></td>
     <td class="moneda"><b><? printf("%.2f", $impuesto[$i]) ?></b>
     <input type="hidden" name="impuesto[<? echo $i ?>]" value=<? printf("%.2f", $impuesto[$i]) ?>></td>
    </tr>
<? }
  }
  $total = $subtotal + $impuestos;

 ?>

    <tr>
     <td class="right"><b>Total</b></td>
     <td class="right_red"><b><?php printf("%.2f", $total) ?></b></td>
    </tr>
  </table>
  </td>
  </tr>
</table>
<br><br>
<center>

<h4><b><?php echo str_cant($total, $centavos); printf("pesos %s", $centavos); ?>/100 M.N.</b></h4>
</center>

<br>
<table border=0 width="100%">
<tbody>
 <tr>
  <td width=5 align="center"><input type="radio" name="accion" value="agregarimprimir" checked>
  <td>Agregar e imprimir
  <td width=5 align="center"><input type="radio" name="accion" value="agregar">
  <td>Sólo agregar
  <td align=right><input type=submit value="Registrar factura">
</tbody>
</table>
<input type="hidden" name="id_cliente" value="<?php echo $id_cliente ?>">
</form>
