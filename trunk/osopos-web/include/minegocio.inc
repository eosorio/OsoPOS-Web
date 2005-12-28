<?php  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */


function Crea_Factura($cliente,    /*clase datoscliente */
                      $fecha,      /*clase*/
                      $articulos,  /*arreglo de clase articulos*/
                      $num_articulos,
                      $subtotal,
                      $iva,
                      $total,
                      $garantia,
                      $observaciones,
                      $num_venta,
                      $nmfact,
                      $tipoimp)

{
  global $ESC, $FF;

  include("include/factur_config.inc");
  include ("include/minegocio_factur_const.inc");

  //  $ffact = fopen($nmfact,"w");
  $ffact = "";
  /*  if (!$ffact) {
    echo "<b>Error al abrir el archivo $nmfact</b><br></body></html>";
    exit();
    }*/


  for ($i=0; $i<$MARGEN_SUP; $i++)
    $ffact.= "\n";

  $linea = "Pedido: ";
  if ($pedido)
    $l_pedido.= sprintf("%d", $num_venta);
  else
    $l_pedido.= "s/n";

  $ffact.= espacio($MARGEN);
  $linea = sprintf("Fecha: %2d / %2d / %4d     Pedido: %s\n\n",
                   $fecha->dia,$fecha->mes,$fecha->anio, $l_pedido);
  $ffact.= $linea;
  $ffact.= espacio($MARGEN);
  $ffact.= "=======================================\n";
  $ffact.= espacio($MARGEN);
  $ffact.= "Cliente:\n";
  $linea = sprintf("%s\n",$cliente->nombre);
  $ffact.= espacio($MARGEN);
  $ffact.= $linea;
  $linea = sprintf("%s, %s",
                   $cliente->dom_calle, $cliente->dom_numero);
  if ( strlen($cliente->dom_inter) )
    $linea .= sprintf("-%s",  $cliente->dom_inter);
  $linea .= "\n";
  $ffact.= espacio($MARGEN);
  $ffact.= $linea;
  if (strlen($cliente->dom_col)) {
    $linea = sprintf("Col. %s ", $cliente->dom_col);
    $ffact.= espacio($MARGEN);
    $ffact.= $linea;
  }
  $linea = sprintf("\n");
  $ffact.= $linea;

  $linea = sprintf("%s, %s", $cliente->dom_ciudad, $cliente->dom_edo);
  $ffact.= espacio($MARGEN);
  $ffact.= $linea;

  $ffact.= "\n";
  $linea = sprintf("C.P. %5d     R.F.C. %s\n", $cliente->cp, $cliente->rfc);
  $ffact.= espacio($MARGEN);
  $ffact.= $linea;
  $ffact.= espacio($MARGEN);
  $ffact.= "----------------------------------------\n";


  for ($i=0; $i<$num_articulos; ++$i) {
    if (strlen($articulos[$i]->desc) > $DESC_MAXCOLS) {
      $reng = array();
      $renglon_largo = wordwrap(str_replace("&quot;", "\"", str_replace("\\\"", "\"", $articulos[$i]->desc)),
                                $DESC_MAXCOLS);
      $reng[0] = trim(strtok($renglon_largo, "\n"));
      for ($j=1; $j<$DESC_MAXRENS && $j<=substr_count($renglon_largo, "\n"); $j++) {
        $reng[$j] = trim(strtok("\n"));
      }
    }
    else {
      $j = 1;
      $reng[0] = stripslashes($articulos[$i]->desc);
    }
    $ffact.= espacio($MARGEN);
    $linea = sprintf("%-10s", $articulos[$i]->codigo);
    $linea.= sprintf(" %-30s\n",$reng[0]);
    $ffact.= $linea;

    $num_ren_articulos++;
    for ($k=1; $k<$j; $k++) {
      $ffact.= espacio($MARGEN);
      $ffact.= espacio(11);
      $linea = sprintf("%s\n", $reng[$k]);
      $ffact.= $linea;
      $num_ren_articulos++;
    }
    $ffact.= espacio($MARGEN);
    $linea = sprintf("%4.2f x ",$articulos[$i]->cant);
    $linea.= sprintf("%9.2f = %10.2f\n", $articulos[$i]->pu,
                     $articulos[$i]->pu*$articulos[$i]->cant);
    $ffact.= $linea;
  }

  $ffact.= espacio($MARGEN);
  $ffact.= "----------------------------------------\n";
  $ffact.= espacio($MARGEN);
  $ffact.= espacio(15);
  $linea = sprintf("Subtotal: %10.2f\n",$subtotal);
  $ffact.= $linea;

  $ffact.= espacio($MARGEN);
  $ffact.=espacio(15);
  $linea = sprintf("  I.V.A.: %10.2f\n",$iva);
  $ffact.= $linea;

  $ffact.= espacio($MARGEN);
  $ffact.= espacio(15);
  $linea = sprintf("   Total: %10.2f\n", $total);
  $ffact.= $linea;

  $importe_letra = substr(str_cant($total,$buff), 0, $MAXIMPORT);
  $ffact.= espacio($MARGEN);
  $linea = sprintf("%s---",$importe_letra);
  $ffact.= $linea;
  $linea = sprintf( "\n");
  $ffact.= "\n";

  $ffact.= espacio($MARGEN);
  if ($buff>9)
    $linea = sprintf("pesos %2d/100 M.N.\n",$buff);
  else
    $linea = sprintf("pesos 0%1d/100 M.N.\n",$buff);
  $ffact.= $linea;



  if (strlen($garantia) > 3) {
    $ffact.= espacio(5);
    $linea = sprintf("%s de garantia",$garantia);
  }
  $ffact.= "\n";

  $linea = sprintf("El pago se hace en una sola exhibici%cn\n\n", 162);
  $ffact.= espacio($MARGEN);
  $ffact.= $linea;
  $linea = sprintf("Hecho con OsoPOS Web.\n");
  $ffact.= espacio($MARGEN);
  $ffact.= $linea;
  $linea = "http://elpuntodeventa.com/software.html\n\n";
  $ffact.= espacio($MARGEN);
  $ffact.= $linea;

  $linea = sprintf("%c",$FF);
  $ffact.= $linea;
  return($ffact);
  //  fclose($ffact);
}

