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
  global $ESC, $FF;

  include ("include/linucs_factur_const.inc");

  $ffact = fopen($nmfact,"w");

  if (!$ffact) {
    echo "<b>Error al abrir el archivo $nmfact</b><br></body></html>";
    exit();
  }


  $linea = sprintf("%ck",$ESC);
  fputs($ffact, $linea);

  $linea = sprintf("%c",18);
  /* Cancela modo condensado */
  fputs($ffact, $linea);

  if ($tipoimp == "EPSON") {
    $linea = sprintf("%c%c",$ESC,77);
    fputs($ffact, $linea);
  }
  else {
    $linea = sprintf("%c%c",$ESC,58);
  /* 12cpi en modo Epson: ESC,77, en modo IBM: ESC,58 */
    fputs($ffact, $linea);
  }
  $linea = sprintf("%cJ%c",$ESC,800);
  /* 2/n-avos de pulgada */
  fputs($ffact, $linea);

  for ($i=0; $i<11; $i++)
    fputs($ffact, "\n");

  $linea = sprintf("%s\n",$cliente->nombre);
  Espacios($ffact, $MARGEN);
  fputs($ffact, $linea);
  $linea = sprintf("%s, %s",
                   $cliente->dom_calle, $cliente->dom_numero);
  if ( strlen($cliente->dom_inter) )
    $linea .= sprintf("-%s",  $cliente->dom_inter);
  $linea .= "\n";
  Espacios($ffact, $MARGEN);
  fputs($ffact, $linea);
  $linea = sprintf("Col. %s ", $cliente->dom_col);
  Espacios($ffact, $MARGEN);
  fputs($ffact, $linea);
  $linea = sprintf( "\n");
  fputs($ffact, $linea);
  $linea = sprintf("%s, %s", $cliente->dom_ciudad, $cliente->dom_edo);
  Espacios($ffact, $MARGEN);
  fputs($ffact, $linea);
  if (strlen($observaciones)) {
    $renglon_largo = $observaciones;
    for ($j=0; $j<$OBS_MAXRENS; $j++) {
      $semi_renglon = substr($renglon_largo, 0, $OBS_MAXCOLS);
      $pos_fin_reng = strrpos($semi_renglon, " ") ? strrpos($semi_renglon, " ") : strlen($semi_renglon) ;
      $reng[$j] = substr($semi_renglon, 0, $pos_fin_reng);
      if ($j)
        $reng[$j] .= "\n";
      $renglon_largo = substr($renglon_largo, $pos_fin_reng+1, strlen($renglon_largo));
    }
    if (strlen($cliente->dom_ciudad)+strlen($cliente->dom_edo) <= 46)
      Espacios($ffact,  46 - strlen($cliente->dom_ciudad) - strlen($cliente->dom_edo) - 2 );
    $linea = sprintf( "%s", $reng[0]); /* Observaciones */
    fputs($ffact, $linea);
  }
  else {
    $j = 1;
    $reng[0] = $observaciones;
  }

  fputs($ffact, "\n");
  $linea = sprintf("C.P. %5d", $cliente->cp);
  Espacios($ffact, $MARGEN);
  fputs($ffact, $linea);
  if (strlen($reng[1])) {
    Espacios($ffact, 36);
    $linea = sprintf( "%s", $reng[1]); /* Observaciones */
  }
  else
    $linea = sprintf( "\n");
  fputs($ffact, $linea);
  $linea = sprintf("%cJ%c",$ESC,16); /* 16 n-avos de pulgada de avance vert */
  fputs($ffact, $linea);
  $linea = sprintf("       %s",$cliente->rfc);
  Espacios($ffact, $MARGEN);
  fputs($ffact, $linea);
  if (strlen($reng[2])) {
    Espacios($ffact, 39-strlen($cliente->rfc));
    $linea = sprintf( "%s", $reng[2]); /* Observaciones */
    if (strlen($linea>18))
      $linea = substr($linea, 0, 18);
    fputs($ffact, $linea);
    Espacios($ffact, 23-strlen($linea));
  }
  else
    Espacios($ffact, 63-strlen($cliente->rfc)-strlen($reng[2]));
  $linea = sprintf("%d / %d / %d\n\n",$fecha->dia,$fecha->mes,$fecha->anio);
  fputs($ffact, $linea);
  $linea = sprintf("%cJ%c",$ESC,16);
  fputs($ffact, $linea);

  $linea = sprintf("%cJ%c",$ESC,90);
  fputs($ffact, $linea);


  for ($i=0; $i<$num_articulos; ++$i) {
    if (strlen($articulos[$i]->desc) > $DESC_MAXCOLS) {
      $reng = array();
      $renglon_largo = $articulos[$i]->desc;
      for ($j=0; $j<$DESC_MAXRENS; $j++) {
        $semi_renglon = substr($renglon_largo, 0, $DESC_MAXCOLS);
        $pos_fin_reng = strrpos($semi_renglon, " ") ? strrpos($semi_renglon, " ") : strlen($semi_renglon) ;
        $reng[$j] = substr($semi_renglon, 0, $pos_fin_reng);
        if ($j)
          $reng[$j] .= "\n";
        $renglon_largo = substr($renglon_largo, $pos_fin_reng+1, strlen($renglon_largo));
      }
    }
    else {
      $j = 1;
      $reng[0] = $articulos[$i]->desc;
    }
    $linea = sprintf("%-15s", $articulos[$i]->codigo);
    Espacios($ffact, $MARGEN);
    fputs($ffact, $linea);
    $linea = sprintf(" %5.0d",$articulos[$i]->cant);
    fputs($ffact, $linea);
    $linea = sprintf("  %-35s ",$reng[0]);
    fputs($ffact, $linea);
    $buff = strlen($reng);
    $linea = sprintf("%10.2f %11.2f\n\r",$articulos[$i]->pu,$articulos[$i]->pu*$articulos[$i]->cant);
    fputs($ffact, $linea);
    $num_ren_articulos++;
    for ($k=1; $k<$j; $k++) {
      Espacios($ffact, $MARGEN);
      Espacios($ffact, 23);
      fputs($ffact, $reng[$k]);
      $num_ren_articulos++;
    }
  }

  for ($i=0; $i<(($ART_MAXRENS)-$num_ren_articulos); ++$i)
    fputs($ffact,"\n"); /* imprime renglones no usados en articulos */

  $importe_letra = substr(str_cant($total,$buff), 0, $MAXIMPORT);
  Espacios($ffact, 20);
  $linea = sprintf("%s---",$importe_letra);
  fputs($ffact, $linea);
  $linea = sprintf( "\n");
  fputs($ffact, "\n");

  Espacios($ffact, 20);
  if ($buff>9)
    $linea = sprintf("pesos %2d/100 M.N.\n",$buff);
  else
    $linea = sprintf("pesos 0%1d/100 M.N.\n",$buff);
  fputs($ffact, $linea);
  fputs($ffact, "\n");

  $linea = sprintf("%cJ%c",$ESC,12);
  if (strlen($garantia) > 3) {
    Espacios($ffact, 5);
    $linea = sprintf("%s de garantia",$garantia);
  }
  fputs($ffact,"\n\n");
  Espacios($ffact, 80);
  $linea = sprintf("%9.2f\n\n",$subtotal);
  fputs($ffact, $linea);
  $linea = sprintf("%cJ%c",$ESC,15);
  fputs($ffact, $linea);
  Espacios($ffact, 80);
  $linea = sprintf("%9.2f\n\n",$iva);
  fputs($ffact, $linea);
  $linea = sprintf("%cJ%c",$ESC,15);
  fputs($ffact, $linea);
  Espacios($ffact,80);
  $linea = sprintf("%9.2f\n\n",$total);
  fputs($ffact, $linea);

  fputs($ffact, "\n\n\n\n");
  $linea = sprintf("%cJ%c",$ESC,12);
  fputs($ffact, $linea);
  Espacios($ffact,15);
  $linea = sprintf("Hecho con OsoPOS Web. http://punto-deventa.com/software.html\n");
  fputs($ffact, $linea);

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
