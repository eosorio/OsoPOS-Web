<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
        invent_item_gral.bdy. Cuerpo del módulo de inventarios de OsoPOS Web.

        Copyright (C) 2003 Eduardo Israel Osorio Hernández

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
  include("bodies/web/invent_costos_head.bdy");
  include("include/tipo_cambio.inc");

  $query = "SELECT a.*, d.long_desc, d.img_location FROM articulos a, article_desc d WHERE a.codigo='$codigo' AND a.codigo=d.code ";
  if (!$db_res = db_query($query, $conn)) {
    echo "Error al consultar información de catálogo del producto<br>\n";
    exit();
  }
  $ren = db_fetch_object($db_res, 0);

  include("bodies/web/invent_item_gral_head.bdy");


  if (empty($DIVISA_OMISION))
    $DIVISA_OMISION = "MXP";

}
?>
