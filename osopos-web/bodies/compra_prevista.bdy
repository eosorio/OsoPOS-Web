<?php if (empty($subaction) || $subaction!="imprimir")
	 echo "<form action=\"$_SERVER['PHP_SELF']\" method=\"post\">\n";
?>
<table width="800pt">
  <tbody>
    <tr>
      <td align="center"><h1>Centro Musical de Tapachula</h1></td>
    </tr>
    <tr>
      <td><table width="100%" width="100%">
  <tbody>
    <tr>
      <td rowspan="2" width="60%" style="font-size: large"><?php echo "$dia/$mes/$anio" ?></td>
      <td style="font-size: large" style="font-size: large">Recepción No.  </td>
      <td align="right" style="font-size: large" style="font-size: large"><?php echo $folio ?></td>
    </tr>
    <tr>
      <td style="font-size: large">Compra No. </td>
      <td align="right" style="font-size: large" style="font-size: large"><?php echo $folio ?></td>
    </tr>
  </tbody>
</table>  </td>
    </tr>
    <tr>
      <td><table width="100%">
  <tbody>
    <tr>
      <td rowspan="2" style="font-size: large"><?php echo $prov_linea1 ?><br>
	  <?php echo $prov_linea2 ?><br>
	  (xxx)xxx-xxx<br>
	  <?php echo $prov_linea3 ?><br>
	  <?php echo $prov_rfc ?></td>
      <td align="right" style="font-size: large">( <?php printf("%d", $id_prov) ?> )  </td>
    </tr>
    <tr>
      <td align="right" style="font-size: large">Almacén: <?php printf("%d", $muestra_alm) ?>  </td>
    </tr>
  </tbody>
</table>  </td>
    </tr>
    <tr>
	  <?php if ($subaction=="imprimir") { ?>
      <td height="700pt" valign="top">
	  <?php } else echo "      <td>\n" ?>
	    <table width="100%">
  <thead>
    <tr>
      <th scope=col>Cant.  </th>
      <th scope=col>Clave  </th>
      <th scope=col>Descripción  </th>
      <th scope=col>I.V.A.  </th>
      <th scope=col>% Descuento  </th>
      <th scope=col>P. Unitario  </th>
      <th scope=col>Importe  </th>
    </tr>
  </thead>
  <tbody>
<?php
{
  $lista = $osopos_carrito;
  $iva = 0.0;
  $subtotal = 0.0;

  $i=0;
  while (list ($cod, $cant) = each ($lista)) {
	$query = "SELECT art.descripcion, art.p_costo, art.iva_porc, art.divisa, alm.* ";
	$query.= sprintf(" FROM articulos art, almacen_1 alm WHERE alm.id_alm=%d AND art.codigo=alm.codigo AND art.codigo='%s' ",
					$muestra_alm, $cod);

    if (!$db_res = db_query($query, $conn)) {
	  die ("<div class=\"error_f\">No puedo consultar datos de productos</div><br>\n");
	}
	$art = db_fetch_object($db_res, 0);

	$ct[$i] = $cant;
	$codg[$i] = $cod;
	$descripcion[$i] = $art->descripcion;
	$iva_porc[$i] = $art->iva_porc;
	$descto[$i] = 0;
	$p_costo[$i] = $art->p_costo;

	$iva += ($art->iva_porc/100 * $art->p_costo * $cant);
	$subtotal += ($art->p_costo * $cant);
	$total = $subtotal + $iva - $descuento;

	echo "    <tr>\n";
	printf("     <td class=\"art_cant\">%.2f</td>\n", $ct[$i]);
	printf("     <td class=\"art_clave\">%s</td>\n", $codg[$i]);
	printf("     <td class=\"art_desc\">%s</td>\n", htmlentities($descripcion[$i]));
	printf("     <td class=\"art_iva\">%.2f</td>", $iva_porc[$i]);
	printf("     <td class=\"art_descto\">%.2f</td>", $descto[$i]);
	printf("     <td class=\"art_unit\">%.2f</td>", $p_costo[$i]);
	printf("     <td class=\"art_importe\">%.2f</td>", $p_costo[$i] * $ct[$i]);
	echo "    </tr>\n";
	$i++;
  }
} ?>
  </tbody>
</table>  </td>
    </tr>
    <tr>
      <td><table border="0" width="100%">
	  <colgroup><col width="50%" span="2"></colgroup>
  <tbody>
    <tr>
      <td width="50%"><table border="0" width="100%">
	  <colgroup><col width="50%" span="2"></colgroup>
  <tbody>
    <tr>
      <td style="font-size: large">Recibió<br><br></td>
	  <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="font-size: large">Elaboró <br><br></td>
	  <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="font-size: large">Verificó <br><br></td>
	  <td>&nbsp;</td>
    </tr>
  </tbody>
</table>  </td>
      <td><table border="0">
  <tbody>
    <tr>
      <td style="font-size: large">SubTotal  </td>
      <td style="font-size: large; text-align: right"><?php printf("%.2f", $subtotal) ?></td>
    </tr>
    <tr>
      <td style="font-size: large">Descuento  </td>
      <td style="font-size: large; text-align: right">0.00</td>
    </tr>
    <tr>
      <td style="font-size: large">I.V.A.  </td>
      <td style="font-size: large; text-align: right"><?php printf("%.2f", $iva) ?></td>
    </tr>
    <tr>
      <td style="font-size: large">Total</td>
      <td style="font-size: large; text-align: right"><?php printf("%.2f", $total) ?></td>
    </tr>
	<tr>
		<td colspan="2" style="font-size: small">
	  <?php printf("%s %s/100 M.N.", str_cant($total, $cents), $cents) ?></td>
	</tr>
  </tbody>
</table>  </td>
    </tr>
  </tbody>
</table>  </td>
    </tr>
  </tbody>
</table>
<?php
  if (empty($subaction) || $subaction!="imprimir") {
    echo "<input type=\"submit\" value=\"Registrar y mostrar hoja imprimible\"><br>\n";
	echo "<input type=\"hidden\" name=\"subaction\" value=\"imprimir\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"prevista\">\n";
	printf("<input type=\"hidden\" name=\"id_prov\" value=\"%d\">\n", $id_prov);
	printf("<input type=\"hidden\" name=\"dia\" value=\"%d\">\n", $dia);
	printf("<input type=\"hidden\" name=\"mes\" value=\"%d\">\n", $mes);
	printf("<input type=\"hidden\" name=\"anio\" value=\"%d\">\n", $anio);

	echo "<input type=\"hidden\" name=\"prov_linea1\" value=\"$prov_linea1\">\n";
	echo "<input type=\"hidden\" name=\"prov_linea2\" value=\"$prov_linea2\">\n";
	echo "<input type=\"hidden\" name=\"prov_linea3\" value=\"$prov_linea3\">\n";
	printf("<input type=\"hidden\" name=\"prov_rfc\" value=\"%s\">\n",
		   $prov_rfc);

	printf("<input type=\"hidden\" name=\"tipo_movimiento\" value=\"%d\">\n",
		   $tipo_movimiento);

	printf("<input type=\"hidden\" name=\"muestra_alm\" value=\"%d\">\n",
		   $muestra_alm);

	echo "<input type=\"hidden\" name=\"divisa\" value=\"$divisa\">\n";
	printf("<input type=\"hidden\" name=\"folio\" value=%d>\n", $folio);

	echo "</form>\n";

  }
?>
