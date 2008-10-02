<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Facturación Web 0.4-1. Módulo de facturación de OsoPOS Web.

        Copyright (C) 2000,2003-2005 Eduardo Israel Osorio Hernández

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

  include("include/passwd.inc");

  $accion = $_POST['accion'];
  $dia = $_POST['dia'];
  $mes_s = $_POST['mes_s'];
  $anio = $_POST['anio'];
  $fase = $_POST['fase'];
  $id_venta = $_POST['id_venta'];
  $id = $_POST['id'];
  $id_cliente = $_POST['id_cliente'];
  $razon_soc = $_POST['razon_soc'];
  $rfc = $_POST['rfc'];
  $curp = $_POST['curp'];
  /* Pendiente: Convertir las variables dom_ en una clase */
  $dom_calle = $_POST['dom_calle'];
  $dom_ext = $_POST['dom_ext'];
  $dom_int = $_POST['dom_int'];
  $dom_col = $_POST['dom_col'];
  $dom_cp = $_POST['dom_cp'];
  $dom_ciudad = $_POST['dom_ciudad'];
  $dom_edo = $_POST['dom_edo'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - FacturWeb v. <? echo $factur_web_vers ?></title>
   <?php include("menu/menu_principal.inc"); ?>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <style type="text/css">
    td.campo {font-face: helvetica,arial}
    td.nm_campo {text-align: right}
    td.right {font-face: helvetica, arial; text-align: right}
    td.right_red {text-align: right; font-face: helvetica, arial; color: red;}
    
   </style>
   <script>
   var miPopup
   function abreVentana(scriptFile){
	miPopup = window.open(scriptFile,"miwin","width=700,height=400,scrollbars=yes")
	miPopup.focus()
	}
   </script>
<?php
  include("include/pos.inc");

    /*  if (strlen(strstr($accion, "imprimir")) && lee_config($conn, "FACTURA_TIPO") == "PDF") {

    $TMP_DIR = lee_config($conn, "TMP_DIR");
    $FACTURA_FORMATO_PDF = lee_config($conn, "FACTURA_FORMATO_PDF");
    $nm_tmpar = tempnam($TMP_DIR, "factura");
    include("$FACTURA_FORMATO_PDF");
    echo "<script>window.open(\"leepdf.php?ar=$nm_tmpar\", \"mipdf\", \"width=700,height=500,scrollbars=yes\");</script>";
      //      unlink($nm_tmpar);
      }*/
?>
</head>

<body>

<?php
  include("menu/menu_principal.bdy");
  echo "<br>\n";

  include("bodies/menu/factur.bdy");
  include("include/encabezado.inc");
  if (empty($accion) || $accion!="lista") {

        if (empty($dia))
          $dia = date("j");
        if (!empty($mes_s)) {
          include "include/mes.inc";
          $mes_a = array_keys($meses, $mes_s);
          $mes = $mes_a[0]+1;
        }
        if (empty($mes))
          $mes = date("n");
        if (empty($anio))
          $anio = date("Y");


    /******************** fase dos ******************/
        if ($fase == 1) {
          //          include("include/pos.inc");

          if (!empty($id_venta)) {

                if (!$conn) {
                  die("<div class=\"error_f\">ERROR: Al conectarse a la base de datos</div><br>\n");
                }

                if (!isset($existe_venta))
                  $existe_venta = 0;
                if (!is_array($articulo = lee_venta($id_venta))) {
                  echo "<div class=\"error_nf\">Error al leer artículos de la venta $id_venta</div><br>\n";
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
      $articulo = array(new articulosClass);

      /* Los datos de artículos provienen de una forma */
      if (isset($_POST['desc'])) {
        $es_forma = TRUE;

        $f_desc = $_POST['desc'];
        $f_pu = $_POST['pu'];
        $f_cant = $_POST['cant'];
        $num_arts = 0;

        $i = 0;
        /* Se cuentan cuantos artículos se introdujeron */
        //        for( ; strlen($_POST['desc'][$num_arts]) && strlen ($_POST['pu'][$num_arts] && strlen($_POST['cant'][$num_arts]; $num_arts++)
        while (list ($item, $valor) = each ($_POST['desc']))
        {
          if (strlen($valor) && strlen($_POST['iva_porc']) && strlen($_POST['pu'])
              && strlen($_POST['cant'])) {

            $articulo[$i]->iva_porc = $_POST['iva_porc'][$item];
            $articulo[$i]->pu = $_POST['pu'][$item];
            $articulo[$i]->codigo = $_POST['codigo'][$item];
            $articulo[$i]->cant = $_POST['cant'][$item];
            $articulo[$i]->desc = $valor;
            $i++;
          }

        }
        $num_arts = $i;
        reset($articulo);
      }
      else /* Provienen de una venta registrada */
      {
        $es_forma = FALSE;
        $query = "SELECT codigo, descrip, cantidad, pu, iva_porc, ";
        $query.= "tax_0, tax_1, tax_2, tax_3, tax_4, tax_5 FROM ventas_detalle ";
        $query.= sprintf("WHERE id_venta = %d ", $_POST['id_venta']);

        if (!$db_res = db_query($query, $conn)) {
          die("<div class=\"error_f\">Error al consultar ventas </div>");
        }
        $num_arts = db_num_rows($db_res);
        if (db_num_rows($db_res) == 0)
          echo "<div class=\"error_nf\">La venta no tiene registro de productos.</div>\n";
        for ($i=0; $i < $num_arts; $i++) {
          $art = db_fetch_object($db_res, $i);

          $articulo[$i]->iva_porc = $art->iva_porc;
          $articulo[$i]->pu = $art->pu;
          $articulo[$i]->codigo = $art->codigo;
          $articulo[$i]->cant = $art->cantidad;
          $articulo[$i]->desc = $art->descrip;
        }

      }
      if (empty($mes)) {
        include "include/mes.inc";
        $mes_a = array_keys($meses, $mes_s);
        $mes = $mes_a[0]+1;
      }

      /* inicialización de variables */
      $iva = 0.0; $subtotal = 0.0;
      $impuesto = array();
      for ($z=0; $z<$MAXTAX; $z++, $impuesto[$z] = 0.0);

      include("bodies/factur_prevista.bdy");


    }  /* de else (no existe id_venta) */
  }    /* de if (accion=mostrar || accion=articulos */

             /********************** fase tres ******************/
  if ( strlen(strstr($accion, "agregar")) ) {
    if (!$conn)
      die("<div class=\"error_f\">Al conectarse a la base de datos</div>\n");

    if (isset($_POST['codigo']) && isset($_POST['cant']) && isset($_POST['desc'])&& isset($_POST['pu'])) {
      $desc = $_POST['desc'];
      $codigo = $_POST['codigo'];
      $cant = $_POST['cant'];
      $pu = $_POST['pu'];
      $garantia = $_POST['garantia'];
      $observaciones = $_POST['observaciones'];
      $subtotal = $_POST['subtotal'];
      $iva = $_POST['iva'];
      $id_cliente = $_POST['id_cliente'];
    }
    else {
      die("<div class=\"error_f\">Error al obtener datos de artículos</div>\n");
    }

    $peticion = "INSERT INTO facturas_ingresos VALUES (";
    $peticion.= sprintf("%d, '%d-%d-%d', ", $id, $anio, $mes, $dia) . "'$rfc', '$dom_calle', ";
    $peticion.= sprintf("'%s', '%s', ", $dom_ext, $dom_int);
    $peticion.= "'$dom_col', '$dom_ciudad', '$dom_edo', ";
    $peticion.= sprintf("%d, %.2f, %.2f", $dom_cp, $subtotal, $iva);
    for ($j=0; $j<$MAXTAX; $j++)
      $peticion.= sprintf(", %.2f", $impuesto[$j]);
    $peticion.= ")";

    if (isset($debug) && $debug>0)
      echo "<i>$peticion</i><br>\n";
    else if (!$resultado = db_query($peticion, $conn)) {
      $mens = "Error al agregar datos de factura<br>" . db_errormsg($conn);
      die($mens);
    }
    else
      echo "<div class=\"mens_inf\">Factura $id, $rfc agregada</div><br>";

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
      if (!$resultado = db_query($peticion, $conn))
        die("<div class=\"error_f\">Error al consultar RFC</div>\n");

      if (db_num_rows($resultado) == 0) {
        $peticion = "INSERT INTO clientes_fiscales (\"rfc\", \"curp\", \"nombre\") ";
        $peticion.= " VALUES ('$rfc', '$curp', '$razon_soc')";
        $mensaje = "<div class=\"mens_inf\">Cliente fiscal $razon_soc agregado</div><br>";
      }
      else
        $mensaje = "";
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al registrar cliente fiscal<br>" . db_errormsg($conn) . "</body></html>\n";
        exit();
      }
      echo $mensaje;

      $peticion = "SELECT rfc FROM clientes WHERE rfc='$rfc' AND nombres='$razon_soc'";
      if (!$resultado = db_query($peticion, $conn))
        die("<div class=\"error_f\">Error al consultar RFC</div>\n");

      if (db_num_rows($resultado) == 0) {
        $peticion = "INSERT INTO clientes (\"rfc\", \"curp\", \"nombres\", ap_paterno, ap_materno) ";
        $peticion.= " VALUES ('$rfc', '$curp', '$razon_soc', '', '')";
        $mensaje = "<div class=\"mens_inf\">Cliente $razon_soc agregado</div><br>\n";
      }
      else
        $mensaje = "";
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al registrar cliente fiscal<br>" . db_errormsg($conn) . "</body></html>\n";
        exit();
      }
      else {
        $peticion = "SELECT id FROM clientes WHERE rfc='$rfc' AND curp='$curp' AND nombres='$razon_soc' ORDER BY id DESC";
        if (!$resultado = db_query($peticion, $conn))
          echo "<div class=\"error_nf\">Error al consultar id de cliente $razon_soc</div>\n";
        else {
          $id_cliente = db_result($resultado, 0, 0);
          echo $mensaje;

          $peticion = "INSERT INTO domicilios (id_cliente, dom_nombre, dom_calle, dom_numero, dom_inter, dom_col, dom_mpo, dom_ciudad, dom_edo_id, dom_cp) VALUES (";
          $peticion.= sprintf("%d, 'Fiscal', '%s', '%s', '%s', '%s', '%s', '%s', %d, %d) ",
                              $id_cliente, $dom_calle,  $dom_ext, $dom_int, $dom_col, $dom_mpo, $dom_ciudad, getDomicilioEstadosID($conn, $dom_edo),  $dom_cp);
          if (!$resultado = db_query($peticion, $conn))
            echo "<div class=\"error_nf\">Error al insertar domicilio fiscal de cliente $razon_soc</div>\n$peticion"; 
          else {
            $peticion = "SELECT id FROM domicilios WHERE id_cliente=$id_cliente AND dom_nombre='Fiscal' ORDER BY id DESC";
            if (!$resultado = db_query($peticion, $conn))
              echo "<div class=\"error_nf\">Error al consultar domicilio fiscal de cliente $razon_soc</div>\n";
            else {
              $id_dom_fiscal = db_result($resultado, 0, 0);
              $peticion = "UPDATE clientes SET dom_principal=$id_dom_fiscal WHERE id=$id_cliente";
              if (!$resultado = db_query($peticion, $conn))
                echo "<div class=\"error_nf\">Error al registrar domicilio de cliente $razon_soc</div>\n";
            }
          }
        }
      }
    }
    $fase = 0;
  }

  if ( strlen(strstr($accion, "imprimir")) ) {

    //    include("include/pos.inc");
    if (lee_config($conn, "FACTURA_TIPO") == "PDF") {
      /*      printf("<a href=\"%s?id=%d\" target=\"_top\"><img src=\"imagenes/pdf.png\" border=0></a>",
       $_SERVER['PHP_SELF], $_POST['id']); */
      //      $TMP_DIR = lee_config($conn, "TMP_DIR");
      //      $FACTURA_FORMATO_PDF = lee_config($conn, "FACTURA_FORMATO_PDF");
      //      $nm_tmpar = tempnam($TMP_DIR, "factura");
      //      include("$FACTURA_FORMATO_PDF");
      //      echo "<script>window.open(\"leepdf.php?ar=$nm_tmpar\", \"mipdf\", \"width=700,height=500,scrollbars=yes\");</script>";
      //      unlink($nm_tmpar);                                                                                                                            
    }
 
    include("include/minegocio.inc");
    include("include/minegocio_factur_const.inc"); 

    /*igm*/ $obs = array();

    $cliente = new datosclienteClass;
    $cliente->id = $id_cliente;
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
      $art[$i]->iva_porc = $iva_porc[$i];
    }

    $observaciones = chop (str_replace("\n", " ", str_replace("\r", "", $observaciones)) );

    $nm_archivo = "";

    if (lee_config($conn, "FACTURA_TIPO") == "PDF") {
      $TMP_DIR = lee_config($conn, "TMP_DIR");
      $FACTURA_FORMATO_PDF = lee_config($conn, "FACTURA_FORMATO_PDF");
      $nm_tmpar = tempnam($TMP_DIR, "factura");
      //      include("$FACTURA_FORMATO_PDF");
      //echo "<script>window.open(\"leepdf.php?ar=$nm_tmpar\", \"mipdf\", \"width=700,height=500,scrollbars=yes\");</script>";
      //      unlink($nm_tmpar);
      printf("<script>window.open(\"%s?id=%d\")</script>\n", $FACTURA_FORMATO_PDF, $_POST['id']);
    }
    else {
      $cola_factur = lee_config($conn, "COLA_FACTUR");
      $cmd_impresion = lee_config($conn, "CMD_IMPRESION");

      $imp_buff= Crea_Factura($cliente, $fecha, $art, count($desc), $subtotal, $iva, $subtotal+$iva,
                              $garantia, $observaciones, $id_venta, $nm_archivo, $tipoimp);
      /*igm*/ echo "<pre>$imp_buff</pre>\n";
      $linea = "$cmd_impresion -P $cola_factur";

      $impresion = popen($linea, "w");
      if (!$impresion) {
        echo "<div class=\"error_nf\">Error al ejecutar <i>$cmd_impresion $nm_archivo</i></div><br>\n";
      }
      else {
        echo "<div class=\"mens_inf\">Factura impresa.</div>\n";
        fputs($impresion, $imp_buff);
        pclose($impresion);
      }
    }
  } /* (fin) if ($accion=="imprimir"  ||  $accion=="agregarimprimir") */


?>
<?php
        /*******************  fase uno *****************/

  if (empty($fase)  ||  $fase==0) {
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

//     if (isset($decodifica_rfc)) {
//       $codificado = explode("|", $rfc);
//       $rfc = $codificado[0];
//       $razon_soc = $codificado[1];
//       $curp = $codificado[2];

//       $peticion = "SELECT * FROM facturas_ingresos WHERE rfc='$rfc' ORDER BY fecha DESC";
//       if (!$resultado = db_query($peticion, $conn)) {
//         echo "Error al ejecutar $peticion<br>No se pudo encontrar los datos del cliente<br>" . db_errormesg($conn) . "<br>\n";
//       }
//       else {
//         if (db_num_rows($resultado)) {
//           $renglon = db_fetch_object($resultado, 0);
//           $dom_calle = $renglon->dom_calle;
//           $dom_ext = $renglon->dom_numero;
//           $dom_int = $renglon->dom_inter;
//           $dom_col = $renglon->dom_col;
//           $dom_cp = $renglon->dom_cp;
//           $dom_ciudad = $renglon->dom_ciudad;
//           $dom_edo = $renglon->dom_edo;
//         }
//       }
//     }

    if (empty($id)) {
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
