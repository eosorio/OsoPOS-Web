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

  $query = "SELECT a.* FROM articulos a WHERE codigo='$codigo' ";
  if (!$db_res = db_query($query, $conn)) {
    echo "Error al consultar información de catalogo del producto<br>\n";
    exit();
  }
  $ren = db_fetch_object($db_res, 0);
  include("bodies/web/invent_item_gral_head.bdy");


  if (empty($DIVISA_OMISION))
    $DIVISA_OMISION = "MXP";

  $actualizacion = sprintf("%s %s", date("Y-m-d"), date("H:i:s"));
  if ($subaction=="agrega_pcosto") {
    $query = "INSERT INTO articulos_costos (codigo, id_prov, costo1, costo2, iva_porc, entrega1, ";
    $query.= "prov_clave, costo_envio1, actualizacion, divisa, divisa_envio) VALUES ";
    $query.= sprintf("('%s', %d, %f, %f, %f, %d, ", $codigo, $id_prov, $costo1,  $costo1, $iva_porc, $entrega1);
    $query.= sprintf("'%s', %f, '%s', '%s', '%s') ", $prov_clave, $costo_envio1, $actualizacion, $divisa, $divisa_env);

    if (!$db_res = db_query($query, $conn)) {
      echo "Error al registrar información del producto<br>\n";
      exit();
    }
    else
      printf("<b>Art&iacute;culo <i>%s</i> actualizado.</b><br>\n", $codigo);
    $subaction="";
    unset($subaction);
  }
  else if ($subaction == "actualiza_pcosto") {
    $query = sprintf("UPDATE articulos_costos SET costo1=%f, iva_porc=%f, entrega1=%d, ",
                     $costo1, $iva_porc, $entrega);
    $query.= sprintf("prov_clave='%s', costo_envio1=%f, actualizacion='%s', divisa='%s', status='%s', ",
                     $prov_clave, $costo_envio1, $actualizacion, $divisa, $status);
    $query.= sprintf("divisa_envio='%s' WHERE (codigo='%s' AND id_prov=%d) ", $divisa_env, $codigo, $id_prov);

    if (!$db_res = db_query($query, $conn)) {
      echo "Error al actualizar costo del producto<br>\n";
      exit();
    }
    else
      printf("<b>Art&iacute;culo <i>%s</i> actualizado.</b><br>\n", $codigo);
    $subaction = "";
    unset($subaction);
  }
  else if ($subaction == "borra_pcosto") {
    $query = sprintf("DELETE FROM articulos_costos WHERE codigo='%s' AND id_prov=%d AND actualizacion='%s' ",
                     $codigo, $id_prov, $act);

    if (!$db_res = db_query($query, $conn)) {
      echo "Error al actualizar costo del producto<br>\n";
      exit();
    }
    else
      printf("<b>Costo de art&iacute;culo <i>%s</i> eliminado.</b><br>\n", $codigo);

    $subaction = "";
    unset($subaction);
  }
  else if ($subaction == "cambia_pcosto") {
    include("bodies/web/invent_item_gral_cambia.bdy");
  }
  else if (!isset($subaction)) {
?>
<table border="0">
<colgroup>
  <col width="25" span="2"><col width="200"><col width="100"><col width="50"><col width="40">
  <col width="95"><col width="80"><col width="200"><col width="40">
</colgroup>
<tr>
  <th>&nbsp;</th><th>&nbsp;</th><th>Proveedor</th><th>Costo (MXP)</th><th>% IVA</th>
  <th>Tiempo entrega</th><th>Clave prov.</th><th>Costo envío</th><th>Última act.</th><th>Est.</th>
</tr>
<?php

  $query = "SELECT codigo, id_prov, costo1, iva_porc, entrega1, prov_clave, costo_envio1, ";
  $query.= "actualizacion, status, divisa, divisa_envio FROM articulos_costos WHERE codigo='$codigo' ";
  $query.= "ORDER BY costo1 ASC ";
  if (!$db_res = db_query($query, $conn)) {
      echo "Error al consultar información del producto<br>\n";
      exit();
  }
  $num_ren = db_num_rows($db_res);
  for ($i=0; $i<$num_ren; $i++) {
 
    if (!($i%4) || $i==0)
      $td_fondo = " bgcolor='#dcffdb'";
    else if (!(($i+2)%2))
      $td_fondo = " bgcolor='#fdffd3'";
    else
      $td_fondo = "";

    echo "<tr>\n";

    $ren = db_fetch_object($db_res, $i);

    $hidden_vars = sprintf("   <input type=\"hidden\" name=\"codigo\" value=\"%s\">\n", $codigo);
    $hidden_vars.= sprintf("   <input type=\"hidden\" name=\"id_depto\" value=\"%d\">\n", $id_depto);

    $hidden_rvars = sprintf("   <input type=\"hidden\" name=\"id_prov\" value=\"%s\">\n", $ren->id_prov);
    $hidden_rvars.= sprintf("   <input type=\"hidden\" name=\"costo1\" value=\"%.2f\">\n", $ren->costo1);

    if ($ren->prov_clave == NULL)
      $prov_clave = "<small>N/D</small>";
    else
      $prov_clave = htmlentities($ren->prov_clave);
    echo "  <td>\n";
    echo "   <form action=\"$PHP_SELF\" method=\"post\">\n";
    echo "   <input type=\"hidden\" name=\"action\" value=\"ver\">\n";
    echo "   <input type=\"hidden\" name=\"subaction\" value=\"cambia_pcosto\">\n";
    echo $hidden_vars;
    echo $hidden_rvars;
    printf("   <input type=\"hidden\" name=\"iva_porc\" value=\"%f\">\n", $ren->iva_porc);
    printf("   <input type=\"hidden\" name=\"costo_envio1\" value=\"%f\">\n", $ren->costo_envio1);
    printf("   <input type=\"hidden\" name=\"divisa\" value=\"%s\">\n", $ren->divisa);
    printf("   <input type=\"hidden\" name=\"prov_clave\" value=\"%s\">\n", $ren->prov_clave);
    printf("   <input type=\"hidden\" name=\"entrega1\" value=\"%d\">\n", $ren->entrega1);
    printf("   <input type=\"hidden\" name=\"status\" value=\"%s\">\n", $ren->status);
    printf("   <input type=\"image\" src=\"imagenes/lapiz.png\">\n");
    echo "   </form>\n";
    echo "  </td>\n";
    echo "  <td>\n";
    echo "   <form action=\"$PHP_SELF\" method=\"post\">\n";
    echo "   <input type=\"hidden\" name=\"action\" value=\"ver\">\n";
    echo "   <input type=\"hidden\" name=\"subaction\" value=\"borra_pcosto\">\n";
    echo $hidden_vars;
    echo $hidden_rvars;
    printf("   <input type=\"hidden\" name=\"act\" value=\"%s\">\n", $ren->actualizacion);
    printf("   <input type=\"image\" src=\"imagenes/borrar.png\">\n");
    echo "   </form>\n";
    echo "  </td>\n";
    printf("  <td $td_fondo>%s</td>\n", $nick_prov[$ren->id_prov]);
    printf("  <td $td_fondo class=\"moneda\">%.2f</td>\n", $ren->costo1 * $tipo_cambio[$ren->divisa]);
    printf("  <td $td_fondo class=\"moneda\">%.2f</td>\n", $ren->iva_porc);
    printf("  <td $td_fondo class=\"serie\">%d</td>\n", $ren->entrega1);
    printf("  <td $td_fondo>%s</td>\n", $prov_clave);
    printf("  <td $td_fondo class=\"moneda\">%.2f</td>\n", $ren->costo_envio1 * $tipo_cambio[$ren->divisa_envio]);
    printf("  <td $td_fondo class=\"serie\"><small>%s</small></td>\n", htmlentities($ren->actualizacion));
    printf("  <td $td_fondo class=\"serie\">%s</td>\n", $ren->status);
    echo "</tr>\n";
  }
?>
</table>
<form action="<?php echo $PHP_SELF ?>" method="post">
<?php printf("   <input type=\"hidden\" name=\"codigo\" value=\"%s\">\n", $codigo) ?>
<input type="hidden" name="action" value="ver">
<input type="hidden" name="subaction" value="nuevo_pcosto">
<input type="image" src="imagenes/web/btn_nuevo_costo_inactivo.png">
</form>
<?php
  }
  else if ($subaction == "nuevo_pcosto") {
    include("bodies/web/invent_item_gral_nuevo_pcosto.bdy");
  } else {
?>

<?php
   }
}
?>
