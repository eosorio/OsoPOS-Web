<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Facturación Web 0.0.3-1. Módulo de facturación de OsoPOS Web.

        Copyright (C) 2000 Eduardo Israel Osorio Hernández

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
  include("include/general_config.inc");
  include("include/pos-var.inc");
//  include("include/caja_web.inc");

{
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }

?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - FacturWeb v. <? echo $factur_web_vers ?></title>
</head>


<body text="#000000" bgcolor="#FFFFFF" link="#0000EF" vlink="#51188E" alink="#FF0000" background="imagenes/fondo.gif">


<?
  include("include/encabezado.inc");
  if (!isset($dia))
    $dia = date("j");
  if (!empty($mes_s)) {
    include "include/mes.inc";
    $mes_a = array_keys($meses, $mes_s);
    $mes = $mes_a[0]+1;
  }
  if (!isset($mes))
    $mes = date("n");
  if (!isset($anio))
    $anio = date("Y");


    /******************** fase dos ******************/
  if ($fase == 1) {
    include("include/pos.inc");

    if (!empty($id_venta)) {

      //      $conn = pg_connect("dbname=" . $DB_NAME . " user=" . $DB_OWNER);
      if (!$conn) {
        echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
        exit();
      }

      $existe_venta = 0;
      if (!is_array($articulo = lee_venta($id_venta))) {
        echo "<b>Error al leer artículos de la venta $id_venta</b><br>\n";
        $accion = "articulos";
      }
      else {
        $num_arts = count($articulo);
        $existe_venta = 1;
      }
    }
    else {
      $accion = "articulos";
    }

    if ($existe_venta==0) {
      include ("include/linucs_factur_const.inc");

      $num_arts = $ART_MAXRENS;
      include("bodies/factur_articulos.bdy");
    }
    else {
      $fase = 2;
      include ("include/linucs_factur_const.inc");

      /* Los datos de artículos provienen de una forma */
      if (isset($desc) && count($desc)) {
        $articulo = array(new articulosClass);
        /* Se cuentan cuantos artículos se introdujeron */
        for($num_arts = 0; strlen($desc[$num_arts]); $num_arts++);
      }

?>
<table border=0 width="100%">
<tbody>
 <tr>
  <td>
<b>Cliente:</b><br>
<?php
  echo "$razon_soc<br>\n";
  printf("%s %d", $dom_calle, $dom_ext);
  if (strlen($dom_int))
    echo "-$dom_int";
  echo "<br>\n";
  if (strlen($dom_col))
    echo "Col. $dom_col. ";
  printf("C.P. %d<br>\n", $dom_cp);
  echo "$dom_ciudad, $dom_edo<br>\n";
  echo "<b>R.F.C.</b> $rfc<br>\n";
?>
  </td>
  <td>
  Folio: <? echo $id ?><br>
  Fecha: 
  <? 
    if (empty($mes)) {
      include "include/mes.inc";
      $mes_a = array_keys($meses, $mes_s);
      $mes = $mes_a[0]+1;
    }
    echo "$dia-$mes-$anio<br>\n";
  ?>
  Observaciones: <br><br><br><br>
  </td>
 </tr>
</tbody>
</table>
<hr>


<table width="100%" border=0>
<thead>
 <tr>
  <th width="10%">Clave</th><th width="5%">Ct.</th>
  <th width="65%">Descripci&oacute;n</th>
  <th width="10%">P.U.</th>
  <th width="10%">Importe</th>

<tbody>

<form action="<?php echo "$PHP_SELF" ?>" method=get>
<?php
  for ($i=0; $i<$num_arts; $i++) {
	if (count($desc)) {
      /* si los datos vienen de una forma */
	  $articulo[$i]->iva_porc = $iva_porc[$i];
	  $articulo[$i]->pu = $pu[$i];
	  $articulo[$i]->codigo = $codigo[$i];
	  $articulo[$i]->cant = $cant[$i];
	  $articulo[$i]->desc = $desc[$i];
	}
    $gravado = $articulo[$i]->iva_porc;
    if ($gravado) {
      $iva += $articulo[$i]->pu * ($articulo[$i]->iva_porc/100) * $articulo[$i]->cant;
    }
    $subtotal += $articulo[$i]->pu * $articulo[$i]->cant;
  
    if (!($i%2))
      $bgcolor = "bgcolor=\"#fdffd8\"";
    else
      $bgcolor = "";
?>

 <tr>
  <td <? echo $bgcolor ?>><? echo $articulo[$i]->codigo ?>&nbsp;</td>
  <input type=hidden name="codigo[<? echo $i ?>]" value="<? echo $articulo[$i]->codigo ?>">
  <td <? echo $bgcolor ?> align=center><? echo $articulo[$i]->cant ?></td>
  <input type=hidden name="cant[<? echo $i ?>]" value="<? echo $articulo[$i]->cant ?>">
  <td width="0*" <? echo $bgcolor ?>><? echo $articulo[$i]->desc ?>&nbsp;</td>
  <input type=hidden name="desc[<? echo $i ?>]" value="<? echo stripslashes($articulo[$i]->desc) ?>">
  <td <? echo $bgcolor ?> align="right"><? printf("%.2f",  $articulo[$i]->pu) ?></td>
  <input type=hidden name="pu[<? echo $i ?>]" value="<? echo $articulo[$i]->pu ?>">
  <td <? echo $bgcolor ?> align="right"><? printf("%.2f",  $articulo[$i]->pu*$articulo[$i]->cant) ?></td>


<?php
	}  /* for */
?>

 <tr>
  <td colspan=5><font face="helvetica,arial" size="-1"><b>Observaciones:</b></font>
   <input type=hidden name=id value="<?php printf("%d", $id) ?>">
   <input type=hidden name=anio value="<?php printf("%d", $anio) ?>">
   <input type=hidden name=mes value="<?php printf("%d", $mes) ?>">
   <input type=hidden name=dia value="<?php printf("%d", $dia) ?>">
   <input type=hidden name=rfc value="<?php echo $rfc ?>">
   <input type=hidden name=razon_soc value="<?php echo $razon_soc ?>">
   <input type=hidden name=dom_calle value="<?php echo $dom_calle ?>">
   <input type=hidden name=dom_ext value="<?php printf("%d",  $dom_ext) ?>">
   <input type=hidden name=dom_int value="<?php echo $dom_int ?>">
   <input type=hidden name=dom_col value="<?php echo $dom_col ?>">
   <input type=hidden name=dom_ciudad value="<?php echo $dom_ciudad ?>">
   <input type=hidden name=dom_edo value="<?php echo $dom_edo ?>">
   <input type=hidden name=dom_cp value="<?php echo $dom_cp ?>">
 </td>

 <tr>
  <td colspan=3 rowspan=3>
   <textarea name=observaciones cols=<? printf("%d", $OBS_MAXCOLS) ?>
   rows=<? printf("%d", $OBS_MAXRENS) ?> wrap=hard></textarea>
  </td>
  <td align="right"><b>Subtotal</b></td>
  <td align="right"><?php printf("%.2f", $subtotal) ?></td>
 <input type=hidden name=subtotal value=<?php echo $subtotal ?>>

 <tr>
  <td align="right"><b>I.V.A.</b></td>
  <td align="right"><?php printf("%.2f", $iva) ?></td>
  <input type=hidden name=iva value=<?php echo $iva ?>>

 <tr>
  <td align="right"><b>Total</b></td>
  <td align="right"><b><font color="red"><?php printf("%.2f", $subtotal+$iva) ?></font></b></td>

</table>
<br><br>
<center>
<font size=+3><b><?php echo str_cant($subtotal+$iva, $centavos) . "pesos $centavos" ?>/100 M.N.</b></font>
</center>

<br>
<table border=0 width="100%">
<tbody>
 <tr>
  <td width=5  align=center><input type=radio name=accion value="agregarimprimir" checked>
  <td>Agregar e imprimir
  <td width=5  align=center><input type=radio name=accion value="agregar">
  <td>Sólo agregar
  <td align=right><input type=submit value="Registrar factura">
</tbody>
</table>

</form>


<?
    }  /* de else (no existe id_venta) */
  }    /* de if (accion=mostrar || accion=articulos */

             /********************** fase tres ******************/
  if ( strlen(strstr($accion, "agregar")) ) {
    $conn = pg_connect("dbname=" . $DB_NAME . " user=" . $DB_OWNER);
    if (!$conn) {
      echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
      exit();
    }

    $peticion = "INSERT INTO facturas_ingresos VALUES (";
    $peticion.= sprintf("%d, '%d-%d-%d', ", $id, $anio, $mes, $dia) . "'$rfc', '$dom_calle', ";
    $peticion.= sprintf("%d, ", $dom_ext) . "'$dom_int', ";
    $peticion.= "'$dom_col', '$dom_ciudad', '$dom_edo', " . sprintf("%d, %.2f, %.2f)", $dom_cp, $subtotal, $iva);
    if (!$resultado = pg_exec($conn, $peticion)) {
      echo "Error al ejecutar $peticion<br>" . pg_errormessage($conn) . "</body></html>\n";
      exit();
    }
    else
      echo "<center><i>Factura $id, $rfc agregada</i></center>";

    for ($i=0; $i<count($desc); $i++) {
      $peticion = "INSERT INTO fact_ingresos_detalle ";
      $peticion.= "(\"id_factura\", \"codigo\", \"concepto\", \"cant\", \"precio\")";
      $peticion.= " VALUES (";
      $peticion.= sprintf("%d, '%s', '%s', %d, %.2f)",
                          $id, $codigo[$i], $desc[$i], $cant[$i], $pu[$i]);
      if (!$resultado = pg_exec($conn, $peticion)) {
        echo "Error al ejecutar $peticion<br>" . pg_errormessage($conn) . "</body></html>\n";
        exit();
      }
    }
    $fase = 0;
  }

  if ( strlen(strstr($accion, "imprimir")) ) {
    include("include/pos.inc");
    /*igm*/ include("include/linucs.inc");
    /*igm*/ $tipoimp = "EPSON";
    /*igm*/ $obs = array();
    /*igm*/ $garantia = "Treinta segundos";

    $cliente = new datosclienteClass;
    $cliente->rfc = $rfc;
    $cliente->curp = $curp;
    $cliente->nombre = $razon_soc;
    $cliente->dom_calle = $dom_calle;
    $cliente->dom_numero = $dom_ext;
    $cliente->dom_inter = $dom_int;
    $cliente->dom_col = $dom_col;
    $cliente->cp = $dom_cp;
    $cliente->dom_edo = $dom_edo;
    $cliente->dom_ciudad = $dom_ciudad;

    $fecha = new fechaClass;
    $fecha->dia  = $dia;
    $fecha->mes  = $mes;
    $fecha->anio = $anio;

    $art = array(new articulosClass);
    for ($i=0; $i<count($desc); $i++) {
      $art[$i] = new articulosClass;
      $art[$i]->codigo = $codigo[$i];
      $art[$i]->desc = $desc[$i];
      $art[$i]->pu = $pu[$i];
      $art[$i]->cant = $cant[$i];
    }

    $observaciones = str_replace("\n", " ", str_replace("\r", "", $observaciones));

    $nm_archivo = tempnam($TMP_DIR, "factweb");

    Crea_Factura($cliente, $fecha, $art, count($desc), $subtotal, $iva, $subtotal+$iva,
                 $garantia, $observaciones, $nm_archivo, $tipoimp);

    $impresion = popen($CMD_IMPRESION . $nm_archivo, "w");
    if (!$impresion) {
      echo "<b>Error al ejecutar <i>$CMD_IMPRESION $nm_archivo</i></b><br>\n";
    }
    else {
      echo "<center><i>Factura impresa.</i></center>\n";
      pclose($impresion);
    }
  } /* if ($accion=="imprimir"  ||  $accion=="agregarimprimir") */


