<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
{
  $PROGRAMA = "web";

  /* Type of SQL client/server. For now is postgres or mysql */
  $SQL_TYPE = "postgres";

  include("include/$SQL_TYPE.inc");

  // Seccion de bases de datos
  if ($SQL_TYPE=="postgres") {
     include("/etc/osopos/.config");
  }
  else if ($SQL_TYPE == "mysql") {
    $DB_HOST = "127.0.0.1";
    $DB_OWNER = "root";
    $DB_PASSWD = "";
    $DB_NAME = "osopos";
  }

  // Variables
  $IVA_PORCENTAJE = 10;
  $IEPS_PORCENTAJE = 5;

  // Varios

  $IMPRESORA_TIPO = "STAR";
  //  $IMPRESORA_TIPO = "EPSON";
  $TICKET_IVA_INCLUIDO = 1;

  // Factur
  //$FACTUR_IVA_INCLUIDO   = TRUE;
  $FACTUR_IVA_INCLUIDO  = FALSE;
  $FACTUR_IMPUESTO_INCLUIDO[0]  = FALSE;
  $FACTUR_IMPUESTO_INCLUIDO[1]  = FALSE;
  $FACTUR_IMPUESTO_INCLUIDO[2]  = FALSE;
  $FACTUR_IMPUESTO_INCLUIDO[3]  = FALSE;
  $FACTUR_IMPUESTO_INCLUIDO[4]  = FALSE;
  $FACTUR_IMPUESTO_INCLUIDO[5]  = FALSE;
  $DESGLOSAR_IMPUESTO[0] = TRUE;
  $DESGLOSAR_IMPUESTO[1] = TRUE;
  $DESGLOSAR_IMPUESTO[2] = FALSE;
  $DESGLOSAR_IMPUESTO[3] = TRUE;
  $DESGLOSAR_IMPUESTO[4] = FALSE;
  $DESGLOSAR_IMPUESTO[5] = FALSE;

  // Movimientos al inventario
  $MOVINV_IMPR_COND = 1;

  // Versiones
  $factur_web_vers = "0.06";
  $caja_web_vers   = "0.04";
  $osopos_web_vers = "1.19";

  // Estado por omisión
  $ESTADO_OMISION = "Chiapas";

  // Estadísticas
  $MAX_IMG_BARRAS = 5;

  $FACTURA_TIPO = "TXT";
  //$FACTURA_TIPO = "PDF";
}
?>