/* Tenemos de dos sopas: Se busca la venta en la base de datos, o se le
envían los datos a la función como parámetros. */
function Crea_Ticket ($nm_ticket,
                      $id_venta,
                      $art_cod,
                      $art_desc,
                      $art_cant,
                      $art_pu,
                      $art_iva_porc,
                      $art_disc,
                      $pago,
                      $monto,
                      $lee_venta
                      )
{
  global $TICKET_IVA_INCLUIDO;
  $i=0;

  if ($lee_venta != 0)
    $art = lee_venta ($id_venta);
  else {
    /* ¿Habrá manera de que se le pase el objeto como parámetro de función? */
    $art = array(new articulosClass);
    for ($i=0; $i<count($art_cod); $i++) {
      $art[$i]->cod = $art_cod[$i];
      $art[$i]->desc = $art_desc[$i];
      $art[$i]->cant = $art_cant[$i];
      $art[$i]->pu = $art_pu[$i];
      $art[$i]->iva_porc = $art_iva_porc[$i];
      $art[$i]->disc = $art_disc[$i];
    }
  }

  $impr = fopen($nm_ticket, "w");
  if (!$impr) {
    echo "<b>Error al abrir el archivo $nm_ticket</b><br></body></html>";
    exit();
  }

  if (strtoupper($IMPRESORA_TIPO) == "STAR")
    fputs($impr, sprintf("%c", 7));
  for ($i=0; $i<count($art); $i++) {
    $sub_iva = ( $art[$i]->pu * ($art[$i]->iva_porc/100) );
    $linea = sprintf(" -> %s\n",wordwrap($art[$i]->desc, 35));
    fputs($impr, $linea);
    $linea = sprintf(" %3.2f x ", $art[$i]->cant);
    fputs($impr, $linea);
    if ($TICKET_IVA_INCLUIDO) {
      $linea = sprintf("%7.2f = %7.2f",
                       $art[$i]->pu + $sub_iva,
                       ($art[$i]->pu + $sub_iva) * $art[$i]->cant);
    }
    else {
      $linea = sprintf("%7.2f = %7.2f",
                       $art[$i]->pu,
                       $art[$i]->pu * $art[$i]->cant);
    }
    fputs($impr, $linea);
    if (!$art[$i]->iva_porc)
      fputs($impr, " E");
    fputs($impr, "\n");

    if ($TICKET_IVA_INCLUIDO)
      $a_pagar += (($art[$i]->pu+$sub_iva) * $art[$i]->cant);
    else
      $a_pagar += ($art[$i]->pu * $art[$i]->cant);
    $iva += ( $sub_iva * $art[$i]->cant );
  }
  $linea = sprintf("\n      Total: %10.2f\n", $a_pagar);
  fputs($impr, $linea);
  $linea = sprintf("     I.V.A.: %10.2f\n",  $iva);
  fputs($impr, $linea);
  $linea = sprintf("   ");
  if ($pago==20)
    $linea .= sprintf("Efectivo: %10.2f\n", $monto);
  else if ($pago==1)
    $linea .= sprintf(" Tarjeta: %10.2f\n", $monto+$iva);
  else if ($pago==2)
    $linea .= sprintf(" Credito: %10.2f\n", $monto+$iva);
  else if ($pago==21)
    $linea .= sprintf("  Cheque: %10.2f\n", $monto+$iva);
  fputs($impr, $linea);
  fputs($impr, "E = Exento\n\n");
  fclose($impr);
}

