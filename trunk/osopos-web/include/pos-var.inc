<?php  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */

/* códigos de la impresora */
$ESC =  27;
$FF  =  12;

if (!isset($MAXLEN_COD))
  $MAXCOD    = 20;
else
  $MAXCOD  = $MAXLEN_COD;
if (!isset($MAXLEN_DESC))
  $MAXDES    = 39;
else
  $MADES     = $MAXLEN_DESC;
$MAXRFC    = 14;
$MAXCURP   = 19;

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

class articulosClass {
  var      $cant, $exist;
  var      $desc;
  var      $codigo;
  var      $pu,                 /* Precio Unitario      */
           $p_costo;            /* Precio de cosro      */
  var      $disc;               /* Discuento   .=)      */
  var      $id_prov;            /* Id del proveedor */
  var      $id_depto;
  var      $exist_min, $exist_max;
  var      $iva_porc;          /* Gravamen de iva (int) */
};

class fechaClass {
  var      $dia,$mes,$anio;
}

?>
