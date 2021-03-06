<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 pos.inc 0.3-1. include file for osopos_web

        Copyright (C) 2000-2006 Eduardo Israel Osorio Hernández
        desarrollo@elpuntodeventa.com

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


function lee_config($db_con, $variable) {
  $query = sprintf("SELECT valor FROM configuracion WHERE llave='%s'", $variable);
  if (!$resultado = db_query($query, $db_con)) {
    return(NULL);
  }
  else {
    if (db_num_rows($resultado) == 0) {
      echo "<div class=\"error_f\">Error en argumento de configuración $variable</div>\n";
      return(NULL);
    }
    return(db_result($resultado, 0, 0));
  }
}

function registra_venta($id_vend, $art_cant, $art_descr, $art_pu, 
                        $art_iva_porc, $tipo_pago,
                        $tipo_comprobante,  $total) {

  global $user, $conn, $iva, $MXT_VENTAS_DET_DESC, $alm;

  $fecha = date("Y-m-d");
  $hora = date("H:i:s");

  /*igm*/$utilidad = $total;
  $query = "INSERT INTO ventas (monto, tipo_pago, tipo_factur, ";
  $query.= "utilidad, id_vendedor, id_cajero, fecha, hora, ";
  $query.= "iva) VALUES (";
  $query.= sprintf("%.2f, %d, %d, %.2f, %d, %d, '%s', '%s', %.2f)",
                   $total, $tipo_pago, $tipo_comprobante, $utilidad, $id_vend,
					  $user->id, $fecha, $hora, $iva);
  if (!$resultado = db_query($query, $conn)) {
    die("<div class=\"error_f\">Error al insertar venta</div>\n");
  }


  /* Se asume que el mismo cajero no puede efectuar dos cobros iguales en menos de un segundo */
  /* y si asi fuere que me hable para contratarlo .=)  */
  $query = sprintf("SELECT numero FROM ventas WHERE id_vendedor=%d AND ", $id_vend);
  $query.= sprintf("id_cajero=%d AND fecha='$fecha' AND hora='$hora'", $user->id);
  if (!$resultado = db_query($query, $conn)) {
    die("<div class=\"error_f\">Error al consultar datos de vendedor en ventas</div>\n");
  }
  $id_venta = db_result($resultado, 0, 0);

  reset($art_cant);
  while (list ($codigo, $valor) = each ($art_cant)) {
    //  for ($i=0; $i<count($articulo_descripcion); $i++) {
    $query = "INSERT INTO ventas_detalle (id_venta, codigo, cantidad, descrip, pu, iva_porc)";
    $query.= sprintf(" VALUES (%d, '%s', %.2f, '%s', %.2f, %f)", $id_venta, $codigo,
                     $art_cant[$codigo],
                     substr($art_descr[$codigo], 0, $MXT_VENTAS_DET_DESC ), 
                     $art_pu[$codigo], $art_iva_porc[$codigo]);
    if (!$resultado = db_query($query, $conn)) {
      die("<div class=\"error_f\">Error al ejecutar $query</div>\n");
    }
  }
  $descto_aplicado = checa_descuento($conn, $id_venta, $alm);
  if ($descto_aplicado)
    $corte = "00010000";
  else
    $corte = "00000000";

  $query = sprintf("INSERT INTO corte (numero,bandera) VALUES (%d, B'%s')", $id_venta, $corte);
  if (!$resultado = db_query($query, $conn)) {
    die("<div class=\"error_f\">Error al registrar corte</div>\n");
  }

  return($id_venta);
}

function update_supp($conn, $art_mcant)
{
  $low_supply = array();
  reset($art_mcant);
  while (list ($codigo, $valor) = each ($art_mcant)) {
    //  for ($i=0; $i<$num_arts; $i++) {
    $query = "SELECT descripcion, cant,min FROM articulos ";
    $query.= sprintf("WHERE codigo='%s'", $codigo);
    if (!$result = db_query($query, $conn)) {
      echo "<div class=\"error_nf\">Error al actualizar existencias.<br>Abortando actualización.</div><br>\n";
      return(0);
    }
    /* Not in database */
    if (db_num_rows($result) <= 0)
      continue;

    $art = db_fetch_object($result, 0);

    $qt_remain = $art->cant - $art_mcant[$codigo];
    if ($qt_remain < $art->min)
      $low_supply[$codigo]++;

    $query = sprintf("UPDATE articulos SET cant=%d",
                     $qt_remain);
    $query.= sprintf(" WHERE codigo='%s'", $codigo);
    if (!$result = db_query($query, $conn)) {
      printf("<div class=\error_nf\">Error al actualizar existencias de %s</div><br>\n", $codigo);
    }
  }
  return($low_supply);
}

