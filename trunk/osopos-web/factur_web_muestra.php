<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Factur Web. Módulo de inventarios de OsoPOS Web.
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

{
  include("include/general_config.inc");
  include("invent_web.config");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }
  if (empty($table))
    $table = "facturas_ingresos";

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<HTML>

<HEAD>
  <TITLE>OsoPOS - Factur Web v. <? echo $factur_web_vers ?></TITLE>
  <style type="text/css">
    td#nm_campo {font-face: helvetica,arial}
  td#right_b {font-face: helvetica, arial; text-align: right}
  td.number {font-face: helvetica, arial; text-align: right}
   </style>

</HEAD>
<BODY BGCOLOR="white" BACKGROUND="imagenes/fondo.gif" <?
  if ($action == "muestra") {
    echo "onload=\"document.articulo.descripcion.focus()\"";
  }
  else if ($action == "agrega")
    echo "onload=\"document.articulo.codigo.focus()\"";
?>
>

<?

  if (!isset($offset))
    $offset = 0;
  if (!isset($limit))
    $limit = 10;
  if (!isset($order_by))
    $order_by = "id";
  if (!isset($order))
     $order = 1; /* Ascendente */



  if ($action == "inserta") {

    $query = "INSERT INTO articulos VALUES ('$codigo', ";
    if (strlen($descripcion))
      $query .= "'$descripcion', ";
    else
      $query .= "'', ";
    $query.= sprintf("%f, %f, %d, %d, %d, ", $pu, $descuento, $ex, $ex_min, $ex_max);
    $query.= sprintf("%d, %d, %f, %d)", $id_prov, $id_dept, $p_costo, $iva_porc);
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al insertar articulos.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b><center>Art&iacute;culo <i>$codigo $descripcion</i> agregado.</center></b><br>\n";
      $action = "agrega";
      unset($codigo);
    }
  }
  
  if ($action == "muestra"  ||  $action == "agrega") {
    if ($action == "muestra") {
      $query = "SELECT id,fecha,rfc,iva,subtotal FROM $table ";
      $query.= " WHERE id=$id";
      if (!$result = pg_exec($conn, $query)) {
        echo "Error al ejecutar $query<br>" . pg_errormessage($conn);
        exit();
      }
      $reng = pg_fetch_object($result, 0);
      $val_id = "value=\"$id\"";
      $val_fecha = sprintf("value=\"%s\"", $reng->fecha);
      $val_rfc = "value=" . $reng->rfc;
      $val_iva = "value=" . $reng->iva;
      $val_subt = "value=" . $reng->subtotal;
      $val_iva = "value=" . $reng->iva;
      $val_submit = "value=\"Cambiar datos\"";
      $form_action = "$PHP_SELF?table=$table&order_by=$order_by&action=cambia&offset=$offset&order=$order";
    }
    else if ($action == "agrega") {
      $val_submit = "value=\"Agregar producto\"";
      $form_action = "$PHP_SELF?table=$table&order_by=$order_by&action=inserta&order=$order";
    }

    echo "<table width=\"100%\">\n";
    echo "<form action=\"$form_action\" name=\"articulo\" method=POST>\n";
    echo " <tr>\n";
    echo "  <td><font face=\"helvetica, arial\">Folio</font></td>\n";
    if (isset($id)) {
      echo "<td><font face=\"helvetica, arial\">$id</font> <input type=hidden name=id $val_id></td>\n";
    }
    else {
      echo "  <td><font face=\"helvetica, arial\"><input type=\"text\" name=id maxlength=20></font></td>\n";
    }
?>
    <td>Fecha <small>(aaaa/mm/dd)</small></td>
    <td><input type="text" name="fecha" maxlength=10 size=10 <? echo $val_fecha ?>></td>
    <td>R.F.C.</td>
    <td>
    <input type="text" name="rfc" size=13 maxlength=13 <? echo $val_rfc ?>></td>
   </tr>
   <tr>
     <td>Subtotal:</td>
     <td><input type="text" name="subtotal" size=10 <? echo $val_subt ?>></td>
     <td>I.V.A.</td>
     <td><input type="text" name="iva" size=10 <? echo $val_iva ?>></td>
<?
    echo "  <td><font face=\"helvetica, arial\">Total</font></td>\n";
    echo "  <td><font face=\"helvetica, arial\"><input type=text name=descuento size=10 $val_disc";
    echo "></font></td>\n";
    echo " </tr>\n";
    echo " <tr><td colspan=2><font face=\"helvetica, arial\"><input type=submit $val_submit></font></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "<hr>\n";
  }
  else
  if ($action == "cambia") {

    $query = "UPDATE facturas_ingresos SET rfc='$rfc', subtotal=$subtotal, iva=$iva,";
	$query.= " fecha='$fecha' ";
    $query.= " WHERE id='$id'";
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al actualizar factura.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b>Factura <i>$id $rfc</i> actualizada.</b><br>\n";
    }
  }
  if ($action == "borrar") {
    $query = "DELETE FROM articulos WHERE codigo='$codigo'";
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al actualizar articulos.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b>Art&iacute;culo <i>$codigo</i> eliminado.</b><br>\n";
    }
  }

  $query = "SELECT * FROM $table";