?>
<?
        /*******************  fase uno *****************/

  if (!isset($fase)  ||  $fase==0) {
    $conn = pg_connect("dbname=" . $DB_NAME . " user=" . $DB_OWNER);
    if (!$conn) {
      echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
      exit();
    }

    if (isset($decodifica_rfc)) {
      $codificado = explode("|", $rfc);
      $rfc = $codificado[0];
      $razon_soc = $codificado[1];
      $curp = $codificado[2];

      $peticion = "SELECT * FROM facturas_ingresos WHERE rfc='$rfc' ORDER BY fecha DESC";
      if (!$resultado = pg_exec($conn, $peticion)) {
        echo "Error al ejecutar $peticion<br>No se pudo encontrar los datos del cliente<br>" . pg_errormessage($conn) . "<br>\n";
      }
	  else {
		if (pg_numrows($resultado)) {
		  $renglon = pg_fetch_object($resultado, 0);
		  $dom_calle = $renglon->dom_calle;
		  $dom_ext = $renglon->dom_numero;
		  $dom_int = $renglon->dom_inter;
		  $dom_col = $renglon->dom_col;
		  $dom_cp = $renglon->dom_cp;
		  $dom_ciudad = $renglon->dom_ciudad;
		  $dom_edo = $renglon->dom_edo;
		}
	  }
    }

    if (!isset($id)) {
      $query = "SELECT max(id) AS next_id FROM facturas_ingresos";
      if (!$result = pg_exec($conn, $query)) {
        echo "Error al ejecutar $peticion<br>No se pudo extraer último folio<br>" . pg_errormessage($conn) . "<br>\n";
      }
      else {
        $id = pg_result($result, 0, "next_id");
      }
    }

	include("bodies/factur_uno.bdy");

?>



<?
      }
}

/*
Pendientes:
- Verificar que no se excedan los renglones para artículos cuando
  se usa mas de un renglón para un solo artículo y se usan
  $ART_MAXRENS artículos
- Hacer captura de observaciones, con su respectivo wrapping
*/


?>
</body>
</html>