/* Aumenta existencias en almacen */
function actualiza_inv($db_con, $almacen, $codigo, $cant)
{

  $query = sprintf("SELECT cant,c_min FROM almacen_1 ");
  $query.= sprintf("WHERE codigo='%s' AND id_alm=%d", $codigo, $almacen);

  if (!$db_res = db_query($query, $db_con)) {
    echo "<b>Error al actualizar existencias</b><br>Abortando actualización.<br>\n";
    return(0);
  }
  /* Not in database */
  if (db_num_rows($db_res) <= 0)
    return(0);

  $art = db_fetch_object($db_res, 0);

  $qt_remain = $art->cant + $cant;
  if ($qt_remain < $art->min)
      $low_supply++;

  $query = sprintf("UPDATE almacen_1 SET cant=%d ", $qt_remain);
  $query.= sprintf("WHERE codigo='%s' AND id_alm=%d", $codigo, $almacen);

  if (!$db_res = db_query($query, $db_con)) {
    printf("<b>Error al actualizar existencias de %s<br>\n",
           $codigo);
  }

  return($low_supply);
}

/* actualiza costos en catálogo de artículos */
function actualiza_costo($db_con, $codigo, $p_costo, $tipo_costeo=0)
{

  $query = sprintf("SELECT p_costo FROM articulos WHERE codigo='%s'", $codigo);

  if (!$db_res = db_query($query, $db_con)) {
    echo "<b>Error al actualizar existencias</b><br>Abortando actualización.<br>\n";
    return(0);
  }
  /* Not in database */
  if (db_num_rows($db_res) <= 0)
    return(-1);

  $costo_act = db_result($db_res, 0, 0);

  switch($tipo_costeo) {
  case 0 : /* Estandar */
    $costo_nvo = $p_costo;
    break;
  case 1: /* PEPS */
    break;
  case 2: /* UEPS */
    break;
  case 3: /* Promedio */
    break;
  }

  $query = sprintf("UPDATE articulos SET p_costo=%f ", $costo_nvo);
  $query.= sprintf("WHERE codigo='%s'", $codigo);

  if (!$db_res = db_query($query, $db_con)) {
    printf("<b>Error al actualizar precio de costo de %s<br>\n",
           $codigo);
    if (isset($debug) && $debug>0) {
      echo db_errormsg($db_con) . "<br>\n";
      echo "<i>$query</i><br>\n";
    }
  }

  return($costo_nvo);
}

function notify_low_supplies($code)
{

}

/*function LeeVenta($nombre) {
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
*/

