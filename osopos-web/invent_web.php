<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Invent Web. Módulo de inventarios de OsoPOS Web.

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
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }
}
?>
<HTML>

<HEAD><TITLE>OSOPoS Web - Invent v. 0.6</TITLE></HEAD>
<BODY BGCOLOR="white" BACKGROUND="imagenes/fondo.gif"
<?
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
    $order_by = "descripcion";
  if (!isset($order))
     $order = 1; /* Ascendente */
  if(isset($depto)) {
    if ($depto!="Todos") {
      $query = "SELECT id AS id_dept FROM departamento WHERE nombre='$depto'";
      if (!$resultado = pg_exec($conn, $query)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_dept = pg_result($resultado, 0, "id_dept");
    }
    else
      unset($id_dept);
  }
  $href_dept = isset($id_dept) ? sprintf("&id_dept=%d", $id_dept) : "&depto=Todos";
  if (isset($prov)) {
    if ($prov == "Todos")
      unset($id_prov);
    else {
      $query = "SELECT id FROM proveedores WHERE nick='$prov'";
      if (!$resultado = pg_exec($conn, $query)) {
        echo "Error al ejecutar $query<br>\n";
        exit();
      }
      $id_prov = pg_result($resultado, 0, "id");
    }
  }
  $href_prov = isset($id_prov) ? sprintf("&id_prov=%d", $id_prov) : "&prov=Todos";

  $query = "SELECT id,nick FROM proveedores";
  $query.= " ORDER BY id";
  if (!$resultado = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $num_ren_prov = pg_numrows($resultado);

  $nick_prov = array();
  for ($i=0; $i<$num_ren_prov; $i++) {
    $reng = pg_fetch_object($resultado, $i);
    $id = $reng->id;
    $nick_prov[$id] = $reng->nick;
  }

  $query = "SELECT id,nombre FROM departamento ORDER BY id";
  if (!$resultado = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }

  $num_ren_depto = pg_numrows($resultado);

  $nm_depto = array();
  for ($i=0; $i<$num_ren_depto; $i++) {
    $reng = pg_fetch_object($resultado, $i);
    $id = $reng->id;
    $nm_depto[$id] = $reng->nombre;
  }


  if ($action == "inserta") {
    $query = "SELECT id FROM departamento WHERE nombre='$depto'";
    if (!$resultado = pg_exec($conn, $query)) {
      echo "Error al buscar departamento.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($resultado))
      $id_dept = pg_result($resultado, 0, id);
    else
      $id_dept = 0;

    $query = "SELECT id FROM proveedores WHERE nick='$prov'";
    if (!$resultado = pg_exec($conn, $query)) {
      echo "Error al consultar proveedores.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($resultado))
      $id_prov = pg_result($resultado, 0, id);
    else
      $id_prov = 0;

    $query = "INSERT INTO articulos VALUES ('$codigo', ";
    if (strlen($descripcion))
      $query .= "'$descripcion', ";
    else
      $query .= "'', ";
    $query.= sprintf("%f, %f, %d, %d, %d, ", $pu, $descuento, $ex, $ex_min, $ex_max);
    $query.= sprintf("%d, %d, %f, '%s', %d)", $id_prov, $id_dept, $p_costo, $prov_clave, $iva_porc);
    if (!$resultado = pg_exec($conn, $query)) {
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
      $query = "SELECT * FROM articulos WHERE codigo='$codigo'";
      if (!$resultado = pg_exec($conn, $query)) {
        echo "Error al ejecutar $query<br>" . pg_errormessage($conn);
        exit();
      }
      $reng = pg_fetch_object($resultado, 0);
      $val_cod = "value=\"$codigo\"";
      $val_desc = "value=\"$reng->descripcion\"";
      $val_pu = "value=" . $reng->pu;
      $val_p_costo = "value=" . $reng->p_costo;
      $val_disc = "value=" . $reng->descuento;
      $val_ex = "value=" . $reng->cant;
      $val_min = "value=" . $reng->min;
      $val_max = "value=" . $reng->max;
      $val_iva_porc =  sprintf("value=\"%f\"", $reng->iva_porc);
      $val_prov_clave = sprintf("value=\"%s\"", $reng->prov_clave);
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
    echo "  <td><font face=\"helvetica, arial\">Existencia min.</font>\n";
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
    echo "  <td><font face=\"helvetica, arial\">Departamento</font>\n";
    echo "  <td><font face=\"helvetica, arial\"><select name=depto>\n";
    for ($i=0; $i<$num_ren_depto; $i++) {
      if (strlen($nm_depto[$i])) {
        echo "   <option";
        if ($i == $reng->id_depto)
          echo " selected";
        echo ">$nm_depto[$i]\n";
      }
    }
    echo "  </select></font>\n";
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
    if (!$resultado = pg_exec($conn, $query)) {
      echo "Error al consultar departamentos.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($resultado))
      $id_dept = pg_result($resultado, 0, id);
    else
      $id_dept = 0;

    $query = "SELECT id FROM proveedores WHERE nick='$prov'";
    if (!$resultado = pg_exec($conn, $query)) {
      echo "Error al consultar proveedores.<br>\n$query<br>\n";
      exit();
    }
    if (pg_numrows($resultado))
      $id_prov = pg_result($resultado, 0, id);
    else
      $id_prov = 0;

    $query = "UPDATE articulos SET descripcion='$descripcion', pu=$pu, descuento=$descuento,";
    $query.= "cant=$ex, min=$ex_min, max=$ex_max, id_prov=$id_prov,";
    $query.= "id_depto=$id_dept, p_costo=$p_costo, prov_clave='$prov_clave', ";
    $query.= "iva_porc=$iva_porc WHERE codigo='$codigo'";
    if (!$resultado = pg_exec($conn, $query)) {
      echo "Error al actualizar articulos.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b>Art&iacute;culo <i>$codigo $descripcion</i> actualizado.</b><br>\n";
    }
  }
  if ($action == "borrar") {
    $query = "DELETE FROM articulos WHERE codigo='$codigo'";
    if (!$resultado = pg_exec($conn, $query)) {
      echo "Error al actualizar articulos.<br>\n$query<br>\n";
      exit();
    }
    else {
      echo "<b>Art&iacute;culo <i>$codigo</i> eliminado.</b><br>\n";
    }
  }

  $query = "SELECT * FROM articulos";
  if (isset($id_dept) || isset($id_prov))
     $query .= " WHERE ";
  $query.= isset($id_dept) ? sprintf("id_depto=%d", $id_dept) : "";
  if (isset($id_dept) && isset($id_prov))
     $query .= " AND ";
  $query.= isset($id_prov) ? sprintf("id_prov=%d", $id_prov) : "";
  if (!$resultado = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $num_arts = pg_numrows($resultado);
  
  echo "<table border=0 width=\"100%\" name=\"superior\">\n";
  echo "<tr>\n";
  echo " <td>\n";
  echo "  <form action=$PHP_SELF method=\"post\" name=selecciones>\n";
  echo "  <input type=hidden name=order_by value=\"$order_by\">\n";
  echo "  <input type=hidden name=order value=\"$order\">\n";
  echo "  <input type=hidden name=offset value=0>\n";
  echo "<font face=\"helvetica,arial\">Depto.: ";
  echo "  <select name=depto></font>\n";
  for ($i=0; $i<count($nm_depto); $i++) {
    echo "   <option";
    if ($nm_depto[$i] == $depto  ||  (isset($id_dept)  &&  $i == $id_dept))
      echo " selected";
    echo ">$nm_depto[$i]\n";
  }
  echo "   <option";
  if ($depto == "Todos"  ||  (isset($id_dept) && $id_dept == count($nm_depto))) {
    echo " selected";
  }
  echo ">Todos\n</select>\n";
  echo " \n";

  echo " <td><font face=\"helvetica,arial\">\n";
  echo "  Proveedor: ";
  echo " <select name=prov>\n";
  for ($i=0; $i<count($nick_prov); $i++) {
    echo "   <option";
    if ($nick_prov[$i] == $prov  ||  (isset($id_prov) && $i==$id_prov))
      echo " selected";
    echo ">$nick_prov[$i]\n";
  }
  echo "   <option";
  if ($prov == "Todos")
    echo " selected";
  echo ">Todos\n</select></font>\n";
?>
 <td><font face="helvetica, arial">
  <input type=submit value="Mostrar"></font>
  </form>
 <td align="rigth"><font face="helvetica, arial" color="#e0e0e0">

<?
  if ($offset > 0) {
    echo "<a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset-$limit);
    echo "&order_by=$order_by&order=$order$href_dept$href_prov\">&lt;-</a>";
  }
  else
    echo "&lt;- ";
  if ($offset) {
    echo " <a href=\"$PHP_SELF?offset=0&order_by=$order_by&order=$order$href_dept$href_prov\">";
    echo "<font face=\"helvetica,arial\">Inicio</font></a> ";
  }
  else
    echo "Inicio";
  if ($offset+$limit < $num_arts) {
    echo " <a href=\"$PHP_SELF?offset=" . sprintf("%d", $offset+$limit);
    echo "&order_by=$order_by&order=$order$href_dept$href_prov\">-&gt;</a>";
  }
  else
    echo "-&gt;";
  echo "</font>\n";
  echo "\n";
  echo "</table>\n";


  $query = "SELECT * FROM articulos";
  if (isset($id_dept) || isset($id_prov))
     $query .= " WHERE ";
  $query.= isset($id_dept) ? "id_depto=$id_dept " : "";
  if (isset($id_dept) && isset($id_prov))
     $query .= " AND ";
  $query.= isset($id_prov) ? sprintf("id_prov=%d", $id_prov) : "";
  $query.= " ORDER BY ";
  switch ($order_by) {
    case "id_dept" : 
      $query.= "\"id_depto\" ";
      break;
    default:
      $query.= "\"$order_by\" ";
  }
  $query.= $order ? "ASC" : "DESC";
  if (!$resultado = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  $total_renglones = pg_numrows($resultado);

  $query.= " LIMIT $limit OFFSET $offset";
  if (!$resultado = pg_exec($conn, $query)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }

  if ($num_ren = pg_numrows($resultado)) {
    echo "<table border=0 width='100%'>\n";
    echo " <tr>\n";
    echo "  <th>&nbsp;";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=codigo&order=";
    printf("%d",  $order_by=="codigo" && !$order);
    echo "$href_dept$href_prov\">C&oacute;digo</a></font>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=descripcion&order=";
    printf("%d",  $order_by=="descripcion" && !$order);
    echo "$href_dept$href_prov\">Descripci&oacute;n</a></font>\n";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=pu&order=";
    printf("%d",  $order_by=="pu" && !$order);
    echo "$href_dept$href_prov\">Precio</a></font>\n";
?>
   <th><font face="helvetica,arial">Des</font>
   <th><font face="helvetica,arial">Cant</font>
   <th><font face="helvetica,arial">Ex. Min</font>
   <th><font face="helvetica,arial">Ex. max</font>
<?
    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=id_prov&order=";
    printf("%d",  $order_by=="id_prov" && !$order);
    echo "$href_dept$href_prov\">Proveedor</a></font>";

    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=id_dept&order=";
    printf("%d",  $order_by=="id_dept" && !$order);
    echo "$href_dept$href_prov\">Departamento</a></font>\n";
    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=prov_clave&order=";
    printf("%d",  $order_by=="p_costo" && !$order);
    echo "$href_dept$href_prov\">Clave Prov.</a></font>\n";
    echo "  <th><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?offset=0&order_by=p_costo&order=";
    printf("%d",  $order_by=="p_costo" && !$order);
    echo "$href_dept$href_prov\">P. costo</a></font>\n";
    echo " \n";

    for ($i=0; $i<$num_ren; $i++) {
      $reng = pg_fetch_object($resultado, $i);
      $id_prov = $reng->id_prov;
      $id_dept = $reng->id_depto;
      if (!($i%4) || $i==0)
        $td_fondo = " bgcolor='#dcffdb'";
      else if (!(($i+2)%2))
        $td_fondo = " bgcolor='#fdffd3'";
      else
        $td_fondo = "";
      echo " <tr>\n";
      echo "  <td><font face=\"helvetica,arial\">\n";
      echo "   <a href=\"$PHP_SELF?order_by=$order_by&order=$order&action=borrar&offset=$offset";
      echo "$href_dept$href_prov&codigo=";
      echo $reng->codigo . "\" border=0><img src=\"imagenes/borrar.gif\" border=0></a></font>";
      echo "  <td$td_fondo><font face=\"helvetica,arial\"><a href=\"$PHP_SELF?codigo=" . $reng->codigo;
      echo "&order_by=$order_by&order=$order&action=muestra&offset=$offset$href_dept$href_prov\">";
      echo $reng->codigo . "</a></font>\n";
      echo "  <td$td_fondo><font face=\"helvetica,arial\">$reng->descripcion</font>\n";
      echo "  <td align=\"right\"$td_fondo><font face=\"helvetica,arial\">" . sprintf("%.2f", $reng->pu) . "</font>\n";
      echo "  <td align=\"center\"$td_fondo><font face=\"helvetica,arial\">$reng->descuento</font>\n";
      echo "  <td align=\"center\"$td_fondo><font face=\"helvetica,arial\">$reng->cant</font>\n";
      echo "  <td align=\"center\"$td_fondo><font face=\"helvetica,arial\">$reng->min</font>\n";
      echo "  <td align=\"center\"$td_fondo><font face=\"helvetica,arial\">$reng->max</font>\n";
      echo "  <td$td_fondo><font face=\"helvetica,arial\">";
      if ($nick_prov[$id_prov])
        echo $nick_prov[$id_prov-1];
      else
        echo "&nbsp;";
      echo "</font>\n";
      echo "  <td$td_fondo><font face=\"helvetica,arial\">";
      if ($nm_depto[$id_dept])
        echo $nm_depto[$id_dept];
      else
        echo "&nbsp;";
      echo "</font>\n";
      echo "  <td align=\"right\"$td_fondo><font face=\"helvetica,arial\">";
      if (strlen($reng->prov_clave))
        printf("%s</font>\n", $reng->prov_clave);
      else
        echo "&nbsp;</font>\n";
      echo "  <td align=\"right\"$td_fondo><font face=\"helvetica,arial\">";
      printf("%.2f</font>\n", $reng->p_costo);
      echo " \n";
    }
    echo "</table>\n";
    echo "<br>";
  }
  else {
    echo "<i><center>No hay art&iacute;culos que coincidan en la base de datos</i></center>\n";
  }


?>

<table border=0 width="100%">
<?
  for ($i=1; $i<=$total_renglones; $i+=$limit) {
    if (($i-1)%100 == 0)
      echo "<tr>";
    echo "<td align=\"center\"><font size=\"-2\">";
    if ($i-1 != $offset) {
      $fin_bloque = $i+$limit==$total_renglones ? $i+$limit-1 : $total_renglones;
      printf("<a href=\"%s?offset=%d&order_by=%s&order=%d&action=%s%s%s\">%d-%d</a> ",
             $PHP_SELF, $i-1, $order_by, $order, $action, $href_dept, $href_prov,
             $i, $fin_bloque);
    }
    else {
      printf("<font color=\"#e0e0e0\">%d-%d</font>", $i, $i+$limit-1);
    }
    echo "</font>\n";
  }
?>
</table>
<?


  pg_close($conn);
?>

  <hr>
  <div align="right"><font face="helvetica,arial">
  <a href="<? echo $PHP_SELF ?>?offset=<? echo $offset ?>&action=agrega<? echo "$href_dept$href_prov" ?>">
  Agregar producto</a> |
  <a href="depto.php">Departamentos</a> |
  <a href="proveedor.php">Proveedores</a> |
  <a href="<? echo $PHP_SELF ?>?salir=1">Salir del sistema</a>
  </font>
  </div>


</BODY>
</HTML>
