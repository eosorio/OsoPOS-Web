<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
{

  // Seccion de Postgres
  $DB_OWNER    =  "linucs";
  $DB_NAME     =  "linucs";

  // Seccion de archivos y directorios
  $TMP_DIR     =  "/tmp";

  // Variables
  $IVA_PORCENTAJE = 15;

  // Varios
  if (!isset($CMD_IMPRESION)) {
    $CMD_IMPRESION = "lpr -Fb ";
  }

  $IMPRESORA_TIPO = "STAR";
  $TICKET_IVA_INCLUIDO = 1;

  // Versiones
  $factur_web_vers = "0.05";
  $caja_web_vers   = "0.03";

}
?>
