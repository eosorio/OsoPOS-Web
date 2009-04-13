<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
        OsoPOS Web Sistema de punto de venta en Intranet
        Copyright (C) 1999-2009 Eduardo Israel Osorio Hernández

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
  include("include/pos-var.inc");

{
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }

  if (isset($_POST["action"]))
    $action = $_POST["action"];
  else if (isset($_GET["action"]))
    $action = $_GET["action"];
  else
    $action = "";



}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - Módulos</title>
<?php include("menu/menu_principal.inc"); ?>

   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <style type="text/css">
    div.notify {font-style: italic; color: red}
   </style>

</head>

<body>
<?php
{
  include("menu/menu_principal.bdy");


  if (empty($action) || $action == "list" ) {

    $query = "SELECT * FROM modulo order by id ASC";

    if (!$db_res = db_query($query, $conn)) {
      die("<div class=\"error_f\">Error al consultar módulos</div>");
    }

    $num_modulos = db_num_rows($db_res);
    if (db_num_rows($db_res) == 0)
      echo "<div class=\"error_nf\">No se cuenta con módulos en el catálogo</div>\n";

    echo "<table border=\"0\" cellpadding=\"3\">\n";
    echo "<tr>\n  <th>Id</th><th>Nombre</th><th>Descripción</th>\n</tr>\n";

    for ($i=0; $i < $num_modulos; $i++) {
      $modulo = db_fetch_object($db_res, $i);

      echo "<tr>\n";
      printf("  <td class=\"serie\">%3d</td><td>%s</td><td>%s</td>\n", $modulo->id, $modulo->nombre, $modulo->desc);
      echo "</tr>\n";
    }

    echo "</table>\n";
  }

}
?>
</body>
</html>
