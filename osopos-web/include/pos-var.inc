<?php  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */

$DB_ERROR = -2;

/* códigos de la impresora */
$ESC =  27;
$FF  =  12;

/* constantes de movimiento al inventario */
$MOVINV_VENTA       = 1;
$MOVINV_COMPRA      = 2;
$MOVINV_DEVVENTA    = 3;
$MOVINV_DEVCOMPRA   = 4;
$MOVINV_MERMA       = 5;
$MOVINV_TSALIDA     = 6;
$MOVINV_TENTRADA    = 7;

/* Constantes de tipos de comprobantes */
$TCOMP_NOTA   = 1;
$TCOMP_FACTUR = 3;
$TCOMP_TICKET = 5;

if (!isset($MAXLEN_COD))
  $MAXCOD    = 20;
else
  $MAXCOD  = $MAXLEN_COD;
if (!isset($MAXLEN_DESC))
  $MAXDES    = 35;
else
  $MAXDES     = $MAXLEN_DESC;
$MAXRFC    = 14;
$MAXCURP   = 19;

$MXT_VENTAS_DET_DESC = 40;
if (!isset($MAXTAX))
  $MAXTAX = 6;

class datosclienteClass {
  var      $rfc;
  var      $curp;
  var      $nombre;
  var      $dom_calle;
  var      $dom_numero;
  var      $dom_inter;
  var      $dom_col;
  var      $dom_ciudad;
  var      $dom_edo;
  var      $cp;
};

/*************************** OJO ***********************/
/* Revisar esta clase de acuerdo al nuevo esquema de las tablas almacen_n y artículos */
class articulosClass {
  var      $cant, $exist;
  var      $desc;
  var      $codigo;
  var      $pu,                 /* Precio Unitario      */
           $pu2, $pu3, $pu4, $pu5,
           $p_costo;            /* Precio de costo      */
  var      $disc;               /* Discuento   .=)      */
  var      $id_prov;            /* Id del proveedor */
  var      $id_depto;
  var      $exist_min, $exist_max;
  var      $iva_porc;          /* Gravamen de iva  */
  var      $tax_0;             /* Porcentaje de I.E.P.S. */
  var      $tax_1;             /* Porcentaje de impuesto 1 */
  var      $tax_2;
  var      $tax_3;
  var      $tax_4;
  var      $tax_5;
};

class fechaClass {
  var      $dia,$mes,$anio;
}

?>
