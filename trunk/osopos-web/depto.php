<?  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
 Depto. Sub-Módulo de Inventarios de OsoPOS Web.

        Copyright (C) 2000,2002 Eduardo Israel Osorio Hernández
        desarrollo@elpuntodeventa.com

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
  include("include/general_config.inc");
  include("include/pos.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }
  if ($PROGRAMA == "web")
    $PAGE_TITLE = "OsoPOS Web - Subm&oacute;dulo de departamentos";
  else
    $PAGE_TITLE = "VideoPOS - Subm&oacute;dulo de g&eacute;neros";

?>

<!doctype html public "-//w3c//dtd html 4.01 transitional//en">
<html>
<head>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">

<title><? echo $PAGE_TITLE ?></title>
</head>
<body>


<?
  if ($accion == "cambia" && puede_hacer($conn, $user->user, "invent_depto_renombrar")) {
    $query = sprintf("UPDATE departamento set nombre='%s' WHERE id=%d",
                        $nmdepto, $id);

    if (!$result = db_query($query, $conn)) {
      echo "<b>Error al ejecutar $query</b><br></body></html>\n";
      exit();
    }
    if (db_affected_rows($result)) {
      printf("<center><i>Departamento %d, %s actualizado</i></center>\n",
      $id, stripslashes($nmdepto));
    }
    

  }
  else if ($accion == "muestra"  ||  $accion == "agrega") {
    if ($accion == "muestra") {
      $val_nmdepto = sprintf("value=\"$s\"", stripslashes($nmdepto));
      $val_submit =  "value=\"Modificar nombre\"";
      $acc = "cambia";
    }
    else {
      $val_submit = "value=\"Agregar departamento\"";
      $acc = "inserta";
    }
    echo "<table width=\"100%\">\n";
    echo " <form action=\"$PHP_SELF\" method=\"post\">\n";
    echo " <tr>\n";
    echo "  <td>ID.<td>$id <input type=\"hidden\" name=\"id\" value=\"$id\">\n";
    echo "  <td><input type=\"text\" name=\"nmdepto\" maxlength=25 size=20 $val_nmdepto>\n";
    echo "  <input type=\"hidden\" name=\"accion\" value=\"$acc\">\n";
    echo "</table>\n";
    echo "<hr>\n";
  }
  else if ($accion == "inserta") {
    $peticion = sprintf("INSERT INTO departamento (nombre) VALUES ('%s')",
                        addslashes($nmdepto));
    //    if (!$resultado = pg_exec($conn, $peticion)) {
    if (!$resultado = db_query($peticion, $conn)) {
      echo "<b>Error al ejecutar $peticion</b><br></body></html>\n";
      exit();
    }
    echo "<i>Departamento $nmdepto agregado</i><br>";
  }

  $peticion = "SELECT id, nombre FROM departamento ORDER BY id";
  if (!$resultado = db_query($peticion, $conn)) {
    echo "Error al ejecutar $peticion<br>\n";
    exit();
  }
  $num_ren_prov = db_num_rows($resultado);

  echo "<table width=\"80%\">\n";
  echo " <tr>\n";
  echo "  <th width=\"5%\">Id.</th><th>Departamento</th>\n";
  echo " \n";
  for ($i=0; $i<$num_ren_prov; $i++) {
    if (!($i%4) || $i==0)
      $td_fondo = " bgcolor='#dcffdb'";
    else if (!(($i+2)%2))
      $td_fondo = " bgcolor='#fdffd3'";
    else
      $td_fondo = "";

    $reng = db_fetch_object($resultado, $i); 

    echo " <tr>\n";
    echo "  <td align=\"center\"$td_fondo>";
    $href = "$PHP_SELF?accion=muestra&id=$reng->id&nmdepto=$reng->nombre";
    $href = str_replace(" ", "%20", htmlentities($href));
    echo "<a href=\"$href\">" . $reng->id . "</a>\n";
    echo "  <td $td_fondo>";
    if ($reng->nombre)
      echo $reng->nombre;
    else
      echo "Sin definir";
    echo "\n";
  }
  echo "</table>\n";
  if ($i<10) {
    for ($j=0; $j<10-$i; $j++)
      echo "<br>\n";
  }
  echo "<hr>\n";
  echo "<div align=\"right\">\n";
  echo "<a href=\"invent_web.php\">Productos</a> | \n";
  echo "<a href=\"$PHP_SELF?accion=agrega\">Agregar departamento</a> | ";
  echo "<a href=\"proveedor.php\">Proveedores</a> | ";
  echo "<a href=\"$PHP_SELF?salir=1\">Salir del sistema</a>\n";
  echo "</div>\n";

  db_close($conn);
?>


</BODY>
</HTML>