function lee_venta($id) {

  global $conn;

  $art = array(new articulosClass);

  $query = "SELECT codigo, descrip, cantidad,pu, iva_porc FROM ventas_detalle WHERE id_venta=$id";
  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>";
    echo "No se pudo encontrar los datos de la venta<br>" . db_errormsg($conn) . "<br>\n";
  }
  $num_art = db_num_rows($resultado);
  if (!$num_art)
    return(0);

  for ($i=0; $i<$num_art; $i++) {
    $reng = db_fetch_object($resultado, $i);
    $cod = $reng->codigo;
    $art[$cod]->cant = $reng->cantidad;
    $art[$cod]->desc = $reng->descrip;
    $art[$cod]->pu = $reng->pu;
    $art[$cod]->iva_porc = $reng->iva_porc;
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

  $num1 = $arreglo[0]; /* Le damos la vuelta al sunto del punto flotante en i386 */

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

/*function Espacios($file_pointer, $num_espacios)
{
  for ($i=0; $i<$num_espacios; $i++)
    $linea.= " ";
  fputs($file_pointer, $linea);
  return($linea);
  }*/

 /*function espacio($num_espacios)
{
  for ($i=0; $i<$num_espacios; $i++)
    $linea.= " ";
  return($linea);
}
 */
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

function open_drawer($cola)
{
  global $cmd_impresion, $MINI_IMP_TIPO, $ESC;

  switch ($MINI_IMP_TIPO) {
  case "EPSON":
    $datos = sprintf("%c%c%c%c%c", $ESC,'p', 0, 255, 255);
    break;
  case "STAR":
  default:
    $datos = sprintf("%c", 7);
    break;
  }
  $comando = sprintf("%s -P %s", $cmd_impresion, $cola);

  if ( !($impresion = @popen($comando, "w")) )
    return(2);
  else {
    fputs($impresion, $datos);
    pclose($impresion);
  }
  return(0);
}

function lista_proveedores($bandera_todos, $nm_campo, $item_0, $llave = 0, $num_prov = -1) {
  global $conn;

  $a_nick = array();

  echo "<select name=\"$nm_campo\">\n";

  $p_selected = 0;

  $query = "SELECT id,nick FROM proveedores ORDER BY ";
  switch ($llave) {
  case 1:
    $query.= "nick";
    break;
  case 0:
  default:
    $query.= "id ";
  }

  if (!$db_res = db_query($query, $conn)) {
    echo db_errormsg($conn);
    return(-1);
  }
  $a_nick[-1] = $item_0;
  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $r = db_fetch_object($db_res, $i);
    $a_nick[$r->id] = $r->nick;
  }

  reset($a_nick);
  foreach ($a_nick as $i => $nm_prov) {
    echo "   <option";
    if ($num_prov == $i)
      echo " selected";
    echo " value=\"$i\">$nm_prov</option>\n";
  }

  if ($bandera_todos)
    echo "<option value=\"-1\">Todos</option>\n";
  echo "</select>\n";
}

function lista_movimientos($db_con, $item_default, $nm_campo="tipo_mov[]") {
  global $MOVINV_COMPRA, $MOVINV_COMPRA, $MOVINV_DEVVENTA, $MOVINV_DEVCOMPRA, 
    $MOVINV_MERMA, $MOVINV_TSALIDA, $MOVINV_TENTRADA, $nm_tipo_mov, $user;

  if (!is_array($tipo_mov)) {
    $query = "SELECT nombre FROM tipo_mov_inv ORDER BY id ASC ";

    if (!$db_res = db_query($query, $db_con)) {
      die("<div class=\"error_f\">Error al consultar tipo de movimientos de inventario</div>\n");
    }

    $nm_tipo_mov = array();

    for ($i=0; $i<db_num_rows($db_res); $i++)
      $nm_tipo_mov[$i+1] = db_result($db_res, $i, 0);
  }

  printf("<select name=\"%s\">\n", $nm_campo);



  for ($i=1; $i<=count($nm_tipo_mov); $i++) {
    if ($i==$MOVINV_VENTA && !puede_hacer($db_con, $user->user, "movinv_venta"))
      continue;
    else
    if ($i==$MOVINV_COMPRA && !puede_hacer($db_con, $user->user, "movinv_compra"))
      continue;
    else
    if ($i==$MOVINV_DEVVENTA && !puede_hacer($db_con, $user->user, "movinv_devventa"))
      continue;
    else
    if ($i==$MOVINV_DEVCOMPRA && !puede_hacer($db_con, $user->user, "movinv_devcompra"))
      continue;
    else
    if ($i==$MOVINV_MERMA && !puede_hacer($db_con, $user->user, "movinv_merma"))
      continue;
    else
    if ($i==$MOVINV_TSALIDA && !puede_hacer($db_con, $user->user, "movinv_tsalida"))
      continue;
    else
    if ($i==$MOVINV_TENTRADA && !puede_hacer($db_con, $user->user, "movinv_tentrada"))
      continue;
    else {
      echo "   <option ";
      if ($i == $item_default)
        printf("selected ");
      printf(" value=%d>%s\n", $i, $nm_tipo_mov[$i]);
    }
  }
  echo "</select>\n";
}

function tipo_mov($db_con, $num_tipo_mov) {

  $query = "SELECT nombre FROM tipo_mov_inv WHERE id=$num_tipo_mov ";

  if (!$db_res = db_query($query, $db_con)) {
    die("<div class=\"error_f\">Error al consultar tipo de movimientos de inventario</div>\n");
  }

  return(db_result($db_res, 0, 0));
}


