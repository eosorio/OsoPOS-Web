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
                      $nmfact,
                      $tipoimp)

{
  include ("include/electroh_factur_const.inc");

  global $ESC, $FF, $MARGEN;

  if ($ESC != 27)
    $ESC = 27;

  $ffact = fopen($nmfact,"w");

  if (!$ffact) {
    echo "<b>Error al abrir el archivo $nmfact</b><br></body></html>";
    exit();
  }

  $linea = sprintf("%c@", $ESC);
  /* Reset de impresora */
  fputs($ffact, $linea);

  $linea = sprintf("%c",18);
  /* Cancela modo condensado */
  fputs($ffact, $linea);

  if ($tipoimp == "EPSON") {
    $linea = sprintf("%c%c", $ESC,77);
    fputs($ffact, $linea);
  }
  else {
    $linea = sprintf("%c%c", $ESC,58);
  /* 12cpi en modo Epson: ESC,77, en modo IBM: ESC,58 */
    fputs($ffact, $linea);
  }

  fputs($ffact, "\r\n");
  $linea = sprintf("%cJ%c", $ESC,2);
  /* 2/n-avos de pulgada */
  fputs($ffact, $linea);

  fputs($ffact, "\n\r");

  Espacios($ffact, 52);

  Espacios($ffact, $MARGEN);
  if ($fecha->mes<10)
    $linea = sprintf("%d    0%d   ",$fecha->dia,$fecha->mes);
  else
    $linea = sprintf("%d     %d   ",$fecha->dia,$fecha->mes);
  fputs($ffact, $linea);
  $linea = sprintf("%d\n\r",$fecha->anio);
  fputs($ffact, $linea);

  Espacios($ffact, $MARGEN);
  $linea = sprintf("%cJ%c",$ESC,30);
  $linea.= sprintf("      %s\n\r",$cliente->nombre);
  fputs($ffact, $linea);

  $linea = sprintf("%cJ%c",$ESC,15);
  Espacios($ffact, $MARGEN);
  $linea.= sprintf("       %s %s",$cliente->dom_calle, $cliente->dom_numero);
  if ( strlen($cliente->dom_inter) )
    $linea .= sprintf("-%s ",  $cliente->dom_inter);
  $linea.= ". ";
  if (strlen($cliente->dom_col))
    $linea.= sprintf("Col. %s", $cliente->dom_col);
  $linea .= "\n\r";
  fputs($ffact, $linea);

  $linea = sprintf("%cJ%c",$ESC,15);
  fputs($ffact, $linea);

  Espacios($ffact, $MARGEN);
  $linea = sprintf("       %s, %s", $cliente->dom_ciudad, $cliente->dom_edo);
  fputs($ffact, $linea);
  Espacios($ffact, 45-strlen($linea));
  $linea = sprintf("%s\n\n\n\n\r",$cliente->rfc);
  fputs($ffact, $linea);

  for ($i=0; $i<$num_articulos; ++$i) {
    if (strlen($articulos[$i]->desc) > $DESC_MAXCOLS) {
      $reng = array();
      $renglon_largo = $articulos[$i]->desc;
      /* Aplica el wrapping */
      for ($j=0; $j<$DESC_MAXRENS; $j++) {
        $semi_renglon = substr($renglon_largo, 0, $DESC_MAXCOLS);
        if ($char_pos=strrpos($semi_renglon, "\n")) {
          $pos_fin_reng = $char_pos;
        }
        else if ($char_pos=strrpos($semi_renglon, " ")) {
          $pos_fin_reng = $char_pos;
        }
        else
          $pos_fin_reng = strlen($semi_renglon);

        $reng[$j] = substr($semi_renglon, 0, $pos_fin_reng);

        if (!empty($reng[$j]) && !strpos($reng[$j], "\n"))
          $reng[$j] .= "\n";
        $reng[$j] = str_replace("\n", "\n\r", $reng[$j]);
        $renglon_largo = substr($renglon_largo, $pos_fin_reng+1, strlen($renglon_largo));
      }
    }
    else {
      $j = 1;
      $reng[0] = $articulos[$i]->desc;
    }

    Espacios($ffact, $MARGEN);
    $linea = sprintf("%4.0d ",$articulos[$i]->cant);
    fputs($ffact, $linea);

    $linea = sprintf("  %-35s ",$reng[0]);
    fputs($ffact, $linea);
    $buff = strlen($reng);
    $linea = sprintf("%8.2f %8.2f\n\r",$articulos[$i]->pu,$articulos[$i]->pu*$articulos[$i]->cant);
    fputs($ffact, $linea);
    $num_ren_articulos++;
    for ($k=1; $k<$j; $k++) {
      Espacios($ffact, $MARGEN);
      Espacios($ffact, 7);
      fputs($ffact, $reng[$k]);
      $num_ren_articulos++;
    }
  }

  fputs($ffact, "\n");

  /* Colocar aqui las observaciones */
  if (strlen($observaciones) > $OBS_MAXCOLS) {
    $reng = array();
    $renglon_largo = $observaciones;
    /* Aplica el wrapping */
    for ($j=0; $j<$OBS_MAXRENS; $j++) {
      $semi_renglon = substr($renglon_largo, 0, $OBS_MAXCOLS-1);
      if ($char_pos=strrpos($semi_renglon, "\n")) {
        $pos_fin_reng = $char_pos;
      }
      else if($char_pos =  strrpos($semi_renglon, " ")) {
        $pos_fin_reng = $char_pos;
        while ($semi_renglon[$pos_fin_reng-1] == " ")
          $pos_fin_reng--;
      }
      $reng[$j] = substr($semi_renglon, 0, $pos_fin_reng);
      if (!empty($reng[$j]) && !strpos($reng[$j], "\n"))
        $reng[$j].= "\n";
      $reng[$j] = str_replace("\n", "\n\r", $reng[$j]);
      $renglon_largo = ltrim(substr($renglon_largo, $pos_fin_reng+1, strlen($renglon_largo)));
    }
  }
  else {
    $j = 1;
    $reng[0] = $observaciones;
  }

  for ($k=0; $k<$j; $k++) {
    Espacios($ffact, $MARGEN);
    $linea = sprintf("       %s", $reng[$k]);
    fputs($ffact, $linea);
  }

  for ($i=0; $i<$ART_MAXRENS-$num_ren_articulos-$k-1; $i++) {
    fputs($ffact, "\n");
  }
  fputs($ffact, "\r");

  /*  if (strlen($garantia) > 3) {
    Espacios($ffact, 5);
    $linea = sprintf("%s de garantia",$garantia);
    }*/
  fputs($ffact,"\n\r");
  Espacios($ffact, 15);
  fputs($ffact, "\n\n\r");

  $linea = sprintf("%cJ%c",$ESC,20);
  fputs($ffact, $linea);

  Espacios($ffact, $MARGEN);
  Espacios($ffact, 50);
  $linea = sprintf("    %9.2f\n\n\r", $subtotal);
  fputs($ffact, $linea);

  Espacios($ffact, $MARGEN);
  Espacios($ffact, 50);
  $linea = sprintf("    %9.2f\n\n\r", $iva);
  fputs($ffact, $linea);

  Espacios($ffact, $MARGEN);
  Espacios($ffact, 50);
  $linea = sprintf("    %9.2f\n\n\n\r", $total);
  fputs($ffact, $linea);

  Espacios($ffact, $MARGEN);
  $importe_letra = substr(str_cant($total,$buff), 0, $MAXIMPORT);
  Espacios($ffact, 13);
  /*  $linea = sprintf("%s---",$importe_letra);
  fputs($ffact, $linea);
  $linea = sprintf( "\n\r");
  fputs($ffact, "\n\r");*/
  $linea = sprintf("%s ",$importe_letra);
  fputs($ffact, $linea);

  //  Espacios($ffact, 15);
  $linea = sprintf("pesos %s/100 M.N.\n\r",$buff);

  fputs($ffact, $linea);
  fputs($ffact, "\n\r");

  /*  $linea = sprintf("%cJ%c",$ESC,12);
  fputs($ffact, $linea);
  Espacios($ffact, $MARGEN);
  Espacios($ffact,15);
  $linea = sprintf("Hecho con OsoPOS Web. http://punto-deventa.com/software.html\n\r");
  fputs($ffact, $linea);*/

  $linea = sprintf("%c",$FF);
  fputs($ffact, $linea);

  fclose($ffact);
}

