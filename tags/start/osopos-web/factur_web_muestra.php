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
/* PENDIENTE: Activar selección de proveedor */
{
  include("include/general_config.inc");
  include("invent_web.config");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }
}
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<HTML>

<HEAD><TITLE>OsoPOS - Factur Web v. <? echo $factur_web_vers ?></TITLE></HEAD>
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
  if(isset($depto)) {
    if ($depto!="Todos") {
      $query = "SELECT id AS id_dept FROM departamento WHERE nombre='$depto'";
      if (!$result = pg_exec($conn, $query)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_dept = pg_result($result, 0, "id_dept");
    }
    else
      unset($id_dept);
  }
  if (isset($prov)) {
    if ($prov == "Todos")
      unset($id_prov);
    else {
      $query = "SELECT id FROM proveedores WHERE nick='$prov'";
      if (!$result = pg_exec($conn, $query)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_prov = pg_result($result, 0, "id");
    }
  }

  $query = "SELECT id,nick FROM proveedores";
  $query.= " ORDER BY id";
  if (!$result = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $num_ren_prov = pg_numrows($result);

  $nick_prov = array();
  for ($i=0; $i<$num_ren_prov; $i++) {
    $reng = pg_fetch_object($result, $i);
    $id = $reng->id;
    $nick_prov[$id] = $reng->nick;
  }

  $query = "SELECT id,nombre FROM departamento ORDER BY id";
  if (!$result = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }

  $num_ren_depto = pg_numrows($result);

  $nm_depto = array();
  for ($i=0; $i<$num_ren_depto; $i++) {
    $reng = pg_fetch_object($result, $i);
    $id = $reng->id;
    $nm_depto[$id] = $reng->nombre;
  }


  if ($action == "inserta") {
    $query = "SELECT id FROM departamento WHERE nombre='$depto'";
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al buscar departamento.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($result))
      $id_dept = pg_result($result, 0, id);
    else
      $id_dept = 0;

    $query = "SELECT id FROM proveedores WHERE nick='$prov'";
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al consultar proveedores.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($result))
      $id_prov = pg_result($result, 0, id);
    else
      $id_prov = 0;

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
      $query = "SELECT id,fecha,rfc,iva,subtotal FROM facturas_ingresos ";
      $query.= " WHERE id=$folio";
      if (!$result = pg_exec($conn, $query)) {
        echo "Error al ejecutar $query<br>" . pg_errormessage($conn);
        exit();
      }
      $reng = pg_fetch_object($result, 0);
      $val_cod = "value=\"$codigo\"";
      $val_desc = "value=\"$reng->descripcion\"";
      $val_pu = "value=" . $reng->pu;
      $val_p_costo = "value=" . $reng->p_costo;
      $val_disc = "value=" . $reng->descuento;
      $val_ex = "value=" . $reng->cant;
      $val_min = "value=" . $reng->min;
      $val_max = "value=" . $reng->max;
      $val_iva_porc =  "value=\"" . $reng->iva_porc . "\"";
      $val_submit = "value=\"Cambiar datos\"";
      $form_action = "$PHP_SELF?order_by=$order_by&action=cambia&offset=$offset&order=$order";
    }
    else if ($action == "agrega") {
      $val_submit = "value=\"Agregar producto\"";
      $form_action = "$PHP_SELF?order_by=$order_by&action=inserta&order=$order";
    }

    echo "<table width=\"100%\">\n";
    echo "<form action=\"$form_action\" name=\"articulo\" method=POST>\n";
    echo " <tr>\n";
    echo "  <td><font face=\"helvetica, arial\">C&oacute;digo</font>\n";
    if (isset($codigo)) {
      echo "<td><font face=\"helvetica, arial\">$codigo</font> <input type=hidden name=codigo $val_cod>\n";
    }
    else {
      echo "  <td><font face=\"helvetica, arial\"><input type=\"text\" name=codigo maxlength=20></font>\n";
    }
    echo "  <td><font face=\"helvetica, arial\">Descripci&oacute;n</font>";
    echo "  <td colspan=3><font face=\"helvetica, arial\"><input type=text name=descripcion maxlength=50";
    echo " size=30 $val_desc></font>\n";
    echo " <tr>\n";
    echo "  <td><font face=\"helvetica, arial\">P.U.</font>\n";
    echo "  <td><font face=\"helvetica, arial\"><input type=text name=pu size=10 $val_pu></font>";
    echo "  <td><font face=\"helvetica, arial\">I.V.A.</font>";
    echo "  <td><font face=\"helvetica, arial\"><input type=text name=iva_porc size=2 $val_iva_porc>%</font>\n";
    echo "  <td><font face=\"helvetica, arial\">Descuento</font>\n";
    echo "  <td><font face=\"helvetica, arial\"><input type=text name=descuento size=5 $val_disc";
    echo ">%</font>\n";
    echo " \n";
    echo " <tr>\n";
    echo "  <td><font face=\"helvetica, arial\">Existencia actual</font>\n";
    echo "  <td><font face=\"helvetica, arial\"><input type=text name=ex size=4 $val_ex>\n</font>";
    echo "  <td><font face=\"helvetica, arial\">Existencia min./font>\n";
    echo "  <td><font face=\"helvetica, arial\"><input type=text size=4 name=ex_min $val_min></font>\n";
    echo "  <td><font face=\"helvetica, arial\">Existencia max.</font>\n";
    echo "  <td><font face=\"helvetica, arial\"><input type=text size=4 name=ex_max $val_max></font>\n";
    echo " <tr>\n";
    echo "  <td><font face=\"helvetica, arial\">Proveedor</font>\n";
    echo "  <td><font face=\"helvetica, arial\"><select name=prov>\n";
    for ($i=0; $i<$num_ren_prov; $i++) {
      if (strlen($nick_prov[$i])) {
        echo "   <option";
        if ($i == $reng->id_prov)
          echo " selected";
        echo ">$nick_prov[$i]</option>\n";
      }
    }
    echo "  </select></font>\n";
    echo "  <td><font face=\"helvetica, arial\">&nbsp;</font>\n";
    echo "  <td><font face=\"helvetica, arial\">&nbsp;</font>\n";
    echo "  <td><font face=\"helvetica, arial\">P. Costo</font>\n";
    echo "  <td><font face=\"helvetica, arial\"><input type=text name=p_costo size=10 $val_p_costo></font>\n";
    echo " <tr><td colspan=2><font face=\"helvetica, arial\"><input type=submit $val_submit></font>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "<hr>\n";
  }
  else
  if ($action == "cambia") {
    $query = "SELECT id FROM departamento WHERE nombre='$depto'";
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al consultar departamentos.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($result))
      $id_dept = pg_result($result, 0, id);
    else
      $id_dept = 0;

    $query = "SELECT id FROM proveedores WHERE nick='$prov'";
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al consultar proveedores.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($result))
      $id_prov = pg_result($result, 0, id);
    else
      $id_prov = 0;

    $query = "UPDATE articulos SET descripcion='$descripcion', pu=$pu, descuento=$descuento,";
    $query.= "cant=$ex, min=$ex_min, max=$ex_max, id_prov=";
    $query.= "$id_prov, id_depto=$id_dept,";
    $query.= "p_costo=$p_costo WHERE codigo='$codigo'";
    if (!$result = pg_exec($conn, $query)) {
      echo "Error al actualizar articulos.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b>Art&iacute;culo <i>$codigo $descripcion</i> actualizado.</b><br>\n";
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

  $query = "SELECT * FROM facturas_ingresos";
  if (isset($id_dept) || isset($id_prov))
     $query .= " WHERE ";
  $query.= isset($id_dept) ? sprintf("id_depto=%d", $id_dept) : "";
  if (isset($id_dept) && isset($id_prov))
     $query .= " AND ";
  $query.= isset($id_prov) ? sprintf("id_prov=%d", $id_prov) : "";
  if (!$result = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $num_arts = pg_numrows($result);
  
  echo "<table border=0 width=\"100%\" name=\"superior\">\n";
  echo "<tr>\n";
  echo " <td>\n";
  echo "  <form action=$PHP_SELF method=\"post\" name=selecciones>\n";
  echo "  <input type=hidden name=order_by value=\"$order_by\">\n";
  echo "  <input type=hidden name=order value=\"$order\">\n";
  echo "  <input type=hidden name=offset value=0>\n";
  echo "<font face=\"helvetica,arial\">&nbsp; ";
  echo "  </font>\n";

  echo " <td><font face=\"helvetica,arial\">\n";
  echo "  &nbsp;";
?>
 <td><font face="helvetica, arial">
  &nbsp;</font>
  </form>
 <td align="rigth"><font face="helvetica, arial" color="#e0e0e0">

<?
  if ($offset > 0) {
    echo "<a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset-$limit);
    echo "&order_by=$order_by&order=$order$href_prov\">&lt;-</a>";
  }
  else
    echo "&lt;- ";
  if ($offset) {
    echo " <a href=\"$PHP_SELF?offset=0&order_by=$order_by&order=$order$href_prov\">";
    echo "<font face=\"helvetica,arial\">Inicio</font></a> ";
  }
  else
    echo "Inicio";
  if ($offset+$limit < $num_arts) {
    echo " <a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset+$limit);
    echo "&order_by=$order_by&order=$order$href_prov\">-&gt;</a>";
  }
  else
    echo "-&gt;";
  echo "</font>\n";
  echo "\n";
  echo "</table>\n";


  $query = "SELECT f.*, f.subtotal+f.iva AS total, c.nombre AS razon_soc ";
  $query.= "FROM facturas_ingresos f, clientes_fiscales c";

  if (isset($periodo_inicio) || isset($periodo_fin))
     $query .= " WHERE ";

  $query.= !empty($periodo_inicio) ? "f.fecha>='$periodo_inicio' " : "";

  if (isset($periodo_inicio) && !empty($periodo_fin))
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
    echo " <tr>\n";
    echo "  <th>&nbsp;\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=id&order=";
    printf("%d",  $order_by=="id" && !$order);
    echo "$href_prov\">Folio</a></font>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=fecha&order=";
    printf("%d",  $order_by=="fecha" && !$order);
    echo "$href_prov\">Fecha</a></font>\n";

    echo "<th><font face=\"helvetica,arial\">";
    echo "<a href=\"$PHP_SELF?offset=0&order_by=rfc&order=";
    printf("%d",  $order_by=="rfc" && !$order);
    echo "$href_prov\">R.F.C.</font>\n";

    echo "<th><font face=\"helvetica,arial\">";
    echo "<a href=\"$PHP_SELF?offset=0&order_by=razon_soc&order=";
    printf("%d",  $order_by=="razon_soc" && !$order);
    echo "$href_prov\">Nombre o razón soc.</font>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=subtotal&order=";
    printf("%d",  $order_by=="subtotal" && !$order);
    echo "$href_prov\">Subtotal</a></font>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=iva&order=";
    printf("%d",  $order_by=="id_prov" && !$order);
    echo "$href_prov\">I.V.A.</a></font>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=total&order=";
    printf("%d",  $order_by=="total" && !$order);
    echo "$href_prov\">Total</a></font>\n";
    echo " \n";

    for ($i=0; $i<$num_ren; $i++) {
      $reng = pg_fetch_object($result, $i);
      if (!($i%4) || $i==0)
        $td_fondo = " bgcolor='#dcffdb'";
      else if (!(($i+2)%2))
        $td_fondo = " bgcolor='#fdffd3'";
      else
        $td_fondo = "";

      $query = sprintf("SELECT nombre FROM clientes_fiscales WHERE rfc='%s'",
                       $reng->rfc);
      $result2 = pg_exec($conn, $query);
      if ($result2 && @pg_numrows($result2)) {
        $razon_soc = pg_result($result2, 0, "nombre");
      }
      else
        $razon_soc = "&nbsp;";

      echo " <tr>\n";
      echo "  <td align=\"center\">\n";
      echo "   <a href=\"$PHP_SELF?order_by=$order_by&order=$order&action=borrar&offset=$offset";
      echo "$href_prov&codigo=";
      echo $reng->id . "\" border=0><img src=\"imagenes/borrar.gif\" border=0></a>";
      echo "  <td align=\"right\"$td_fondo><font face=\"helvetica,arial\">";
      echo "<a href=\"$PHP_SELF?codigo=" . $reng->id;
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_prov\">";
      printf("%s</a></font>\n", $reng->id);
      echo "  <td align=\"center\"$td_fondo><font face=\"helvetica,arial\">$reng->fecha</font>\n";
      echo "  <td align=\"center\"$td_fondo><font face=\"helvetica,arial\">$reng->rfc</font>\n";
      echo "  <td align=\"center\"$td_fondo><font face=\"helvetica,arial\">$razon_soc</font>\n";
      echo "  <td align=\"right\"$td_fondo><font face=\"helvetica,arial\">";
      printf("%.2f</font>\n", $reng->subtotal);
      echo "  <td align=\"right\"$td_fondo><font face=\"helvetica,arial\">";
      printf("%.2f</font>\n", $reng->iva);
      echo "  <td align=\"right\"$td_fondo><font face=\"helvetica,arial\">";
      printf("%.2f</font>\n", $reng->total);
      echo " \n";
    }
    echo "</table>\n";
    echo "<br>";
  }
  else {
    echo "<i><center>No hay facturas que coincidan en la base de datos</i></center>\n";
  }

  pg_close($conn);
?>

  <hr>
  <div align="right"><font face="helvetica,arial">
  <a href="<? echo $PHP_SELF ?>?offset=<? echo $offset ?>&action=agrega<? echo "$href_prov" ?>">
  Agregar producto</a> |
  <a href="depto.php">Departamentos</a> |
  <a href="proveedor.php">Proveedores</a> |
  <a href="<? echo $PHP_SELF ?>?salir=1">Salir del sistema</a>
  </font>
  </div>


</BODY>
</HTML>