function inserta_cab_movinv($db_con, $almacen, $tipo_mov, $id_prov1, $usuario, $tiempo=0) {
  $fecha = date("Y-m-d");
  $hora = date("H:i:s");
  
  $query = "INSERT INTO mov_inv (almacen, tipo_mov, usuario, fecha_hora, id_prov1) VALUES ";
  $query.= sprintf("(%d, %d, '%s', '%s %s', %d)", $almacen, $tipo_mov, $usuario, $fecha, $hora, $id_prov1);
  if (!$db_res = db_query($query, $db_con)) {
    die("<div class=\"error_f\">Error al insertar movimientos al inventario</div>\n");
  }

  $query = sprintf("SELECT id FROM mov_inv WHERE almacen=%d AND usuario='%s' AND fecha_hora='%s %s' ",
                   $almacen, $usuario, $fecha, $hora);
  if (!$db_res = db_query($query, $db_con)) {
    die("<div class=\"error_f\">Error al consultar modvimientos al inventario</div>\n");
  }
  if (db_num_rows($db_res))
    return(db_result($db_res, 0, 0));
  else
    return(-1);
}


function inserta_det_movinv($db_con, $almacen, $id, $codigo, $cant, $pu, $p_costo, $alm_dest, $tipo_mov) {
  $query = sprintf("INSERT INTO mov_inv_detalle VALUES (%d, '%s', %f, %f, %f, %d) ",
                   $id, $codigo, $cant, $pu, $p_costo, $alm_dest);


  if (!$db_res = db_query($query, $db_con)) {
    die("<div class=\"error_f\">Error al insertar detalle de movimientos al inventario</div>\n");
  }

  $query = "SELECT entrada FROM tipo_mov_inv WHERE id=$tipo_mov";

  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al consultar tipos de movimientos. Existencia y costo no actualizados<br>\n";
  }
  else {

    if (db_result($db_res, 0, 0) == 'f')
      $cant = -$cant;
    actualiza_inv($db_con, $almacen, $codigo, $cant);
    if ($tipo_mov == 2) { /* Compra */
      actualiza_costo($db_con, $codigo, $p_costo);
    } else if ($tipo_mov == 6  && $alm_dest>0) { /* Transferencia salida */
      $cant = -$cant;
      actualiza_inv($db_con, $alm_dest, $codigo, $cant);
      actualiza_costo($db_con, $codigo, $p_costo);
    }
  }
}


function inserta_en_almacen($db_con, $almacen, $codigo) {
  global $debug;

  $query = "INSERT INTO almacen_1 (codigo, divisa, ";
  $query.= "tax_0, tax_1, tax_2, tax_3, tax_4, tax_5, id_alm) ";
  $query.= "(SELECT codigo, divisa, tax_0, tax_1, tax_2, tax_3, tax_4, tax_5, ";
  $query.= "$almacen AS id_alm FROM articulos WHERE codigo='$codigo')";

  if ($debug>0)
    echo "<i>$query</i><b>\n";
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al importar artículo del catálogo<br>\n";
    db_errormsg($db_res);
    return(-1);
  }
  return(1);
}

function puede_hacer($db_con, $usuario, $accion) {
  global $debug;

  $query = "SELECT mod_usr.usuario FROM modulo, modulo_usuarios mod_usr WHERE modulo.id=mod_usr.id ";
  $query.= sprintf("AND modulo.nombre='%s' AND usuario='%s' ", $accion, $usuario);
  if ($debug>2)
    echo "<i>puede_hacer</i> $query<br>\n";
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al consultar permisos del usuario<br>\n";
    db_error($db_res);
    return(-1);
  }
  return(db_num_rows($db_res));
}

function articulo_descripcion($db_con, $codigo) {
  $query = "SELECT descripcion FROM articulos WHERE codigo='$codigo'";
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al leer descripción del artículo<br>\n";
    db_errormsg($db_res);
    return(-1);
  }
  return(db_result($db_res, 0, 0));
}