function Crea_Ticket ($nm_ticket,
                      $art_cod,
                      $art_desc,
                      $art_cant,
                      $art_pu,
                      $art_iva_porc,
                      $art_disc,
                      $pago,
                      $monto
                      )
{
  global $TICKET_IVA_INCLUIDO;

  $impr = fopen($nm_ticket, "w");
  if (!$impr) {
    echo "<b>Error al abrir el archivo $nm_ticket</b><br></body></html>";
    exit();
  }

  if (strtoupper($IMPRESORA_TIPO) == "STAR")
    fputs($impr, sprintf("%c", 7));
  for ($i=0; $i<count($art_desc); $i++) {
    $sub_iva = ( $art_pu[$i] * ($art_iva_porc[$i]/100) );
    $linea = sprintf(" -> %s\n",$art_desc[$i]);
    fputs($impr, $linea);
    $linea = sprintf(" %5d x ", $art_cant[$i]);
    fputs($impr, $linea);
    if ($TICKET_IVA_INCLUIDO) {
      $linea = sprintf("%10.2f = %10.2f",
                       $art_pu[$i] + $sub_iva,
                       ($art_pu[$i] + $sub_iva) * $art_cant[$i]);
    }
    else {
      $linea = sprintf("%10.2f = %10.2f",
                       $art_pu[$i],
                       $art_pu[$i] * $art_cant[$i]);
    }
    fputs($impr, $linea);
    if (!$art_iva_porc[$i])
      fputs($impr, " E");
    fputs($impr, "\n");

    if ($TICKET_IVA_INCLUIDO)
      $a_pagar += (($art_pu[$i]+$sub_iva) * $art_cant[$i]);
    else
      $a_pagar += ($art_pu[$i] * $art_cant[$i]);
    $iva += ( $sub_iva * $art_cant[$i] );
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
