<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Factur Web. Módulo de inventarios de OsoPOS Web.
        Copyright (C) 2000-2004 Eduardo Israel Osorio Hernández

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
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta name="Author" content="E. Israel Osorio Hernández">
  <TITLE>OsoPOS - Factur Web v. <? echo $factur_web_vers ?></TITLE>
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
  <style type="text/css">
    td.right_b {text-align: right}
    td.number {text-align: right}
  </style>

</HEAD>
<BODY>

<?php

  if (!isset($offset))
    $offset = 0;
  if (!isset($limit))
    $limit = 10;
  if (empty($order_by)) {
    /*igm*/ echo "Ordenando por id<br>\n";
    $order_by = "id";
  }
  if (!isset($order))
     $order = 1; /* Ascendente */



  if ($action == "muestra"  ||  $action == "agrega") {
    if ($action == "muestra") {
      $query = "SELECT id,fecha,rfc,iva,subtotal FROM $table ";
      $query.= " WHERE id=$id";
      if (!$result = db_query($query, $conn)) {
        echo "Error al ejecutar $query<br>" . db_errormsg($conn);
        exit();
      }
      $reng = db_fetch_object($result, 0);
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
    echo "  <td>Folio</td>\n";
    if (isset($id)) {
      echo "<td>$id <input type=hidden name=id $val_id></td>\n";
    }
    else {
      echo "  <td><input type=\"text\" name=id maxlength=20></td>\n";
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
    echo "  <td>Total</td>\n";
    echo "  <td><input type=text name=descuento size=10 $val_disc\"></td>\n";
    echo " </tr>\n";
    echo " <tr><td colspan=2><input type=submit $val_submit></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "<hr>\n";
  }
  else
  if ($action == "cambia") {

    $query = "UPDATE facturas_ingresos SET rfc='$rfc', subtotal=$subtotal, iva=$iva,";
	$query.= " fecha='$fecha' ";
    $query.= " WHERE id='$id'";
    if (!$result = db_query($query, $conn)) {
      echo "Error al actualizar factura.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b>Factura <i>$id $rfc</i> actualizada.</b><br>\n";
    }
  }
  if ($action == "borrar") {
    $query = "DELETE FROM articulos WHERE codigo='$codigo'";
    if (!$result = db_query($query, $conn)) {
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
  if (!$result = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $total_rows = db_num_rows($result);
  

  echo "<div align=\"center\">\n";

  echo "<table border=0>\n";
  echo "<tr>\n";
  echo "  <td>\n";
  if ($offset > 0) {
    echo "<a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset-$limit);
    echo "&order_by=$order_by&order=$order$href_prov&table=$table\">&lt;-</a>";
  }
  else
    echo "&lt;- ";
  echo "  </td>\n";

  echo "  <td>\n";
  echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
  echo "<select name=\"offset\" onchange=\"submit()\">\n";
  for ($i=1; $i<=$total_rows; $i+=$limit) {
    printf("<option value=%d", $i-1);
    if ($offset == $i-1)
      echo " selected";
    printf(">%d\n", (int)$i/$limit + 1);
  }
  echo "</select>\n";
  echo "<input type=\"hidden\" name=\"order_by\" value=\"$order_by\">\n";
  echo "<input type=\"hidden\" name=\"order\" value=\"$order\">\n";
  echo "</form>\n";
  echo "  </td>\n";

  echo "  <td>\n";
  if ($offset+$limit < $total_rows) {
    echo " <a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset+$limit);
    echo "&order_by=$order_by&table=$table&order=$order$href_prov\">-&gt;</a>";
  }
  else
    echo "-&gt;";
  echo "  </td>\n";
  echo "</tr\n";
  echo "</table\n";

  echo "</div>\n";

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


  if (!$result = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }

  if ($num_ren = db_num_rows($result)) {
    echo "<table border=0 width='100%'>\n";
    echo " <colgroup>\n";
    if ($table == "facturas_gastos") { ?>

     <col width="10"><col width="20"><col><col><col width="20%" span=3 align="char" char=".">

<?  }
    echo " </colgroup>\n";
	echo " <thead>\n";
    echo " <tr>\n";
    echo "  <th>&nbsp;\n</th>";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=id&order=";
    printf("%d&table=%s",  $order_by=="id" && !$order, $table);
    echo "$href_prov\">Folio</a></th>\n";

    echo "  <th><a href=\"$PHP_SELF?offset=0&order_by=fecha&order=";
    printf("%d&table=",  $order_by=="fecha" && !$order, $table);
    echo "$href_prov\">Fecha</a></th>\n";

    echo "<th>";
    echo "<a href=\"$PHP_SELF?offset=0&order_by=rfc&order=";
    printf("%d&table=%s",  $order_by=="rfc" && !$order, $table);
    echo "$href_prov\">R.F.C.</th>\n";

  if ($table == "facturas_ingresos") {
    echo "<th>";
    echo "<a href=\"$PHP_SELF?offset=0&order_by=razon_soc&order=";
    printf("%d&table=%s",  $order_by=="razon_soc" && !$order, $table);
    echo "$href_prov\">Nombre o razón social</th>\n";
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
      $reng = db_fetch_object($result, $i);
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
<?
  }
  else {
    echo "<i><center>No hay facturas que coincidan en la base de datos</i></center>\n";
  }

  db_close($conn);
  echo "<hr>\n";
  include("bodies/menu/general.bdy");
?>


  <div align="right">
  <a href="factur_web.php">Agregar factura</a> |
  <a href="<? echo $PHP_SELF ?>?salir=1">Salir del sistema</a>
  </div>


</BODY>
</HTML>