function imprime_movimiento($db_con, $almacen, $id_mov, $codigo, $ct, $pu, $p_costo,
                            $alm_dest, $tipo_mov, $cola_impresion) {
  global $user;

  $cmd_impresion = lee_config($db_con, "CMD_IMPRESION");

  if ($cola_impresion == "")
    $cola_impresion = lee_config($db_con, "COLA_DEFAULT");

  $query = "SELECT ";

  $linea = sprintf("\n  REPORTE DE MOVIMIENTO AL INVENTARIO\n\n");
  $linea.= sprintf("Movimiento %d Fecha: %s\n", $id_mov, date("d/m/Y H:i:s"));
  $linea.= sprintf("Tipo de movimiento: %d (%s)\n", $tipo_mov, tipo_mov($db_con, $tipo_mov));
  $linea.= sprintf("Almacen: %d\n", $almacen);
  $linea.= sprintf("Usuario: %s\n\n", $user->user);


  for ($i=0; $i<count($codigo) && !empty($codigo[$i]); $i++) {
    $linea.= sprintf("Código: %s, %s\n", $codigo[$i], 
                     substr(articulo_descripcion($db_con, $codigo[$i]), 0, 15));
    $linea.= sprintf("Cantidad: %.2f\nP.U.: %.2f, P. Costo: %.2f\n", $ct[$i], $pu[$i], $p_costo[$i]);
    $linea.= sprintf("Almacen destino: %d\n", $alm_dest[$i]);
    $linea.= sprintf("-----------------------------------\n");

  }
  $linea.= "******Fin de movimiento******\n";
  $comando = sprintf("%s -P %s "  , $cmd_impresion, $cola_impresion);

  $ar  = popen($comando, "w");
  if ($ar<=0)
    echo "Error: No puedo imprimir<br>";
  fputs($ar, $linea);
  pclose($ar);
  
}


function checklist_almacen($db_con)
{
  $query = "SELECT id FROM almacenes ORDER BY id ASC";
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al leer listado del almacenes<br>\n";
    db_errormsg($db_res);
    return(-1);
  }
  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $almac = db_result($db_res, $i, 0);
    printf("<input type=\"checkbox\" name=\"almac[]\" value=\"%d\"> %d<br>\n", $almac, $almac);
  }
}

function lista_almacen($db_con, $nm_lista, $item_0, $alm_def=1, $opciones="")
{
  $query = "SELECT id,nombre FROM almacenes";
  if (!$db_res = db_query($query, $db_con)) {
    printf("Error al consultar almacenes<br>\n");
    printf("Error: %s<br>\n",db_errormsg($db_con));
    return($DB_ERROR);
  }

  $buf = sprintf("<select name=\"%s\" %s>\n", $nm_lista, $opciones);
  $buf.= "<option value=0>$item_0\n";
  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $almacen = db_fetch_object($db_res, $i);
    $buf.= sprintf("<option value=\"%d\" ", $almacen->id);
    if ($almacen->id == $alm_def)
      $buf.= " selected ";
    $buf.= sprintf(">%d. %s\n", $almacen->id, $almacen->nombre);
  }
  $buf.= "</select>\n";
  return($buf);
}

function lista_precio($db_con, $codigo, $tipo_precio, $almacen=1)
{
  /* tipo_precio= 0:costo, 1:p. público 1, 2:p. pub. 1, etc. */ 
  global $debug;
  $sub_q = "";

  switch($tipo_precio) {
  case 0:
    $campo = "p_costo";
    $tabla = "articulos";
    break;
  case 1:
    $campo = "pu";
    $tabla = sprintf("almacen_1");
    break;
  case 2:
  case 3:
  case 4:
  case 5:
    $campo = sprintf("pu%d", $tipo_precio);
    $tabla = sprintf("almacen_1");
    break;

  }
  $query = sprintf("SELECT %s,codigo FROM %s WHERE ", $campo, $tabla);
  if (is_array($codigo)) {
    while (list($item,$cod) = each($codigo)) {
      if (strlen($sub_q)>0)
        $sub_q.= "OR ";
      $sub_q .= "codigo='$cod' ";
    }
    $query.= $sub_q;
  }
  else
    $query.= "codigo='$codigo' ";
  if ($tipo_precio>0)
    $query.= sprintf("AND id_alm=%d ", $almacen);

  if ($debug>0)
    echo "<i>$query</i><br>\n";
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al leer precio<br>\n";
    db_errormsg($db_res);
    return(-1);
  }
  $num_rows = db_num_rows($db_res);

  $precio = array();
  for ($i=0; $i<$num_rows; $i++) {
    $precio[db_result($db_res, $i, 1)] = db_result($db_res, $i, 0);
  }

  return($precio);
}

function nombre_depto($db_con, $id_depto) {
  $query = sprintf("SELECT nombre FROM departamento WHERE id=%d", $id_depto);
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al leer base de datos<br>\n";
    db_errormsg($db_res);
    return(-1);
  }  
  return(db_result($db_res, 0, 0));
}

