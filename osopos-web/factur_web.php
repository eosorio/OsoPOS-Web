<?  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Facturación Web 0.4-1. Módulo de facturación de OsoPOS Web.

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
  /*igm*/ $debug = 0;
{
  include("include/general_config.inc");
  include("include/factur_config.inc");
  include("include/pos-var.inc");

  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
    if (!$conn) {
      echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
      exit();
    }
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - FacturWeb v. <? echo $factur_web_vers ?></title>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <style type="text/css">
    td.campo {font-face: helvetica,arial}
    td.nm_campo {text-align: right}
    td.right {font-face: helvetica, arial; text-align: right}
    td.right_red {text-align: right; font-face: helvetica, arial; color: red;}
    
   </style>

</head>

<body>

<?
  include("include/encabezado.inc");
  if (!isset($accion) || $accion!="lista") {

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

                if (!$conn) {
                  echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
                  exit();
                }

                if (!isset($existe_venta))
                  $existe_venta = 0;
                if (!is_array($articulo = lee_venta($id_venta))) {
                  echo "<b>Error al leer artículos de la venta $id_venta</b><br>\n";
                  $accion = "articulos";
                }
                else {
                  if ($num_arts = count($articulo)) {
                    $existe_venta = 1;
                  }
                  else {
                    $existe_venta = 0;
                    $accion = "articulos";
                  }
                }

    }
    else {
      $accion = "articulos";
    }

    if ($existe_venta==0) {
      include ("include/minegocio_factur_const.inc");

      $num_arts = $ART_MAXRENS;
      include("bodies/factur_articulos.bdy");
    }
    else {
      $fase = 2;
      include ("include/minegocio_factur_const.inc");

      /* Los datos de artículos provienen de una forma */
      if (isset($desc) && count($desc)) {
        $articulo = array(new articulosClass);
        /* Se cuentan cuantos artículos se introdujeron */
        for($num_arts = 0; strlen($desc[$num_arts]); $num_arts++);
      }

      if (empty($mes)) {
        include "include/mes.inc";
        $mes_a = array_keys($meses, $mes_s);
        $mes = $mes_a[0]+1;
      }

      include("bodies/factur_prevista.bdy");


    }  /* de else (no existe id_venta) */
  }    /* de if (accion=mostrar || accion=articulos */

             /********************** fase tres ******************/
  if ( strlen(strstr($accion, "agregar")) ) {
    if (!$conn) {
      echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
      exit();
    }

    $peticion = "INSERT INTO facturas_ingresos VALUES (";
    $peticion.= sprintf("%d, '%d-%d-%d', ", $id, $anio, $mes, $dia) . "'$rfc', '$dom_calle', ";
    $peticion.= sprintf("%d, '%s', ", $dom_ext, $dom_int);
    $peticion.= "'$dom_col', '$dom_ciudad', '$dom_edo', ";
    $peticion.= sprintf("%d, %.2f, %.2f", $dom_cp, $subtotal, $iva);
    for ($j=0; $j<$MAXTAX; $j++)
      $peticion.= sprintf(", %.2f", $impuesto[$j]);
    $peticion.= ")";

    if (isset($debug) && $debug>0)
      echo "<i>$peticion</i><br>\n";
    else if (!$resultado = db_query($peticion, $conn)) {
      echo "Error al ejecutar $peticion<br>" . db_errormsg($conn) . "</body></html>\n";
      exit();
    }
    else
      echo "<center><i>Factura $id, $rfc agregada</i></center><br>";

    for ($i=0; $i<count($desc); $i++) {
      $peticion = "INSERT INTO fact_ingresos_detalle ";
      $peticion.= "(id_factura, codigo, concepto, cant, precio)";
      $peticion.= " VALUES (";
      $peticion.= sprintf("%d, '%s', '%s', %d, %.2f)",
                          $id, $codigo[$i], $desc[$i], $cant[$i], $pu[$i]);
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al ejecutar $peticion<br>" . db_errormsg($conn) . "</body></html>\n";
        exit();
      }
    }

    if ($AGREGA_CLIENTES != 0) {
      $peticion = "SELECT rfc FROM clientes_fiscales WHERE rfc='$rfc'";
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al ejecutar $peticion<br>" . db_errormsg($conn) . "</body></html>\n";
        exit();
      }
      if (db_num_rows($resultado) == 0) {
        $peticion = "INSERT INTO clientes_fiscales (\"rfc\", \"curp\", \"nombre\") ";
        $peticion.= " VALUES ('$rfc', '$curp', '$razon_soc')";
        $mensaje = "<center><i>Cliente $razon_soc agregado</i></center><br>";
      }
      else
        $mensaje = "";
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al ejecutar $peticion<br>" . db_errormsg($conn) . "</body></html>\n";
        exit();
      }
      echo $mensaje;
    }
    $fase = 0;
  }

  if ( strlen(strstr($accion, "imprimir")) ) {
    include("include/pos.inc");
    include("include/minegocio.inc");
    include("include/minegocio_factur_const.inc"); 

    /*igm*/ $obs = array();

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

    $observaciones = chop (str_replace("\n", " ", str_replace("\r", "", $observaciones)) );

    //$nm_archivo = tempnam($TMP_DIR, "factweb");
    $nm_archivo = "";

    $imp_buff= Crea_Factura($cliente, $fecha, $art, count($desc), $subtotal, $iva, $subtotal+$iva,
                            $garantia, $observaciones, $id_venta, $nm_archivo, $tipoimp);

    $linea = "$CMD_IMPRESION -P $COLA_FACTUR $nm_archivo";
    /*IGM*/ echo "$linea<br>\n";
    $impresion = popen($linea, "w");
    if (!$impresion) {
      echo "<b>Error al ejecutar <i>$CMD_IMPRESION $nm_archivo</i></b><br>\n";
    }
    else {
      /*igm */echo "<pre>$imp_buff</pre>";
      echo "<center><i>Factura impresa.</i></center>\n";
      fputs($impresion, $imp_buff);
      pclose($impresion);
    }
  } /* (fin) if ($accion=="imprimir"  ||  $accion=="agregarimprimir") */


