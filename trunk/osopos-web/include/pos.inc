<?php  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 pos.inc 0.0.2-1. include file for osopos_web

        Copyright (C) 2000 Eduardo Israel Osorio Hernández
        iosorio@punto-deventa.com

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

function registra_venta($id_vendor, $tipo_pago, $tipo_comprobante,  $total) {

  global $articulo_descripcion, $articulo_cantidad, $articulo_codigo, $user, $conn;

  /*igm*/$utilidad = $total;
  $query = "INSERT INTO ventas (\"monto\", \"tipo_pago\", \"tipo_factur\", \"corte_parcial\", ";
  $query.= "\"utilidad\", \"id_vendedor\", \"id_cajero\", \"fecha\", \"hora\" ) VALUES (";
  $query.= sprintf("%.2f, %d, %d, '%s', %.2f, %d, %d, '%s', '%s')",
					  $total, $tipo_pago, $tipo_comprobante, "f", $utilidad, $id_vendor,
					  $user->id, date("Y-m-d"), date("H:i:s"));
  if (!$resultado = pg_exec($conn, $query)) {
	echo "Error al ejecutar $query<br>\n";
	exit();
  }

  $query = "SELECT max(numero) FROM ventas";
  if (!$resultado = pg_exec($conn, $query)) {
	echo "Error al ejecutar $query<br>\n";
	exit();
  }
  $id_venta = pg_result($resultado, 0, 0);

  for ($i=0; $i<count($articulo_descripcion); $i++) {
	$query = "INSERT INTO ventas_detalle (\"id_venta\", \"codigo\", \"cantidad\")";
	$query.= sprintf(" VALUES (%d, '%s', %d)", $id_venta, $articulo_codigo[$i],
						$articulo_cantidad[$i]);
	if (!$resultado = pg_exec($conn, $query)) {
	  echo "Error al ejecutar $query<br>\n";
	  exit();
	}
  }
  return($id_venta);
}

function update_supp($conn, $num_arts)
{
  global $articulo_codigo, $articulo_cantidad;

  $low_supply = array();
  for ($i=0; $i<$num_arts; $i++) {
    $query = "SELECT \"descripcion\", \"cant\",\"min\" FROM articulos ";
    $query.= sprintf("WHERE codigo='%s'", $articulo_codigo[$i]);
    if (!$result = pg_exec($conn, $query)) {
      echo "<b>Error al actualizar existencias</b><br>Abortando actualización.<br>\n";
      return(0);
    }
    /* Not in database */
    if (pg_numrows($result) < 0)
      continue;

    $art = pg_fetch_object($result, 0);

    $qt_remain = $art->cant - $articulo_cantidad[$i];
    if ($qt_remain < $art->min)
      $low_supply[$articulo_codigo[$i]]++;

    $query = sprintf("UPDATE articulos SET \"cant\"=%d",
                     $qt_remain);
    $query.= sprintf(" WHERE codigo='%s'", $articulo_codigo[$i]);
    if (!$result = pg_exec($conn, $query)) {
      printf("<b>Error al actualizar existencias de %s<br>\n",
             $articulo_codigo[$i]);
    }
  }
  return($low_supply);
}

function notify_low_supplies($code)
{

}

function LeeVenta($nombre) {
  global $MAXDES, $MAXCOD;

  $ar = fopen($nombre,"r");
  if (!$ar)
    return(0);

  $tam_buff = 4096;
  $i = 0;
  $art = array(new articulosClass);
  do {
    $buff = fgets($ar, $tam_buff);
    $buff = substr($buff, 0, strlen($buff)-1);
    if (feof($ar)) {
      $salir = 1;
      fclose($ar);
      continue;
    }
    else {
      $art[$i] = new articulosClass;
      $salir = 0;
      $art[$i]->codigo = substr($buff, 0, $MAXCOD);
    }
    $buff = fgets($ar, $tam_buff);
    $buff = substr($buff, 0, strlen($buff)-1);
    if (feof($ar)) {
      $salir = 1;
      fclose($ar);
      continue;
    }
    else {
      $salir = 0;
      $art[$i]->desc = substr($buff, 0, $MAXDES);
    }
    $buff = fgets($ar, $tam_buff);
    $buff = substr($buff, 0, strlen($buff)-1);
    $art[$i]->cant = $buff;
    $buff = fgets($ar, $tam_buff);
    $buff = substr($buff, 0, strlen($buff)-1);
    $art[$i]->pu   = $buff;
    $buff = fgets($ar, $tam_buff);
    $buff = substr($buff, 0, strlen($buff)-1);
    $art[$i]->iva_porc = $buff;
    $i++;
    fgetc($ar);
  }
  while (!$salir);
  return($art);
}

function lee_venta($id) {

  global $conn;

  $art = array(new articulosClass);

  $query = "SELECT \"codigo\", \"cantidad\" FROM ventas_detalle WHERE id_venta=$id";
  if (!$resultado = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>";
    echo "No se pudo encontrar los datos de la venta<br>" . pg_errormessage($conn) . "<br>\n";
  }
  $num_art = pg_numrows($resultado);

  for ($i=0; $i<$num_art; $i++) {
    $reng = pg_fetch_object($resultado, $i);
    $art[$i]->codigo = $reng->codigo;
    $art[$i]->cant = $reng->cantidad;

    $query = "SELECT \"descripcion\",\"pu\",\"iva_porc\" FROM articulos WHERE ";
    $query.= sprintf("codigo='%s'", $art[$i]->codigo);
    if (!$resultado2 = pg_exec($conn, $query)) {
      echo "Error al ejecutar $query<br>";
      echo "No se pudo accesar la tabla <i>articulos</i><br>\n";
      echo pg_errormessage($conn) . "<br>\n";
      return;
    }

    if (!pg_numrows($resultado2)) {
      echo "<b>Error: No puedo encontrar los datos del articulo" . $art[$i]->codigo . "</b><br>\n";
      echo pg_errormessage($conn) . "<br>\n";
      return;
    }
    $reng2 = pg_fetch_object($resultado2, 0);
    $art[$i]->desc = $reng2->descripcion;
    $art[$i]->pu = $reng2->pu;
    $art[$i]->iva_porc = $reng2->iva_porc;
  }
  return($art);
}