function nombre_almacen($db_con, $id_almacen=1) {
  $query = sprintf("SELECT nombre FROM almacenes WHERE id=%d", $id_almacen);
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al leer base de datos<br>\n";
    db_errormsg($db_res);
    return(-1);
  }  
  return(db_result($db_res, 0, 0));
}

function lista_campo($db_con, $codigo, $campo, $tabla)
{

  global $debug;

  $query = sprintf("SELECT %s,codigo FROM %s WHERE ", $campo, $tabla);
  if (is_array($codigo)) {
    while (list($item,$cod) = each($codigo)) {
      if (strlen($sub_q)>0)
        $sub_q.= "OR ";
      $sub_q .= "codigo='$cod' ";
    }
    $query.= $sub_q;
  }
  else
    $query.= "codigo='$codigo' ";

  if ($debug>0)
    echo "<i>$query</i><br>\n";
  if (!$db_res = db_query($query, $db_con)) {
    echo "Error al leer base de datos<br>\n";
    db_errormsg($db_res);
    return(-1);
  }
  $num_rows = db_num_rows($db_res);

  $tupla = array();
  for ($i=0; $i<$num_rows; $i++) {
    $tupla[db_result($db_res, $i, 1)] = db_result($db_res, $i, 0);
  }

  return($tupla);
}

function checa_descuento($db_con, $num_venta, $almacen)
{

  global $DB_ERROR;

  $query = sprintf("SELECT d.id_venta AS id, d.codigo, d.pu AS venta, a.pu AS precio FROM ventas_detalle d, almacen_1 a WHERE d.id_venta=%d AND d.codigo=a.codigo AND d.pu!=a.pu AND a.id_alm=%d", $num_venta, $almacen);

  if (!$db_res = db_query($query, $db_con)) {
    printf("Error al consultar ventas<br>\n");
    printf("Error: %s<br>\n",db_errormsg($db_con));
    return($DB_ERROR);
  }
  return(db_num_rows($db_res));
}

function lista_divisas($db_con, $divisa_def) 
{
  $query = "SELECT id FROM divisas";
  if (!$db_res = db_query($query, $db_con)) {
    printf("Error al consultar divisas<br>\n");
    printf("Error: %s<br>\n",db_errormsg($db_con));
    return($DB_ERROR);
  }

  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $divisa = db_fetch_object($db_res, $i);
    $buf.= sprintf("<option value=\"%s\" ", $divisa->id);
    if ($divisa->id == $divisa_def)
      $buf.= " selected ";
    $buf.= sprintf(">%s\n", $divisa->id);
  }
  return($buf);
}

function valor_divisas($db_con, $divisa_def="") {
  if (strlen($divisa_def)) {
    $query = "SELECT tipo_cambio FROM divisas WHERE id='$divisa_def'";
    if (!$db_res = db_query($query, $db_con)) {
      return(-1);
    }
    if (db_num_rows($result) <= 0)
      return(-1);
    return(db_result($db_res, 0, 0));
  }
  else {
    $tipo_cambio = array();
    $query = "SELECT id,tipo_cambio FROM divisas ";
    if (!$db_res = db_query($query, $db_con)) {
      return(-1);
    }
    for ($i=0; $i<db_num_rows($db_res); $i++) {
      $ren = db_fetch_object($db_res, $i);
      $tipo_cambio[$ren->id] = $ren->tipo_cambio;
    }
    return($tipo_cambio);
  }
}

function busca_codigo($db_con, $codigo, $almacen)
{
  if ($almacen>0)
    $query = sprintf("SELECT * FROM almacen_1 WHERE codigo='%s' AND id_alm=%d", $codigo, $almacen);
  else
    $query = sprintf("SELECT * FROM articulos WHERE codigo='%s'", $codigo);

  if (!$db_res = db_query($query, $db_con))
    return($DB_ERROR);

  return(db_num_rows($db_res));
}

function lista_forma_pago($db_con, $nm_lista, $item_0)
{
  $query = "SELECT id,descripcion FROM forma_pago ";
  if (!$db_res = db_query($query, $db_con)) {
    printf("Error al consultar formas de pago<br>\n");
    printf("Error: %s<br>\n",db_errormsg($db_con));
    return($DB_ERROR);
  }

  $buf = "<select name=\"$nm_lista\">\n";
  $buf.= "<option value=0>$item_0\n";
  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $f_pago = db_fetch_object($db_res, $i);
    $buf.= sprintf("<option value=\"%d\" ", $f_pago->id);
    $buf.= sprintf(">%d. %s\n", $f_pago->id, $f_pago->descripcion);
  }
  $buf.= "</select>\n";
  return($buf);
}

