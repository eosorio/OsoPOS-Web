<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
        invent_series.bdy. Cuerpo del m�dulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2003 Eduardo Israel Osorio Hern�ndez

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los t�rminos de la Licencia P�blica General GNU
publicada por la Free Software Foundation: ya sea en la versi�n 2 de la
Licencia, o (a su elecci�n) en una versi�n posterior. 

        Este programa es distribuido con la esperanza de que sea �til, pero
SIN GARANTIA ALGUNA; incluso sin la garant�a impl�cita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. V�ase la Licencia P�blica General
GNU para mayores detalles. 

        Deber�a usted haber recibido una copia de la Licencia P�blica General
GNU junto con este programa; de no ser as�, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 

*/
{
  $boton = "series"; /* Indicador de cual bot�n debe aparecer presionado */
  include("bodies/web/invent_costos_head.bdy");


  $query = "SELECT id,nombre FROM almacenes ";
  if (!$res = db_query($query, $conn)) {
    echo "Error al consultar almacenes<br>\n";
    exit();
  }
  $alm = array();
  $ic = array(); /* Contador de cada columna de la tabla a mostrar */

  $num_cols = db_num_rows($res); /* N�mero de columnas de la tabla a mostrar */

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
   /* Construcci�n de renglones de la tabla */

  $ren = array(array());
  $query = "SELECT id,codigo,almacen,status from articulos_series WHERE codigo='$codigo' ORDER BY id";
  if (!$res = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
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
<br>