/*  if (isset($id_dept) || isset($id_prov))
     $query .= " WHERE ";
  $query.= isset($id_dept) ? sprintf("id_depto=%d", $id_dept) : "";
  if (isset($id_dept) && isset($id_prov))
     $query .= " AND ";
     $query.= isset($id_prov) ? sprintf("id_prov=%d", $id_prov) : "";*/
  if (!$result = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $total_rows = pg_numrows($result);
  
  echo "<table border=0 width=\"100%\" name=\"superior\">\n";
  echo "<tr>\n";
  echo " <td>\n";
  echo "  <form action=$PHP_SELF method=\"post\" name=selecciones>\n";
  echo "  <input type=hidden name=table value=\"$table\">\n";
  echo "  <input type=hidden name=order_by value=\"$order_by\">\n";
  echo "  <input type=hidden name=order value=\"$order\">\n";
  echo "  <input type=hidden name=offset value=0>\n";
  echo "<font face=\"helvetica,arial\">&nbsp; ";
  echo "  </font>\n";

  echo " <td><font face=\"helvetica,arial\">\n";
  echo "  &nbsp;";
?>
 <td>
  &nbsp;
  </form>
 <td align="rigth">

<?
  if ($offset > 0) {
    echo "<a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset-$limit);
    echo "&order_by=$order_by&order=$order$href_prov&table=$table\">&lt;-</a>";
  }
  else
    echo "&lt;- ";
  if ($offset) {
    echo " <font color=\"#e0e0e0\">";
    echo " <a href=\"$PHP_SELF?offset=0&order_by=$order_by&order=$order$href_prov&table=$table\">";
    echo "Inicio</a></font> ";
  }
  else
    echo "Inicio";
  if ($offset+$limit < $total_rows) {
    echo " <a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset+$limit);
    echo "&order_by=$order_by&table=$table&order=$order$href_prov\">-&gt;</a>";
  }
  else
    echo "-&gt;";
  echo "</font>\n";
  echo "\n";
  echo "</table>\n";


  if ($table == "facturas_ingresos") {
    $query = "SELECT f.*, f.subtotal+f.iva AS total, c.nombre AS razon_soc ";
    $query.= "FROM $table f, clientes_fiscales c";
    $query .= " WHERE f.rfc=c.rfc ";
  }
  else {
    $query = "SELECT f.*, f.subtotal+f.iva AS total ";
    $query.= "FROM $table f";
  }

  $query.= !empty($periodo_inicio) ? "AND f.fecha>='$periodo_inicio' " : "";

  if (!empty($periodo_fin))
     $query .= " AND ";

  $query.= !empty($periodo_fin) ? sprintf("f.fecha<='%s'", $periodo_fin) : "";
  $query.= " ORDER BY ";
  if ($order_by != "total"  &&  $order_by != "razon_soc")
    $query.= "f.";
  $query.= "$order_by ";
  $query.= $order ? "ASC" : "DESC";
  $query.= " LIMIT $limit OFFSET $offset";


  if (!$result = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }

  if ($num_ren = pg_numrows($result)) {
    echo "<table border=0 width='100%'>\n";
    echo " <colgroup>\n";
    if ($table == "facturas_gastos") { ?>

     <col width="10"><col width="20"><col><col><col width="20%" span=3 align="char" char=".">

<?  }
    echo " </colgroup>\n";
	echo " <thead>\n";
    echo " <tr>\n";
    echo "  <th>&nbsp;\n</th>";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=id&order=";
    printf("%d&table=%s",  $order_by=="id" && !$order, $table);
    echo "$href_prov\">Folio</a></font></th>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=fecha&order=";
    printf("%d&table=",  $order_by=="fecha" && !$order, $table);
    echo "$href_prov\">Fecha</a></font></th>\n";

    echo "<th><font face=\"helvetica,arial\">";
    echo "<a href=\"$PHP_SELF?offset=0&order_by=rfc&order=";
    printf("%d&table=%s",  $order_by=="rfc" && !$order, $table);
    echo "$href_prov\">R.F.C.</font></th>\n";

  if ($table == "facturas_ingresos") {
    echo "<th><font face=\"helvetica,arial\">";
    echo "<a href=\"$PHP_SELF?offset=0&order_by=razon_soc&order=";
    printf("%d&table=%s",  $order_by=="razon_soc" && !$order, $table);
    echo "$href_prov\">Nombre o razón social</font></th>\n";
  }

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=subtotal&order=";
    printf("%d&table=%s",  $order_by=="subtotal" && !$order, $table);
    echo "$href_prov\">Subtotal</a></font></th>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=iva&order=";
    printf("%d&table=%s",  $order_by=="id_prov" && !$order, $table);
    echo "$href_prov\">I.V.A.</a></font></th>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=total&order=";
    printf("%d&table=%s",  $order_by=="total" && !$order, $table);
    echo "$href_prov\">Total</a></font></th>\n";
    echo " </tr>\n";

	echo " </thead>\n\n";
	echo " <tbody>\n";

    for ($i=0; $i<$num_ren; $i++) {
      $reng = pg_fetch_object($result, $i);
      if (!($i%4) || $i==0)
        $td_fondo = " bgcolor='#dcffdb'";
      else if (!(($i+2)%2))
        $td_fondo = " bgcolor='#fdffd3'";
      else
        $td_fondo = "";

      $razon_soc = strlen($reng->razon_soc) ? $reng->razon_soc : "&nbsp;";
      $fecha = strlen($reng->fecha) ? $reng->fecha : "&nbsp";

      echo " <tr>\n";
      echo "  <td>\n";
      echo "   <a href=\"$PHP_SELF?order_by=$order_by&order=$order&action=borrar&offset=$offset";
      echo "$href_prov&table=$table&codigo=";
      echo $reng->id . "\" border=0><img src=\"imagenes/borrar.gif\" border=0></a></td>";
      echo "  <td$td_fondo><font face=\"helvetica,arial\">";
      printf("<a href=\"$PHP_SELF?id=%s&table=%s", $reng->id, $table);
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_prov\">";
      printf("%s</a></font></td>\n", $reng->id);
      echo "  <td$td_fondo><font face=\"helvetica,arial\">$fecha</font></td>\n";
      echo "  <td$td_fondo><font face=\"helvetica,arial\">$reng->rfc</font></td>\n";

      if ($table == "facturas_ingresos")
        echo "  <td$td_fondo><font face=\"helvetica,arial\">$razon_soc</font></td>\n";

      echo "  <td $td_fondo class=\"number\">";
      printf("%.2f</td>\n", $reng->subtotal);
      echo "  <td $td_fondo class=\"number\">";
      printf("%.2f</td>\n", $reng->iva);
      echo "  <td $td_fondo class=\"number\">";
      printf("%.2f</td>\n", $reng->total);
      echo " </tr>\n";
    }
?>
 </tbody>
</table>
<br>
<table border=0 width="100%">
<?
  for ($i=1,$j=1; $i<=$total_rows; $i+=$limit, $j++) {
    if (($i-1)%($limit*10) == 0) {
      if ($i>1)
        echo " </tr>\n";
      echo " <tr>\n";
    }
    echo "  <td align=\"center\"><font size=\"-2\">";
    if ($i-1 != $offset) {
      $block_end = $i+$limit!=$total_rows ? $i+$limit-1 : $total_rows;
      printf("<a href=\"%s?offset=%d&order_by=%s&order=%d&action=%s%s%s\">%d</a>",
             $PHP_SELF, $i-1, $order_by, $order, $action, $href_dept, $href_prov,
             $j);
    }
    else {
      printf("<font color=\"#e0e0e0\">%d</font>", $j);
    }
    echo "</font></td>\n";
  }
    if (($i-1)%($limit*10) != 0)
      echo "  <td>&nbsp;</td>\n";
  echo " </tr>\n";
?>
</table>
<?
  }
  else {
    echo "<i><center>No hay facturas que coincidan en la base de datos</i></center>\n";
  }

  pg_close($conn);
?>

  <hr>
  <div align="right"><font face="helvetica,arial">
  <a href="factur_web.php">Agregar factura</a> |
  <a href="<? echo $PHP_SELF ?>?salir=1">Salir del sistema</a>
  </font>
  </div>


</BODY>
</HTML>
