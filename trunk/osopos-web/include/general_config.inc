<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
{

  /* Type of SQL client/server. For now is postgres or mysql */
  //  $SQL_TYPE = "mysql";
  $SQL_TYPE = "postgres";

  include("include/$SQL_TYPE.inc");

  // Seccion de bases de datos
  $DB_OWNER    =  "linucs";
  $DB_NAME     =  "linucs";
  //$DB_OWNER    =  "scaja";
  //$DB_NAME     =  "osopos";
  $DB_HOST     =  "localhost";

  // Seccion de archivos y directorios
  $TMP_DIR     =  "/tmp";

  // Variables
  $IVA_PORCENTAJE = 15;

  // Varios
  if (!isset($CMD_IMPRESION)) {
    $CMD_IMPRESION = "lpr -Fb ";
  }

  $IMPRESORA_TIPO = "STAR";
  //  $IMPRESORA_TIPO = "EPSON";
  $TICKET_IVA_INCLUIDO = 1;

  // Factur
  //$FACTUR_IVA_INCLUIDO   = TRUE;
  $FACTURA_IVA_INCLUIDO  = FALSE;

  // Versiones
  $factur_web_vers = "0.06";
  $caja_web_vers   = "0.03";

  // Estado por omisión
  $ESTADO_OMISION = "Chiapas";
}
?>