function imprime_ticket_razon()
{
  global $CMD_IMPRESION, $IMPRESORA_TIPO, $ESC, $COLA_TICKET;

  $nm_ar = tempnam("/tmp", "tkct_h");
  if ( !($ar = fopen($nm_ar, "w")) )
    return(1);
  if ($IMPRESORA_TIPO == "EPSON") {
    /* Centrado y doble ancho */
    fputs($ar, sprintf("%ca%c%c!%celpuntodeventa\n%c!%c",
                       $ESC, 1, $ESC, 48, $ESC, 0));
    fputs($ar, sprintf("%ca%c%c!%c.com\n%c!%c",
                       $ESC, 1, $ESC, 48, $ESC, 0));
  }
  else {
    /* Doble ancho y centrado a mano con espacios */
    fputs($ar, sprintf("%c%c%c elpuntodeventa.com\n%c%c%c",
                       $ESC,'P',14,    15, $ESC,'M'));
  }

  fputs($ar, "   Soluciones en control de\n");
  fputs($ar, "inventarios y punto de venta\n");
  fputs($ar, sprintf("Eduardo Israel Osorio Hern%cndez\n", 182));
  fclose($ar);
  $comando = sprintf("%s -P %s %s", $CMD_IMPRESION, $COLA_TICKET, $nm_ar);
  if ( !($impresion = @popen($comando, "w")) )
    return(2);
  else {
    pclose($impresion);
    unlink($nm_ar);
  }
  return(0);
}

function print_ticket_header()
{
  global $CMD_IMPRESION, $IMPRESORA_TIPO, $COLA_TICKET, $ESC;

  $nm_ar = tempnam("/tmp", "tkct_h");
  if ( !($ar = fopen($nm_ar, "w")) )
    return(1);
  fputs($ar, "RFC: OOHE-740628-RNA\n");
  fputs($ar, "Dom. fiscal: 2a. Calle Ote. No. 2.\n");
  fputs($ar, "             Tapachula, Chiapas.n");
  fputs($ar, "-------------------------------------\n");
  fclose($ar);
  $comando = sprintf("%s -P %s %s", $CMD_IMPRESION, $COLA_TICKET, $nm_ar);
  if ( !($impresion = @popen($comando, "w")) )
    return(2);
  else {
    pclose($impresion);
    unlink($nm_ar);
  }
  return(0);
}

function imprime_nota($nm_nota)
{



}


