<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
        invent_item_gral.bdy. Cuerpo del m�dulo de inventarios de OsoPOS Web.

        Copyright (C) 2003 Eduardo Israel Osorio Hern�ndez

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los t�rminos de la Licencia P�blica General GNU
publicada por la Free Software Foundation: ya sea en la versi�n 2 de la
Licencia, o (a su elecci�n) en una versi�n posterior. 

        Este programa es distribuido con la esperanza de que sea �til, pero
SIN GARANTIA ALGUNA; incluso sin la garant�a impl�cita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. V�ase la Licencia P�blica General
GNU para mayores detalles. 

        Deber�a usted haber recibido una copia de la Licencia P�blica General
GNU junto con este programa; de no ser as�, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 

*/
{
  include("bodies/web/invent_costos_head.bdy");
  include("include/tipo_cambio.inc");

  $query = "SELECT a.*, d.long_desc, d.img_location FROM articulos a, article_desc d WHERE a.codigo='$codigo' AND a.codigo=d.code ";
  if (!$db_res = db_query($query, $conn)) {
    echo "Error al consultar informaci�n de cat�logo del producto<br>\n";
    exit();
  }
  $ren = db_fetch_object($db_res, 0);

  include("bodies/web/invent_item_gral_head.bdy");


  if (empty($DIVISA_OMISION))
    $DIVISA_OMISION = "MXP";

}
?>
