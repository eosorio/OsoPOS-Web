<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
{

  // Seccion de Postgres
  $DB_OWNER    =  "israel";
  $DB_NAME     =  "osopos";

  // Seccion de archivos y directorios
  $TMP_DIR     =  "/tmp";

  // Variables
  $IVA_PORCENTAJE = 10;

  // Varios
  if (!isset($CMD_IMPRESION)) {
    $CMD_IMPRESION = "lpr -Fb -P";
  }

  $IMPRESORA_TIPO = "EPSON";
  $TICKET_IVA_INCLUIDO = 1;

  // Factur
  $FACTUR_IVA_INCLUIDO   = TRUE;
  $LP_PRINTER            = "lp";
  $AGREGA_CLIENTES       =  TRUE;
  $OBS_DEFAULT           = " dias de garantia.";

  // Versiones
  $factur_web_vers = "0.06";
  $caja_web_vers   = "0.03";

}
?>