function interp_cant($u, $d, $c) {

  $buffer = "";
  switch ($c) {
    case 1: if ($d || $u)
         $buffer.="ciento ";
       else
	 $buffer.="cien ";
       break;
    case 2: $buffer.="doscientos ";
        break;
    case 3: $buffer.="trescientos ";
        break;
    case 4: $buffer.="cuatrocientos ";
        break;
    case 5: $buffer.="quinientos ";
        break;
    case 6: $buffer.="seiscientos ";
        break;
    case 7: $buffer.="setecientos ";
        break;
    case 8: $buffer.="ochocientos ";
        break;
    case 9: $buffer.="novecientos ";
        break;
    case 0:
    default:
  }

	/* Sección de decenas */
  switch ($d) {
    case 1: 
      switch ($u) { 
          case 1: $buffer.="on";
             break;
          case 2: $buffer.="do";
             break;
          case 3: $buffer.="tre";
             break;
          case 4: $buffer.="cator";
             break;
          case 5: $buffer.="quin";
             break;
          default: $buffer.="diez ";
      }
      break;
    case 2: $buffer.="veinte ";
        break;
    case 3: $buffer.="treinta ";
        break;
    case 4: $buffer.="cuarenta ";
        break;
    case 5: $buffer.="cincuenta ";
        break;
    case 6: $buffer.="sesenta ";
        break;
    case 7: $buffer.="setenta ";
        break;
    case 8: $buffer.="ochenta ";
        break;
    case 9: $buffer.="noventa ";
        break;
    case 0:
    default:
  }
	/* Seccion de unidades */
  if ($d==1 && $u && $u<=5)
    $buffer.="ce "; /* onCE, doCE, treCE... */
  else {
    if (($u) && ($d))
      $buffer.="y ";
    switch ($u) {
      case 1: $buffer.="un ";
          break;
      case 2: $buffer.="dos ";
          break;
      case 3: $buffer.="tres ";
          break;
      case 4: $buffer.="cuatro ";
          break;
      case 5: $buffer.="cinco ";
          break;
      case 6: $buffer.="seis ";
          break;
      case 7: $buffer.="siete ";
          break;
      case 8: $buffer.="ocho ";
          break;
      case 9: $buffer.="nueve ";
          break;
      case 0: 
      default:
    }
  }
  return($buffer);
}


function str_cant($total, &$centavos) {

  $unidades = "";
  $miles = "";
  $millones = "";

  $buffer = sprintf("%0.2f", $total);
  $arreglo = explode(".", $buffer);
  $centavos = $arreglo[1];
  if (strlen($centavos) == 1)
    $centavos.="0";

  $buffer = "";

  $num1 = (int) $total;

  /* Obtención de millones */
  $millon = (int)($num1/1000000);
  $num1 = $num1 - $millon*1000000;

  /* Obtención de centenas de miles */
  $mcentena = (int)($num1/100000);
  $num1 = $num1 - $mcentena*100000;

  /* Obtencion de decenas de miles */
  $mdecena = (int)($num1/10000);
  $num1 = $num1 - $mdecena*10000;

  /* Obtencion de miles */
  $munidad = (int)($num1/1000);
  $num1 = $num1 - $munidad*1000;

  /* Obtencion de centenas */
  $centena = (int)($num1/100);
  $num1 = $num1 - $centena*100;

  /* Obtencion de decenas */
  $decena = (int)($num1/10);
  $num1 = $num1 - $decena*10;

  $unidad = $num1;

  if ($millon) {
    $millones .= interp_cant($millon,0,0);
    $millones .= "millones ";
    $buffer .= $millones;
  }
  else
    $millones = "";
  if ($munidad || $mdecena || $mcentena) {
    $miles = interp_cant($munidad, $mdecena, $mcentena);
    $miles .= "mil ";
    $buffer.= $miles;
  }
  else
    $miles = "";
  if ($unidad || $decena || $centena)
    $unidades = interp_cant($unidad,$decena,$centena);
  else
    $unidades = "";
  $buffer .= $unidades;
  $cantletra = ucfirst($buffer);
  return($cantletra);
}

function Espacios($file_pointer, $num_espacios)
{
  for ($i=0; $i<$num_espacios; $i++)
    fputs($file_pointer, " ");
  return($i);
}

function CalculaIVA($numarticulos) {
  global $iva, $subtotal, $total;

  $iva = 0;
  for ($i=0; $i<$numarticulos; $i++) {
    $iva_porcentaje = $art[$i]->iva_porc / 100;
    $art[$i]->pu =  $art[$i]->pu / ($iva_porcentaje+1);
    $sumatoria += ($art[$i]->pu * $art[$i]->cant);
    $iva += $art[$i]->pu * $iva_porcentaje * $art[$i]->cant;
  }
  $subtotal = $sumatoria;
  $total = $subtotal + $iva;
  return($i);
}

?>
