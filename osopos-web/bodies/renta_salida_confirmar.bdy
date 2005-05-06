<form action="<?php echo $PHP_SELF ?>" method="post">
<table border=0 width="100%">
<tr>
  <th>Serie</th><th>Código</th><th>Descripción</th><th>Importe</th><th>F. de entrega</th>
</tr>
<?php
$importe = 0.0;
while (list ($nombre, $valor) = each ($codigo)) {
  $anio = substr($f_entrega[$nombre], 0,4);
  $mes = substr($f_entrega[$nombre], 5,2);
  $dia = substr($f_entrega[$nombre], 8,2);
  $hora = substr($f_entrega[$nombre], 11,2);
  $minutos = substr($f_entrega[$nombre], 14,2);
  $ux_entrega = mktime($hora, $minutos, 0, $mes, $dia, $anio);

  $renta_seg = $ux_entrega - time();
  switch ($unidad_t[$nombre]) {
  case 0: $unidades_t = ceil($renta_seg / 60);
    break;
  case 1: $unidades_t = ceil($renta_seg / (60*60));
    break;
  case 3: $unidades_t = ceil($renta_seg / (60*60*24*7)) ;
    break;
  case 4: $unidades_t = ceil($renta_seg / (60*60*24*30)) ;
    break;
  case 5: $unidades_t = ceil($renta_seg / (60*60*24*365)) ;
    break;
  case 2:
  default: $unidades_t = ceil($renta_seg / (60*60*24));
  }
  $importe+= ($costo[$nombre] * $unidades_t);

  echo "<tr>\n";
  printf("  <td>%s<input type=\"hidden\" name=\"almcen[%s]\" value=\"%s\"></td>\n",
	 $nombre, $nombre, $almcen[$nombre]);
  printf("  <td>%s<input type=\"hidden\" name=\"codigo[%s]\" value=\"%s\"></td>\n",
	 $valor, $nombre, $valor);
  printf("  <td>%s<input type=\"hidden\" name=\"descripcion[%s]\" value=\"%s\"></td>\n",
	 $descripcion[$nombre], $nombre, $descripcion[$nombre]);
  /* Aqui convertimos el costo unitario a costo total del artículo */
  printf("  <td>%.2f<input type=\"hidden\" name=\"costo[%s]\" value=\"%s\"></td>\n",
	 $costo[$nombre]*$unidades_t, $nombre, $costo[$nombre]*$unidades_t);
  printf("  <td>%s<input type=\"hidden\" name=\"f_entrega[%s]\" value=\"%s\"></td>\n",
	 $f_entrega[$nombre], $nombre, $f_entrega[$nombre]);
  echo "</tr>\n";

}
?>
</table>
<input type="hidden" name="id_cliente" value="<?php echo $id_cliente ?>">
<input type="hidden" name="accion" value="registrar">
<input type="submit" value="Registrar">
</form>
<?php printf("<big>Importe total: %.2f</big><br>\n", $importe); ?>
