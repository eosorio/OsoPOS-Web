<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Renta Web 0.02-1. Módulo de rentas de OsoPOS Web.

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
  include("include/general_config.inc");
  include("include/pos-var.inc");
  include("include/pos.inc");

  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS Web - Renta Web v. <? echo $factur_web_vers ?></title>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/extras.css">
   <style type="text/css">
    td.campo {font-face: helvetica,arial}
    td.nm_campo {text-align: right}
    td.right {font-face: helvetica, arial; text-align: right}
    td.right_red {text-align: right; font-face: helvetica, arial; color: red;}
    
   </style>

</head>

<?php
  if ($accion=="devolucion_express" || $accion=="renta")
    echo "<body onload=\"document.express.serie.focus()\">\n";
  else
    echo "<body>\n";

  if ($accion=="clientes") {
	$query = "SELECT distinct c.*, r.id AS id_renta, det.f_entrega AS entrega FROM clientes c, rentas r, ";
    $query.= "rentas_detalle det WHERE c.status&B'10000000' = B'10000000' AND r.cliente=c.id AND ";
    $query.= "r.status&B'10000000' != B'10000000' AND r.id=det.id ORDER BY id ASC, f_entrega ASC ";
    /*igm*/  /* OJO: En este query se consultaba el campo rentas_entrega. Hay que ver la forma
    de mostrar la fecha de entrega del producto más próximo a la fecha de la consulta */
    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al consultar clientes</div><br>";
	  $mens.= db_errormsg($conn);
      die($mens);
    } 
    include("bodies/renta_clientes.bdy");
  }
  else if ($accion=="productos") {
    $query = "SELECT det.id, det.f_entrega AS entrega, r.pedido, det.serie, ars.codigo, a.descripcion, ";
    $query.= "r.cliente, det.status ";
    $query.= "FROM rentas_detalle det, rentas r, articulos_series ars, articulos a ";
    $query.= "WHERE det.status&B'10000000' != B'10000000' AND det.id=r.id AND ars.id=det.serie ";
    $query.= "AND a.codigo=ars.codigo ORDER BY det.f_entrega ASC";

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar productos rentados</font><br>";
	  $mens.= db_errormsg($conn);
      die($mens);
    } 
    include("bodies/renta_productos.bdy");
  }
  else if ($accion=="devolucion") {
    $query = "SELECT distinct on (r.id) r.id, det.f_entrega AS entrega, r.pedido, r.cliente ";
    $query.= "FROM rentas r, rentas_detalle det WHERE det.status&B'10000000' != B'10000000' ";
    $query.= "AND det.id=r.id ORDER BY id ASC, entrega DESC;";

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar rentas</font><br>";
	  $mens.= db_errormsg($conn);
      die($mens);
    } 
    include("bodies/renta_consulta.bdy");
  }
  else if($accion=="devolucion_express") {
    include("bodies/renta_dev_express.bdy");

    if ($subaccion == "agrega" && !empty($id_renta)) {
      $query = "SELECT * FROM rentas_detalle WHERE status&B'10000000'!=B'10000000' AND id=$id_renta ";

      if (!@$db_res = db_query($query, $conn)) {
        $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar detalles de renta</font><br>";
        $mens.= db_errormsg($conn);
        die($mens);
      }
      if (db_num_rows($db_res) == 0) {
        $query = "UPDATE rentas SET status=status|B'10000000' WHERE id=$id_renta ";
      }
      if (!@$db_res = db_query($query, $conn)) {
        $mens = "<font style=\"text-size: big; font-weight: bold\">Error al actualizar registros de rentas</font><br>";
        $mens.= db_errormsg($conn);
        die($mens);
      }

      $query = "SELECT * FROM rentas WHERE cliente=$id_cliente AND status&B'10000000'!=B'10000000' ";
      if (!@$db_res = db_query($query, $conn)) {
        $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar registros de rentas</font><br>";
        $mens.= db_errormsg($conn);
        die($mens);
      }

      if (db_num_rows($db_res) == 0) {
        $query = "UPDATE clientes SET status=status&B'01111111' WHERE id=$id_cliente ";

        if (!@$db_res = db_query($query, $conn)) {
          $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar registros de rentas</font><br>";
          $mens.= db_errormsg($conn);
          die($mens);
        }
      }
    }
    include("bodies/menu/renta.bdy");
  }
  else if ($accion=="detalle_renta") {
    include("bodies/renta_detalle.bdy");
  }
  else if ($accion=="renta") {
    include("bodies/renta_salida.bdy");
  }
  else if ($accion=="estadistica") {
    include("bodies/renta_estadistica.bdy");
  }
  else if($accion=="registrar") {
    $s_pedido = date("Y-m-d H:i:s", time()); /* Fecha en formato ISO */
    $cliente = datos_cliente($conn, $id_cliente);

    $query = "INSERT INTO rentas (pedido, cliente, status) ";
    $query.= sprintf("VALUES ('%s', %d, B'00000000') ", $s_pedido, $id_cliente);

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al insertar datos de rentas</div>";
      die($mens);
    }

    $query = sprintf("SELECT id FROM rentas WHERE pedido='%s' AND ", $s_pedido);
    $query.= sprintf(" cliente=%d AND status=B'00000000' ", $id_cliente);

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">ERROR: No puedo consultar registro de la renta</div>\n";
      die($mens);
    }
    $id_renta = db_result($db_res, 0, 0);


    $query = sprintf("UPDATE clientes SET status = status|B'10000000' WHERE id=%d ", $id_cliente);

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al actualizar datos de cliente</div>\n";
      die($mens);
    }

    $i=0;
    while (list ($serie, $importe) = each ($costo)) {
      $query = sprintf("INSERT INTO rentas_detalle (id, serie, f_entrega, costo, status) VALUES ");
      $query.= sprintf("(%d, '%s', '%s', %f, B'00000000') ",
                       $id_renta, $serie, $f_entrega[$serie], $importe);

      if (!@$db_res = db_query($query, $conn)) {
        $mens = sprintf("<div class=\"error_nf\">ERROR: Al registrar renta de articulo %s.</div>\n",
                        $serie);
        echo ($mens);
      }
      else {
        $query2 = sprintf("UPDATE almacen_1 set cant=cant-1 WHERE codigo='%s' AND id_alm=%d ", 
                          $codigo[$serie], $almcen[$serie]);
        if (!@$db_res = db_query($query2, $conn)) {
          $mens = sprintf("<div class=\"error_nf\">ERROR: Al actualizar inventario.</div>\n");
          echo ($mens);
          echo $query2 . "<br>\n" . db_errormsg($db_res);
        }
        $query2 = sprintf("UPDATE articulos_series SET status=status|B'10000000' WHERE id='%s' ",
                          $serie);
        if (!@$db_res = db_query($query2, $conn)) {
          $mens = sprintf("<div class=\"error_nf\">ERROR: Al actualizar datos del producto.</div>\n");
          echo ($mens);
        }
        else
          $i++;
      }
    }

    /* Impresion de la nota de renta */
    $linea_impr = sprintf("      Israel GM Video.\n2a. Oriente No. 2-B.\n\n");
    $linea_impr.= sprintf("Cliente: %d %s %s %s.\n%s. Folio: %d\n",
                          $cliente->id, $cliente->nombres, $cliente->ap_paterno, $cliente->ap_materno,
                          $s_pedido, $id_renta);
    $linea_impr.= "---------------------------------\n";

    reset($costo);
    $itotal = 0;
    while (list ($serie, $importe) = each ($costo)) {
      $linea_impr.= sprintf("-> %-10s %s\n", $serie, $descripcion[$serie]);
      $linea_impr.= sprintf("   Entregar: %s         $%6.2f\n",
                            substr($f_entrega[$serie], 0, 10), $importe);
      $itotal+=$importe;
    }
    $linea_impr.= sprintf("                         Total: $%6.2f\n", $itotal);
    $linea_impr.= sprintf("Total de artículos rentados: %d\n", count($costo));
    $linea_impr.= "\n\nGracias por su preferencia\n\n\n\n";

    /*igm*/ echo "<pre>Salida del ticket:\n$linea_impr\n</pre>\n";
    /*igm*/ echo "<hr>\n";

    $impresion = popen("$CMD_IMPRESION -P $COLA_TICKET", "w");
    if (!$impresion) {
      echo "<div class=\"error_nf\">Error al ejecutar <i>$CMD_IMPRESION $nm_archivo</i></div><br>\n";
    }
    else {
      fputs($impresion, $linea_impr);
      pclose($impresion);
    }

    reset($costo);
    while (list ($serie, $importe) = each ($costo)) {
      $query = sprintf("UPDATE articulos_recup_costo SET ingreso=ingreso+%f WHERE serie='%s'",
                         $importe, $serie);
        
      if (!@$db_res = db_query($query, $conn)) {
        $mens = sprintf("<div class=\"error_nf\">ERROR: Al actualizar recuperación de costo de articulo %s, %s.</div>\n",
                        $serie, $descripcion[$serie]);
        echo ($mens);
      }
    }
    printf("<b>%d artículos registrados en renta</b><br>\n", $i);
    echo "<br><br>";
    

    echo "\n<hr>\n";
    include("bodies/menu/renta.bdy");

  }
  else if ($accion=="costos") {
    $query = "SELECT * FROM articulos_rentas WHERE codigo='$codigo' ";
    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al consultar catálogo de costos de renta</div><br>";
	  $mens.= db_errormsg($conn);
      die($mens);
    } 
    include("bodies/renta_costos.bdy");
  }
  else {
    include("bodies/renta_principal.bdy");
  }
  include("bodies/menu/general.bdy");
}
?>
</body>
</html>