function checklist_forma_pago($db_con, $nm_lista)
{
  $query = "SELECT id,descripcion FROM forma_pago ";
  if (!$db_res = db_query($query, $db_con)) {
    printf("Error al consultar formas de pago<br>\n");
    printf("Error: %s<br>\n",db_errormsg($db_con));
    return($DB_ERROR);
  }

  for ($i=0; $i<db_num_rows($db_res); $i++) {
    $f_pago = db_fetch_object($db_res, $i);
    $buf.= sprintf("<input type=\"checkbox\" name=\"%s[]\" ", $nm_lista);
    $buf.= sprintf("value=\"%d\"> %s\n", $f_pago->id, $f_pago->descripcion);
  }
  return($buf);
}

function revisa_series($db_con, $id_mov) {

  $query = "SELECT m.codigo, m.cant FROM mov_inv_detalle m, articulos a ";
  $query.= sprintf("WHERE m.id=%d AND m.codigo=a.codigo AND a.serie='t' ", $id_mov);

  if (!$db_res = db_query($query, $db_con)) {
    printf("Error al consultar movimientos de inventario<br>\n");
    printf("Error: %s<br>\n",db_errormsg($db_con));
    return($DB_ERROR);
  }
  else {
    $num_ren = db_num_rows($db_res);
    $a = array();
    for ($i=0; $i<$num_ren; $i++) {
      $r = db_fetch_object($db_res, $i);
      $a[$r->codigo] = $r->cant;
    }
    if ($num_ren > 0)
      return($a);
  }
  return(0);
}

function datos_cliente($db_con, $id) {
  global $DB_ERROR;

  $query = "SELECT * FROM cliente WHERE id=$id ";

  if (!$db_res = db_query($query, $db_con)) {
    printf("Error al consultar formas de pago<br>\n");
    printf("Error: %s<br>\n",db_errormsg($db_con));
    return($DB_ERROR);
  }

  return(db_fetch_object($db_res, 0));
}

function agrega_carrito_item($db_con, $codigo, $qt=1) {
  global $DB_ERROR, $user, $debug;

  $query = sprintf("SELECT cant FROM carro_virtual WHERE usuario='%s' AND codigo='%s' ",
				   $user->user, $codigo);
  if ($debug)
	echo "<i>$query</i><br>\n";
  if (!$db_res = @db_query($query, $db_con))
    return($DB_ERROR);
  if (db_num_rows($db_res)) {
	$qt+= db_result($db_res, 0, 0);
	$query = sprintf("UPDATE carro_virtual SET cant=%f WHERE usuario='%s' AND codigo='%s'",
					 $qt, $user->user, $codigo);
  }
  else {
	$query = "INSERT INTO carro_virtual (usuario, codigo, cant) ";
	$query.= sprintf("VALUES ('%s', '%s', %f) ", $user->user, $codigo, $qt);
  }
  if ($debug)
	echo "<i>$query</i><br>\n";
  if (!$db_res = @db_query($query, $db_con))
    return($DB_ERROR);
  return(0);
}

function quita_carrito_item($db_con, $codigo) {
  global $DB_ERROR, $user, $debug;

  $query = sprintf("DELETE FROM carro_virtual WHERE usuario='%s' AND codigo='%s' ",
				   $user->user, $codigo);
  if ($debug)
	echo "<i>$query</i><br>\n";
  if (!$db_res = @db_query($query, $db_con))
    return($DB_ERROR);
  return(0);
}

function cambia_carrito($db_con, $items) {
  global $DB_ERROR, $user, $debug;

  $errores = array();

  while (list ($codigo, $cant) = each($items)) {
	$query = sprintf("UPDATE carro_virtual SET cant=%d WHERE usuario='%s' AND codigo='%s'",
					 $cant, $user->user, $codigo);
	if ($debug)
	  echo "<i>$query</i><br>\n";
	if (!$db_res = @db_query($query, $db_con))
	  $errores[] = $codigo;
  }
  return($errores);
}

