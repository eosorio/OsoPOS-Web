<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 Caja Web 0.0.2-1. Módulo de caja de OsoPOS Web.

        Copyright (C) 2000 Eduardo Israel Osorio Hernández
        iosorio@punto-deventa,com

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

{

  include("include/general_config.inc");
  include("include/caja_web.inc");
  include("include/pos-var.inc");
  include("include/pos.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }


  $id_venta = registra_venta($id_vendor, $pago, $comprobante, $total);
  $low_supp = update_supp($conn, count($articulo_descripcion));

  if (count($low_supp))
    notify_low_supplies($low_supp);

  switch ($comprobante) {
    case 2:
      $location = sprintf("Location: factur_web.php?id_venta=%d&low_supp=%d",
                          $id_venta, count(low_supp));
      header($location);
      exit;
      break;
    case 1:
    case 5:
      include("bodies/caja_web_imprime.bdy");
      break;
  }

}
