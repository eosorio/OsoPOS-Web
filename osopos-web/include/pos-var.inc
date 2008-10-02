<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- */

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
$MOVINV_FISICO      = 8;

/* Constantes de tipos de comprobantes */
$TCOMP_NOTA   = 1;
$TCOMP_FACTUR = 3;
$TCOMP_TICKET = 5;

/* Constantes de formas de pago */
$FPAGO_TARJETA  =  1;
$FPAGO_CREDITO  =  2;
$FPAGO_EFECT    = 20;
$FPAGO_CHEQUE    = 21;

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
  public      $id = 0;
  public      $rfc = "";
  public      $curp = "";
  public      $nombre = "";
  public      $ap_paterno;
  public      $ap_materno;
  public      $sexo;
  public      $dom_id;
  public      $email;
  public      $dom_calle = "";
  public      $dom_numero = "";
  public      $dom_inter = "";
  public      $dom_col = "";
  public      $dom_ciudad = "";
  public      $dom_edo = "";
  public      $cp = 0;

  function getIdByRfc($rfc) {

    global $conn;

    $query = "SELECT id FROM clientes WHERE rfc='$rfc'";

    if (!$result = db_query($query, $conn)) {
      return(NULL);
    }
    else {
      if (db_num_rows($result) == 0) {
        return(NULL);
      }
      return(db_result($result, 0, 0));
    }
  }

  function setIdByRfc($rfc) {
    $this->id = $this->getIdByRfc($rfc);
  }

  function setDataById($id) {
    global $conn;

    $query = "SELECT * FROM clientes WHERE id='$id'";

    if (!$result = db_query($query, $conn)) {
      return(NULL);
    }
    else {
      if (db_num_rows($result) == 0) {
        return(NULL);
      }
      $customer = db_fetch_object($result, 0);

      $this->rfc = $customer->rfc;
      $this->nombre = $customer->nombres;
      $this->ap_paterno = $customer->ap_paterno;
      $this->ap_materno = $customer->ap_materno;
      $this->dom_id  = $customer->dom_principal;
      $this->email = $customer->email;
      $this->curp = $customer->curp;
      $this->sexo = $customer->sexo;
      $this->telefono1 = $customer->telefono1;
    }

  }
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
  public $dia = 0;
  public $mes = 0;
  public $anio = 0;
};

/* Propiedades de formato de hoja en impresiones de texto plano */
class hojaClass {
  var     $max_ren;
  var     $max_col;
  var     $max_col_cond;
};

?>
