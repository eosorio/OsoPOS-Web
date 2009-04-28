<?php
{
  $td_style = "border-right-width: 0; border-left-width: 0";
?>
<h2>Introduzca los números de serie de los productos</h2>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table border=1 width="100%" cellpadding=2>
<colgroup>
  <col width=75><col width=*><col width=350>
</colgroup>
<tr>
  <th>Código</th><th>Descripción</th><th>Serie</th>
</tr>
<?php

  reset($a_series);

  while (list($cod, $n1) = each($a_series)) {
	$desc = articulo_descripcion($conn, $cod);

	for ($i=0; $i < $n1; $i++) {
	  echo "<tr>\n";
	  printf("<td style=\"%s\"><input type=\"hidden\" name=\"codigo[]\" value=\"%s\">%s</td>\n",
			 $td_style, $cod, $cod);
	  printf("<td style=\"%s\">%s<input type=\"hidden\" name=\"costo[]\" value=\"%s\"></td>\n",
			 $td_style, $desc, $pcosto[$cod]);
	  printf("<td style=\"%s; text-align: center\"><input type=\"text\" name=\"serial[]\" size=40></td>\n",
			 $td_style);
	  echo "</tr>\n";
	}
	echo "<tr>\n  <td colspan=3>&nbsp;</td>\n</tr>\n";
  }
}
?>
</table>
<input type="hidden" name="accion" value="series">
<input type="hidden" name="almacen" value=<?php printf("%d", $almacen) ?>>
<input type="image" src="imagenes/web/botones/btn_agregar_serie_inactivo.png">
</form>