?>
<?
        /*******************  fase uno *****************/

  if (!isset($fase)  ||  $fase==0) {
    if (!$conn) {
      echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
      exit();
    }

    if (!$REPEAT_FACT_DATA) {
      $rfc = "";
      $razon_soc = "";
      $curp = "";
      $dom_calle = "";
      $dom_ext = "";
      $dom_int = "";
      $dom_col = "";
      $dom_cp = "";
      $dom_ciudad = "";
      $dom_edo = "";
    }

    if (isset($decodifica_rfc)) {
      $codificado = explode("|", $rfc);
      $rfc = $codificado[0];
      $razon_soc = $codificado[1];
      $curp = $codificado[2];

      $peticion = "SELECT * FROM facturas_ingresos WHERE rfc='$rfc' ORDER BY fecha DESC";
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al ejecutar $peticion<br>No se pudo encontrar los datos del cliente<br>" . db_errormesg($conn) . "<br>\n";
      }
      else {
        if (db_num_rows($resultado)) {
          $renglon = db_fetch_object($resultado, 0);
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
      if (!$result = db_query($query, $conn)) {
        echo "Error al ejecutar $peticion<br>No se pudo extraer último folio<br>" . db_errormsg($conn) . "<br>\n";
      }
      else {
        $id = db_result($result, 0, "next_id");
      }
    }
    
        include("bodies/factur_uno.bdy");

?>



<?
          }
  }
  else if ($accion=="lista") {
        if ($debug>0)
          echo "<i>$query</i><br>\n";
        $query = "SELECT * FROM facturas_ingresos ORDER BY id ASC";
        if (!$db_res = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
        }
        include("bodies/ingresos_lista.bdy");
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
