<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Mov. Invent Web. Subm�dulo de movimientos al inventario de OsoPOS Web.

        Copyright (C) 2000-2003 Eduardo Israel Osorio Hern�ndez

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
  $MOVINV_VERSION = 0.3;
  include("include/general_config.inc");
  include("include/pos-var.inc");
  include("include/pos.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }

  $osopos_carrito = lee_carrito($conn);
  if ($osopos_carrito != $DB_ERROR)
    if ($accion=="detalles") {
      $i=0;
      vacia_carrito($conn);
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Movimientos de inventario v. <? echo $MOVINV_VERSION ?></TITLE>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <style type="text/css">
    td.bg1 { background: <? echo $bg_color1 ?> }
    td.bg1_center {text-align: center; background: <? echo $bg_color1 ?> }
    td.bg1_right {text-align: right; background: <? echo $bg_color1 ?>}
    td.bg2 { background: <? echo $bg_color2 ?> }
    td.bg2_center {text-align: center; background: <? echo $bg_color2 ?> }
    td.bg2_right {text-align: right; background: <? echo $bg_color2 ?> }
    td.bg0 { }
    td.bg0_center {text-align: center }
    td.bg0_right {text-align: right }
    td.right_red {text-align: right; font-color: red}
    td.item_modify {text-align: top }
    div.notify {font-style: italic; color: red}
    div.head_almacen { text-align: center; font-size: big; font-weight: bold }
   </style>
 

</HEAD>
<body>

<?
{
   if (!puede_hacer($conn, $user->user, "movinv_general")) {
     echo "<body>\n";
     echo "<h4>Usted no tiene permisos para accesar este m�dulo</h4><br>\n";
     echo "<a href=\"index.php\">Regresar a men� principal</a>\n";
     echo "</body>\n";
     exit();
   }

  $no_hay = 0;
  if (is_array($codigo)) {
    for ($i=0; $i<count($codigo) && !$no_hay; $i++) {
      $no_hay = (strlen($codigo[$i] == 0 ));
    }
  }

  if ($accion=="detalles" && isset($id_mov) && $id_mov>0) { /* Dummy safe */
    $accion = "prever";
    for ($i=0; $i<count($codigo); $i++)
      if (strlen($codigo[$i])) {
        inserta_det_movinv($conn, $almacen, $id_mov, $codigo[$i], $ct[$i],
                           $pu[$i], $p_costo[$i], $alm_dest[$i], $tipo_mov);
      }
  }
  else if (isset($almacen) && $accion=="cabecera") {
    $cab_ok = 0;
    switch ($tipo_mov[0]) {
    case $MOVINV_VENTA:
      if (puede_hacer($conn, $user->user, "movinv_venta"))
        $cabecera_ok = 1;
      break;
    case $MOVINV_COMPRA:
      if (puede_hacer($conn, $user->user, "movinv_compra"))
        $cabecera_ok = 1;
      break;
    case $MOVINV_DEVVENTA:
      if (puede_hacer($conn, $user->user, "movinv_devventa"))
        $cabecera_ok = 1;
      break;
    case $MOVINV_DEVCOMPRA:
      if (puede_hacer($conn, $user->user, "movinv_devcompra"))
        $cabecera_ok = 1;
      break;
    case $MOVINV_MERMA:
      if (puede_hacer($conn, $user->user, "movinv_merma"))
        $cabecera_ok = 1;
      break;
    case $MOVINV_TSALIDA:
      if (puede_hacer($conn, $user->user, "movinv_tsalida"))
        $cabecera_ok = 1;
      break;
    case $MOVINV_TENTRADA:
      if (puede_hacer($conn, $user->user, "movinv_tentrada"))
        $cabecera_ok = 1;
      break;
    }
    if ($cabecera_ok)
      $id_mov = inserta_cab_movinv($conn, $almacen, $tipo_mov[0], $id_prov1, $user->user, $tiempo);
  }

  if (($accion=="cabecera" && $id_mov) || ($accion=="detalles" && !$no_hay)) {


    if (!isset($nick_prov) || !isarray($nick_prov)) {
      $query = "SELECT nick FROM proveedores WHERE id>=1 ORDER BY id";
      if ($debug>0)
        echo "<i>$query</i><br>\n";
      if (!$db_res = db_query($query, $conn)) {
        echo db_errormsg($conn);
        return($DB_ERROR);
      }
      for ($i=1; $i<db_num_rows($db_res); $i++)
        $nick_prov[$i] = db_result($db_res, $i, 0);
    }

    /* Incluir el nombre del body en la base de datos */
    switch($tipo_mov[0]) {
    case 1:
      include("bodies/mov_inv_venta.bdy");
      break;
    case $MOVINV_COMPRA:
      if (puede_hacer($conn, $user->user, "movinv_compra"))
        include("bodies/mov_inv_compra.bdy");
      else
        echo "<h4>Usted no puede realizar este movimiento</h4>";
      break;
    case 3:
      include("bodies/mov_inv_devven.bdy");
      break;
    case 4:
      include("bodies/mov_inv_devcom.bdy");
      break;
    case 5:
      include("bodies/mov_inv_merma.bdy");
      break;
    case 6:
    case 7:
      include("bodies/mov_inv_transf.bdy");
      break;
    }
      //    include("bodies/movimientos.bdy");
  }
  else if ($accion  ==  "prever") {
    if (isset($imprimir)) {
      imprime_movimiento($conn, $almacen, $id_mov, $codigo, $ct,
                         $pu, $p_costo, $alm_dest, $tipo_mov);
    }

    switch($tipo_mov) {
    case $MOVINV_COMPRA:
      include("bodies/web/prever_movinv_compra.bdy");
      if (is_array($a_series = revisa_series($conn, $id_mov))) {
        include("bodies/mov_inv_compra_series.bdy");
      }
      break;
    default:
      include("bodies/web/prever_movinv.bdy");
      if (is_array($a_series = revisa_series($conn, $id_mov))) {
        include("bodies/mov_inv_compra_series.bdy");
      }
      echo "<hr>\n";
    }
  }
  else if ($accion == "series") {
    $error_serie = 0;
    for ($i=0; $i<count($serial); $i++) {
      $query = "INSERT INTO articulos_series (id, codigo, almacen) VALUES ";
      $query.= sprintf("('%s', '%s', %d) ", $serial[$i], $codigo[$i], $almacen);
      if (!$db_res = db_query($query, $conn)) {
        printf("<div class=\"error_nf\">Error al agregar n�mero de serie \"%s\", c�digo %s</div><br>\n",
               $serial[$i], $codigo[$i]);
        echo db_errormsg($conn);
        $error_serie = 1;
      }

      $error_recup_costo = 0;
      $query = "INSERT INTO articulos_recup_costo (serie, costo) VALUES ";
      $query.= sprintf("('%s', %f) ", $serial[$i], $costo[$i]);
      if (!$db_res = db_query($query, $conn)) {
        printf("Error al registrar producto de serie \"%s\", c�digo %s<br>\n", $serial[$i], $codigo[$i]);
        echo db_errormsg($conn);
        $error_recup_costo = 1;
      }

    }
    if (!$error_serie)
      echo "<i>Series de productos agregadas</i><br>\n";

  }
  else {
	include("bodies/movimientos_cab.bdy");
  }
  include("bodies/menu/general.bdy");
}
?>


</body>
</html>