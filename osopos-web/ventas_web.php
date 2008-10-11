<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Beta test. Módulo de pruebas de OsoPOS Web.

        Copyright (C) 2004 Eduardo Israel Osorio Hernández

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

  if (isset($_POST['action']))
    $action = $_POST['action'];


  if ($action=="add2cart") {
    $item_agregado = agrega_carrito_item($conn, $codigo, $qt);
    //    setcookie("osopos_carrito[$codigo]", $qt);
  }

  $cmd_impresion = lee_config($conn, "CMD_IMPRESION");
  $cola_ticket = lee_config($conn, "COLA_TICKET");

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
 <TITLE>OSOPoS Web - Reportes de ventas</TITLE>
<?php include("menu/menu_principal.inc"); ?>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/extras.css">
   <style type="text/css">
   </style>
 

</HEAD>
<body>
<?php
    include("menu/menu_principal.bdy");
?>
<h1>Reportes de ventas</h1>

<?php
if ($action=="imprime_ticket") {
  include("include/caja_config.inc");
  include("include/minegocio.inc");

  $query = "SELECT f.id AS folio, v.fecha, v.hora FROM folios_tickets_1 f, ventas v ";
  $query.= sprintf("WHERE f.venta=v.numero AND v.numero=%d ", $_POST['id_venta']);
  if (!$db_res = db_query($query, $conn)) {
    echo "<div class=\"error_nf\">No puedo consultar folios y fechas de ventas</div><br>\n";
  }
  else {

    $ren = db_fetch_object($db_res, 0);
    $folio = $ren->folio;
    $fecha = $ren->fecha;
    $hora = $ren->hora;
  }

  imprime_ticket_razon();

  $enc_ticket = @fopen($ARCHIVO_ENCAB_TICKET, "r");
  if (!$enc_ticket) {
    echo "<i>Advertencia: No puedo leer el encabezado de ticket</i><br>\n";
    $comando = sprintf("%s -P %s", $cmd_impresion, $cola_ticket);
    if ($impresion = popen($comando, "w")) {
      fputs($impresion, "     Sistema OsoPOS Web 2004. Linux.\n");
      fputs($impresion, "     (C)E. Israel Osorio H.\n");
      pclose($impresion);
    }
  }
  else {
    $comando = sprintf("%s -P %s %s", $cmd_impresion, $cola_ticket, $ARCHIVO_ENCAB_TICKET);
    $impresion = popen($comando, "w");
    if (!$impresion) {
      echo "<div class=\"error_nf\">Error al ejecutar comando de impresión para encabezado</div><br>\n";
    }
  }

  $buf_lp = Crea_Ticket("", $_POST['id_venta'], "", "", "", "", "", "", "", "", TRUE);

  if ( !($impresion = @popen($cmd_impresion . "-P " . $cola_ticket, "w")) )
    echo "<div class=\"error_nf\">Error al ejecutar comando de impresión para cuerpo</div><br>\n";
  else {
    fputs($impresion, $buf_lp);
    fputs($impresion, "$fecha $hora. Folio $folio, Serie A\n");
    pclose($impresion);
  }

  $pie_ticket = @fopen($ARCHIVO_PIE_TICKET, "r");
  if (!$pie_ticket) {
    echo "<i>Advertencia: No puedo leer el pie de ticket</i><br>\n";
    $comando = sprintf("%s -P %s", $cmd_impresion, $cola_ticket);
    if ($impresion = popen($comando, "w")) {
      fputs($impresion, "         Gracias por su compra.\n\n");
      fputs($impresion, "     Este es un sistema de\n          elpuntodeventa.com\n");
      fputs($impresion, "        http://elpuntodeventa.com\n");
      fputs($impresion, "        Tel. (962)626-5040\n\n\n");
      pclose($impresion);
    }
  }
  else {
    $comando = sprintf("%s -P %s %s", $cmd_impresion, $cola_ticket, $ARCHIVO_PIE_TICKET);
    $impresion = popen($comando, "w");
    if (!$impresion) {
      echo "<div class=\"error_nf\">Error al ejecutar comando de impresión para pie</div><br>\n";
    }
    else {
      echo "<i>Ticket impreso</i><br>\n";

      pclose($impresion);
      imprime_ticket_razon();
    }
  }

}
else if ($action=="imprimir") {
  $action = "";
  unset($action);
  if ($tipo_factur == $TCOMP_TICKET) {
    echo "Prepare la impresora de tickets y presione el botón continuar<br>\n";
    printf("<form action=\"\%s\" method=\"post\">\n", $_SERVER['PHP_SELF']);
    printf("<input type=\"hidden\" name=\"id_venta\" value=\"%d\">\n", $_POST['id_venta']);
    echo "<input type=\"hidden\" name=\"action\" value=\"imprime_ticket\">\n";
    echo "<input type=\"submit\" value=\"imprimir\"><br>\n";
    echo "</form>\n";
  }
}
else if ($action == "consulta") {

  if (isset($_POST['id_from']))
    $id_from = $_POST['id_from'];
  if (isset($_POST['id_to']))
    $id_to =$_POST['id_to'];

  if (isset($_POST['fecha_d_from']))
    $fecha_d_from =$_POST['fecha_d_from'];
  if (isset($_POST['fecha_m_from']))
    $fecha_m_from =$_POST['fecha_m_from'];
  if (isset($_POST['fecha_a_from']))
    $fecha_a_from =$_POST['fecha_a_from'];

  if (isset($_POST['fecha_d_to']))
    $fecha_d_to =$_POST['fecha_d_to'];
  if (isset($_POST['fecha_m_to']))
    $fecha_m_to =$_POST['fecha_m_to'];
  if (isset($_POST['fecha_a_to']))
    $fecha_a_to =$_POST['fecha_a_to'];

  if (isset($_POST['hora_h_from']))
    $hora_h_from =$_POST['hora_h_from'];
  if (isset($_POST['hora_m_from']))
    $hora_m_from =$_POST['hora_m_from'];

  if (isset($_POST['hora_h_to']))
    $hora_h_to =$_POST['hora_h_to'];
  if (isset($_POST['hora_m_to']))
    $hora_m_to =$_POST['hora_m_to'];

  if (isset($_POST['s_tipo_comp']))
    $s_tipo_comp = $_POST['s_tipo_comp'];

  if (isset($_POST['s_tipo_pago']))
    $s_tipo_pago = $_POST['s_tipo_pago'];

  $q = array(); /* Matriz de argumentos de consulta */
  $iq=0; /* Contador de elementos en matriz */
  $query = "SELECT * FROM ventas ";

  if (!empty($id_from)) {
    $q[$iq] = sprintf("numero>=%d ", $id_from);
    $iq++;
  }

  if (!empty($id_to)) {
    $q[$iq] = sprintf("numero<=%d ", $id_to);
    $iq++;
  }

  if (!empty($fecha_a_from) && !empty($fecha_m_from) && !empty($fecha_d_from)) {
    if (empty($hora_h_from))
      $hora_h_from = "00";
    if (empty($hora_m_from))
      $hora_m_from = "00";
    $q[$iq] = sprintf("fecha>='%s%s%s' AND hora>='%s:%s'",
                      $fecha_a_from, $fecha_m_from, $fecha_d_from, $hora_h_from, $hora_m_from);
    $iq++;
  }

  if (!empty($fecha_a_to) && !empty($fecha_m_to) && !empty($fecha_d_to)) {
    if (empty($hora_h_to))
      $hora_h_to = "00";
    if (empty($hora_m_to))
      $hora_m_to = "00";
    $q[$iq] = sprintf("fecha<='%s%s%s' ",
                      $fecha_a_to, $fecha_m_to, $fecha_d_to, $hora_h_to, $hora_m_to);
    $iq++;
  }

  if (!empty($s_tipo_comp)) {
	$q[$iq] = sprintf("tipo_factur=%d ", $s_tipo_comp);
    $iq++;
  }

  if (!empty($s_tipo_pago)) {
	$q[$iq] = sprintf("tipo_pago=%d ", $s_tipo_pago);
    $iq++;
  }


  if (count($q))
  $query.= sprintf("WHERE %s ", $q[0]);

  for ($i=1; $i<$iq; $i++) {
    $query.= sprintf("AND %s ", $q[$i]);
  }
  $query.= "ORDER BY numero ASC LIMIT 1000";

  if (!$db_res = db_query($query, $conn)) {
    echo "<div class=\"error_nf\">No puedo consultar datos de ventas</div><br>\n";
  }
  else {
    $num_ren = db_num_rows($db_res);
    echo "<small>Mostrando $num_ren registros</small><br><br>\n";
  }

?>


<table border=0>
  <col width=" 20">
  <col width=" 20">
  <col width=" 80">
  <col width=" 80">
  <col width=" 80">
  <col width=" 30">
  <col width="200">
  <col width=" 80">
  <col width="120">
  <col width="120">
  <col width=" 80">
  <col width=" 80">

  <thead>
    <tr>
      <th scope=col style="font-size: small"></th>
      <th scope=col style="font-size: small"></th>
      <th scope=col style="font-size: small">Id.  </th>
      <th scope=col style="font-size: small">Tipo comp.  </th>
      <th scope=col style="font-size: small">Folio  </th>
      <th scope=col style="font-size: small">Serie  </th>
      <th scope=col style="font-size: small">Fecha y hora  </th>
      <th scope=col style="font-size: small">F. pago  </th>
      <th scope=col style="font-size: small">Importe  </th>
      <th scope=col style="font-size: small">I.V.A.  </th>
      <th scope=col style="font-size: small">I.D. cajero</th>
      <th scope=col style="font-size: small">I.D. vendedor</th>
    </tr>
  </thead>
  <tbody>
<?php
  for ($i=0; $i<$num_ren; $i++) {
    $ren = db_fetch_object($db_res, $i);

    switch ($ren->tipo_factur) {
      case $TCOMP_NOTA:
        $tipo_factur = "Nota";
        break;
      case $TCOMP_FACTUR:
        $tipo_factur = "Factura";
        break;
      case $TCOMP_TICKET:
        $tipo_factur = "Ticket";
        break;
    }

    switch($ren->tipo_pago) {
      case $FPAGO_EFECT:
        $tipo_pago = "Efectivo";
	  break;
      case $FPAGO_TARJETA:
        $tipo_pago = "Tarjeta";
	  break;
      case $FPAGO_CREDITO:
        $tipo_pago = "Crédito";
	  break;
      case $FPAGO_CHEQUE:
        $tipo_pago = "Cheque";
	  break;
	}

	echo "    <tr>\n";
    echo "      <td class=\"serie\" style=\"font-size: small\">\n";
    printf("      <form action=\"%s\" method=\"post\">\n", $_SERVER['PHP_SELF']);
    printf("      <input type=\"hidden\" name=\"id_venta\" value=%d>", $ren->numero);
    echo "      <input type=\"hidden\" name=\"action\" value=\"detalle\">\n";
    echo "      <input type=\"image\" src=\"imagenes/lupa.png\" border=0 width=18 height=18>\n";
    echo "      </form>\n";
    echo "      </td>\n";

    echo "      <td class=\"serie\" style=\"font-size: small\">\n";
    printf("      <form action=\"%s\" method=\"post\">\n", $_SERVER['PHP_SELF']);
    printf("      <input type=\"hidden\" name=\"id_venta\" value=%d>\n", $ren->numero);
    echo "      <input type=\"hidden\" name=\"action\" value=\"imprimir\">\n";
    echo "      <input type=\"image\" src=\"imagenes/impresora.jpg\" border=0 width=16 height=14>\n";
    echo "      <input type=\"hidden\" name=\"tipo_factur\" value=\"$TCOMP_TICKET\">\n";
    echo "      </form>\n";
    echo "      </td>\n";
	printf("      <td class=\"serie\" style=\"font-size: small\">%d</td>\n", $ren->numero);
	printf("            <td style=\"font-size: small\">%s</td>\n", $tipo_factur);
	printf("            <td class=\"serie\" style=\"font-size: small\">%d</td>\n", $ren->folio);
	printf("            <td class=\"serie\" style=\"font-size: small\">%d</td>\n", $ren->serie);
	printf("            <td class=\"fecha\" style=\"font-size: small\">%s %s</td>\n", $ren->fecha, $ren->hora);
	printf("            <td style=\"font-size: small\">%s</td>\n", $tipo_pago);
	printf("            <td class=\"moneda\" style=\"font-size: small\">%.2f</td>\n", $ren->monto);
	printf("            <td class=\"moneda\" style=\"font-size: small\">%.2f</td>\n", $ren->iva);
	printf("            <td class=\"serie\" style=\"font-size: small\">%d</td>\n", $ren->id_cajero );
	printf("            <td class=\"serie\" style=\"font-size: small\">%d</td>\n", $ren->id_vendedor);
	echo "    </tr>\n";
  }
?>
  </tbody>
</table>
</font>
<?php
}
else if ($action=="detalle") {
  $query = sprintf("SELECT codigo, descrip, cantidad, pu, iva_porc FROM ventas_detalle WHERE id_venta=%d ",
                  $_POST['id_venta']);

  if (!$db_res = db_query($query, $conn)) {
    echo "<div class=\"error_nf\">No puedo consultar detalle de ventas</div><br>\n";
  }
  else {
    printf("<h3>Detalle de venta %d</h3>\n", $_POST['id_venta']);
    echo "<table border=0 width=\"100%\">\n";
    echo "<tr>\n";
    echo "<th>Código</th>\n";
    echo "<th>Descripción</th>\n";
    echo "<th>Cantidad</th>\n";
    echo "<th>P.U.</th>\n";
    echo "<th>% IVA</th>\n";
    echo "</tr>\n";
    for ($i=0; $i<db_num_rows($db_res); $i++) {
      $ren = db_fetch_object($db_res, $i);
      echo "<tr>\n";
      printf("  <td>%s</td>\n", htmlentities($ren->codigo));
      printf("  <td>%s</td>\n", htmlentities($ren->descrip));
      printf("  <td class=\"serie\">%.2f</td>\n", $ren->cantidad);
      printf("  <td class=\"moneda\">%.2f</td>\n", $ren->pu);
      printf("  <td class=\"moneda\">%.2f</td>\n", $ren->iva_porc);
      echo "</tr>\n";
    }
    echo "</table>\n";
    echo "<hr>\n";
    echo "<br><br>\n";
  }
}
else {
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
Mostrar ventas con los siguientes criterios:<br>
Desde la venta <input type="text" name="id_from" size="5"> y hasta la venta <input type="text" name="id_to" size="5"><br>
Por tiempo, desde <input type="text" name="fecha_d_from" size="2">/<input type="text" name="fecha_m_from" size="2">/<input type="text" name="fecha_a_from" size="4">
<input type="text" name="hora_h_from" size="2">:<input type="text" name="hora_m_from" size="2">
y hasta <input type="text" name="fecha_d_to" size="2">/<input type="text" name="fecha_m_to" size="2">/<input type="text" name="fecha_a_to" size="4">
<input type="text" name="hora_h_to" size="2">:<input type="text" name="hora_m_to" size="2">
<br>
Tipo de comprobante:
<select name="s_tipo_comp">
<option value=0>Todos
<option value=<?php echo $TCOMP_NOTA ?>>Nota
<option value=<?php echo $TCOMP_FACTUR ?>>Factura
<option value=<?php echo $TCOMP_TICKET ?>>Ticket
</select>
<br>
<br>
Forma de pago:
<select name="s_tipo_pago">
<option value=0>Todos
<option value=<?php echo $FPAGO_EFECT ?>>Efectivo
<option value=<?php echo $FPAGO_TARJETA ?>>Tarjeta
<option value=<?php echo $FPAGO_CREDITO ?>>Crédito
<option value=<?php echo $FPAGO_CHEQUE ?>>Cheque
</select>
<br>
<br>
<input type="hidden" name="action" value="consulta">
<input type="submit" value="Continuar">

</form>
<?php
   }

db_close($conn);
?>
</body>
</html>