function vacia_carrito($db_con) {
  global $DB_ERROR, $user;

    $query = sprintf("DELETE FROM carro_virtual WHERE usuario='%s' ", $user->user);
    if (!$db_res = db_query($query, $db_con))
	  return($DB_ERROR);

	return(0);
}

function lee_carrito($db_con) {
  global $DB_ERROR,$user;

  $query = sprintf("SELECT * FROM carro_virtual WHERE usuario='%s'", $user->user);
  if (!$db_res = db_query($query, $db_con))
    return($DB_ERROR);

  $osopos_carrito = array();

  for ($i=0; $i < db_num_rows($db_res); $i++) {
    $ren = db_fetch_object($db_res, $i);
    $osopos_carrito[ $ren->codigo ] = $ren->cant;
  }
  return($osopos_carrito);
}

function lista_tipo_cliente($db_con) {
  $query = "SELECT * FROM cliente_tipo ORDER BY id ASC";
  if (!$resultado = db_query($query, $db_con))
    $mens = "<div class=\"error_nf\">Error al consultar tipos de clientes</div>\n";
  else {
    $a_clientes_tipo = array();
    $res_maxr = db_num_rows($resultado);

    for ($i=0; $i < $res_maxr; $i++) {
      $ren = db_fetch_object($resultado, $i);
      $a_clientes_tipo[$ren->id] = $ren->tipo;
    }
  }
  return($a_clientes_tipo);
}

function lista_domicilio_estados($db_con) {
  $query = "SELECT * FROM domicilio_estados ORDER BY id ASC";
  if (!$resultado = db_query($query, $db_con)) {
    $mens = "<div class=\"error_nf\">Error al consultar estados (domicilios)</div>\n";
  }
  else {
    $a_estados = array();
    $res_maxr = db_num_rows($resultado);

    for ($i=0; $i < $res_maxr; $i++) {
      $ren = db_fetch_object($resultado, $i);
      $a_estados[$ren->id] = $ren->nombre;
    }
  }
  return($a_estados);
}

function lista_domicilio_paises($db_con) {
  $query = "SELECT * FROM domicilio_paises ORDER BY id ASC";
  if (!$resultado = db_query($query, $db_con)) {
    $mens = "<div class=\"error_nf\">Error al consultar países</div>\n";
  }
  else {
    $a_paises = array();
    $res_maxr = db_num_rows($resultado);

    for ($i=0; $i < $res_maxr; $i++) {
      $ren = db_fetch_object($resultado, $i);
      $a_paises[$ren->id] = $ren->nombre;
    }
  }
  return($a_paises);
}

function getDomicilioEstadosID($db_con, $sEstado) {
  $query = "SELECT id FROM domicilio_estados WHERE nombre='$sEstado'";

  if (!$resultado = db_query($query, $db_con)) {
    return(NULL);
  }
  else
    return(db_result($resultado, 0, 0));
}


function readInvoiceHeader($db_con, $id) {

  $query = sprintf("SELECT * FROM facturas_ingresos WHERE id = %d", $id);

  if (!$resultado = db_query($query, $db_con)) {
    $mens = "<div class=\"error_nf\">Error al consultar facturas</div>\n";
    return(0);
  }
  else {
    $data =  db_fetch_object($resultado, 0);
    return($data);
  }
}

function readInvoiceItems($db_con, $id) {

  $data = array();
  $query = sprintf("SELECT * FROM fact_ingresos_detalle WHERE id_factura = %d", $id);
  if (!$resultado = db_query($query, $db_con)) {
    $mens = "<div class=\"error_nf\">Error al consultar facturas</div>\n";
    return(0);
  }
  else {
    $rows = db_num_rows($resultado);
    for ($i=0; $i<$rows; $i++)
      $data[] =  db_fetch_object($resultado, $i);
    return($data);
  }
}

function readInvoiceNotes($db_con, $id) {

  $query = sprintf("SELECT observaciones FROM factura_ingresos_obs WHERE id = %d", $id);
  if (!$resultado = db_query($query, $db_con)) {
    $mens = "<div class=\"error_nf\">Error al consultar observaciones de facturas</div>\n";
    return(0);
  }
  else {
    $rows = db_num_rows($resultado);
    for ($i=0; $i<$rows; $i++)
      $data =  db_result($resultado, 0, 0);
    return($data);
  }
}

?>