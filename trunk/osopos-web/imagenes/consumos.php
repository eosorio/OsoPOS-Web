<?
{
  header("Content-Type: image/png");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // Date in the past 
  header("Last-Modified: " . gmdate( "D, d M Y H:i:s") .  "GMT");  // always modified 
  header("Cache-Control: no-cache, must-revalidate");            // HTTP/1.1 
  header("Pragma: no-cache");                                    // HTTP/1.0 

  $im_maxx = 300;
  $im_maxy = 300;
  $tam_y = $im_maxy + 50; /* esp. barras + espacio para  encabezados */
  $tam_x = $im_maxx + 50;
  $desp_x = 55;        /* espacio entre barras y borde de imagen */
  $espacio_x = 3;      /* espacio entre barras */
  $im = imagecreate($tam_x, $tam_y);
  $ancho_barra = floor(($im_maxx-$espacio_x-$desp_x) / $num_barras);
  $blanco       = ImageColorAllocate($im, 255,255,255);
  $negro        = ImageColorAllocate($im, 0,0,0);
  $rojo         = ImageColorAllocate($im, 255, 0, 0);
  $azul         = ImageColorAllocate($im, 0, 0, 255);
  $fondo_barras = ImageColorAllocate($im, 253, 255, 229);
  $color = array();

  for ($i=0; $i<$num_barras; $i++) {
	$color[$i] = ImageColorAllocate($im, $color_r[$i], $color_g[$i], $color_b[$i]);
  }
  ImageFilledRectangle($im, 0, 0, $tam_x, $tam_y, $fondo_barras); /* Ponemos fondo a la imagen */
  $max_y = max($cant_platillo);
  $mult_y = floor($im_maxy/$max_y);
  imageline($im, $desp_x, 0, $desp_x, $im_maxy, $rojo); /* escala */
  for ($i=0; $i<=$max_y; $i++) {
	imageline($im, $desp_x-2, $im_maxy-($i*$mult_y), $desp_x+2, $im_maxy-($i*$mult_y), $rojo);
	imagestring($im, 1, $desp_x-12, $im_maxy-($i*$mult_y)-4, $i, $negro); /* referencias */
  }
  imagestring($im, 4, $im_maxx/2 - $desp_x + 30, $im_maxy+20, "ID platillo", $negro); /* titulo x */
  imagestringup($im, 4, $desp_x-40, $im_maxy/2 + 30, "Cantidad", $negro);

  for ($i=0; $i<$num_barras; $i++) {
	$platillo = each( $cant_platillo );
	$indice = $platillo[key];
	$valor  = $platillo[value];

	$x1 = $i*$ancho_barra + $desp_x;
	ImageFilledRectangle($im, $x1 + $espacio_x, $im_maxy-($platillo[value]*$mult_y),
						 $x1+$ancho_barra, $im_maxy, $color[$i]);
	ImageString($im, 1, $x1+$ancho_barra/2, $im_maxy+4, $platillo[key], $negro);
  }
    
  imagepng($im);
}
?>
