<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
        invent_series.bdy. Cuerpo del módulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2003 Eduardo Israel Osorio Hernández

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los términos de la Licencia Pública General GNU
publicada por la Free Software Foundation: ya sea en la versión 2 de la
Licencia, o (a su elección) en una versión posterior. 

        Este programa es distribuido con la esperanza de que sea útil, pero
SIN GARANTIA ALGUNA; incluso sin la garantía implícita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. Véase la Licencia Pública General
GNU para mayores detalles. 

        Debería usted haber recibido una copia de la Licencia Pública General
GNU junto con este programa; de no ser así, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 

*/
{
  $boton = "series"; /* Indicador de cual botón debe aparecer presionado */
  include("bodies/web/invent_costos_head.bdy");


  $query = "SELECT id,nombre FROM almacenes ";
  if (!$res = db_query($query, $conn)) {
    echo "Error al consultar almacenes<br />\n";
    exit();
  }
  $alm = array();
  $ic = array(); /* Contador de cada columna de la tabla a mostrar */

  $num_cols = db_num_rows($res); /* Número de columnas de la tabla a mostrar */

  for ($i=0; $i < $num_cols; $i++) {
	$r = db_fetch_object($res, $i);
	$alm[$r->id] = $r->nombre;
	$ic[$r->id] = 0;
  }
  include("bodies/web/invent_item_descripcion.bdy");
?>
<table border=1 width="100%">
<tr>
<?php
  reset($alm);
  while (list ($key, $val) = each ($alm))
    echo "  <th>$val</th>\n";

?>
</tr>
<?php
   /* Construcción de renglones de la tabla */

  $ren = array(array());
  $query = "SELECT id,codigo,almacen,status from articulos_series WHERE codigo='$codigo' ORDER BY id";
  if (!$res = db_query($query, $conn)) {
    die("<div class=\·error_f\">Error al seleccionar series de artículos</div>\n");
  }

  for ($i=0; $i < db_num_rows($res); $i++) {
    $tupla = db_fetch_object($res, $i);
    if (substr($tupla->status, 0, 1) == "1")
      $cadena = sprintf("%s <small>(R)</small>", $tupla->id);
    else
      $cadena = $tupla->id;
    $ren[$ic[$tupla->almacen]][$tupla->almacen] = $cadena;
    $ic[$tupla->almacen]++;
  }

  $max_ren = max($ic);

  /* Escritura del HTML */
  for ($i=0; $i < $max_ren; $i++) {
    echo "<tr>\n";
	reset($alm);
	while (list ($key, $val) = each ($alm))
	  if (empty($ren[$i][$key]))
	    echo "  <td>&nbsp;</td>\n";
	  else
		printf("  <td>%s</td>\n", $ren[$i][$key]);
	 echo "</tr>\n";
  }

?>
</table>
<?php
}
?>
<br />