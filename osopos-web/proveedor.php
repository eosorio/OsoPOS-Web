<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Proveedores. Sub-Módulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2003,2005 Eduardo Israel Osorio Hernández

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
  include("include/pos.inc");
  include("include/passwd.inc");

}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>
  <TITLE>OsoPOS Web - Subm&oacute;dulo de proveedores</TITLE>
   <?php include("menu/menu_principal.inc"); ?>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">

</HEAD>
<BODY>
<?php 

   include("menu/menu_principal.bdy");
   echo "<br>\n";
   if (!puede_hacer($conn, $user->user, "invent_ver_prov")) {
     echo "<body>\n";
     echo "<h4>Usted no tiene permisos para accesar este módulo</h4><br>\n";
     echo "</body>\n";
     echo "</html>\n";
     exit();
   }
  if (isset($_POST['accion']))
    $accion = $_GET['accion'];
  else if (isset($_POST['accion']))
    $accion = $_POST['accion'];
/*  else
 $accion = "muestra"; */

  if ($accion == "cambia") {
    $peticion = sprintf("UPDATE proveedores SET nick='%s'", $_POST['nick']);
    if ($razon_soc)
      $peticion.= sprintf(", razon_soc='%s'", $_POST['razon_soc']);
    if ($calle)
      $peticion.= sprintf(", calle='%s'", $_POST['calle']);
    if ($colonia)
      $peticion.= sprintf(", colonia='%s'", $_POST['colonia']);
    if ($ciudad)
      $peticion.= sprintf(", ciudad='%s'", $_POST['ciudad']);
    if ($estado)
      $peticion.= sprintf(", estado='%s'", $_POST['estado']);
    if ($contacto)
      $peticion.= sprintf(", contacto='%s'", $_POST['contacto']);
    if ($email)
      $peticion.= sprintf(", email='%s'", $_POST['email']);
    if ($url)
      $peticion.= sprintf(", url='%s'", $_POST['url']);
    $peticion.= sprintf(" WHERE id=%s", $_POST['id']);

    if (!$resultado = db_query($peticion, $conn)) {
      die("<div class=\"error_f\">Error al actualizar proveedores</div>\n");
    }

    $peticion = "DELETE FROM telefonos_proveedor WHERE id_proveedor=$id";
    if (!$resultado = db_query($peticion, $conn)) {
      die("<div class=\"error_f\">Error al actualizar telefonos de proveedor</div>\n");
    }
      /* Ahora que no hay registro, se insertan */
    for ($i=0; $i<3; $i++) {
      if (!empty($_POST["tel[$i]"])) {
        $peticion = "INSERT INTO telefonos_proveedor VALUES (";
        $peticion.= sprintf("%2d, %2d, %2d, %2d,", $_POST['id'], $_POST["clave_ld[$i]"],
                            $_POST["tel[$i]"], $_POST["ext[$i]"]);
        if ($_POST["es_fax[$i]"])
          $peticion.= "'t'";
        else
          $peticion.= "'f'";
        $peticion.= ")";
        //        if (!$resultado = pg_exec($conn, $peticion)) {
         if (!$resultado = db_query($peticion, $conn)) {
           die("<div class=\"error_f>Error al actualizar telefonos de proveedor</div>\n");
        }
      }
    }
  }
  else if ($accion == "muestra"  ||  $accion == "agrega") {
    if ($accion == "muestra") {
      $peticion = "SELECT * FROM proveedores WHERE id=$id";
      //      if (!$resultado = pg_exec($conn, $peticion)) {
      if (!$resultado = db_query($peticion, $conn)) {
        /*igm*/ echo "$peticion<br>\n";
        die("<div class=\"error_f\">Error al consultar proveedores</div>");
      }
      $reng = db_fetch_object($resultado, 0);

      $val_nick =     "value=\"" . $reng->nick . "\"";
      $val_razon =    "value=\"" . $reng->razon_soc . "\"";
      $val_calle =    "value=\"" . $reng->calle . "\"";
      $val_colonia =  "value=\"" . $reng->colonia . "\"";
      $val_ciudad =   "value=\"" . $reng->ciudad . "\"";
      $val_estado =   "value=\"" . $reng->estado . "\"";
      $val_contacto = "value=\"" . $reng->contacto . "\"";
      $val_url = "value=\"" . $reng->url . "\"";
      $val_email = "value=\"" . $reng->email . "\"";
      $val_submit =  "value=\"Modificar datos\"";

      $peticion = "SELECT * FROM telefonos_proveedor WHERE id_proveedor=$id";
      if (!$resultado = db_query($peticion, $conn)) {
        echo "<div class=\"error_nf\">Error al consultar telefonos de proveedores</div>";
      }
      if ($num_ren = db_num_rows($resultado)) {
        for ($i=0;  $i<4 && $i<$num_ren; $i++) {
          $reng = db_fetch_object($resultado, $i);
          $val_clave_ld[$i] = "value=\"" . $reng->clave_ld . "\"";
          $val_tel[$i] =      "value=\"" . $reng->numero . "\"";
          $val_ext[$i]      = "value=\"" . $reng->ext . "\"";
          $val_tel_fax[$i]  = "value=$i";
          if ($reng->fax == 't')
            $val_tel_fax[$i].= " checked";
        }
      }
      $acc = "cambia";
    }
    else {
      $val_submit = "value=\"Agregar proveedor\"";
      $acc = "inserta";
	  $peticion = "SELECT max(id) as id FROM proveedores";
	  if (!$resultado = db_query($peticion, $conn)) {
		die("div class=\"error_f\">Error al consultar proveedores</div>\n");
	  }
	  $renglon = db_fetch_object($resultado, 0);
	  $id = $renglon->id + 1;
    }
    include ("forms/proveedor.inc");
    echo "<hr>\n";
  }
  else if ($accion == "inserta") {
    $peticion = "INSERT INTO proveedores (nick, razon_soc, calle, colonia, ciudad, ";
    $peticion.= sprintf("estado, contacto) VALUES ('%s', ", $_POST['nick']);
    $peticion.= sprintf("'%s', '%s', '%s', '%s',", $_POST['razon_soc'],
                        $_POST['calle'], $_POST['colonia'], $_POST['ciudad']);
    $peticion.= sprintf("'%s', '%s')", $_POST['estado'], $_POST['contacto']);
    if (!$resultado = db_query($peticion, $conn)) {
      die("<div class=\"error_f\">Error al agregar proveedor</div>\n");
    }
    $peticion = "SELECT max(id) as max_id FROM proveedores";
    if (!$resultado = db_query($peticion, $conn)) {
      echo "Error al ejecutar $peticion<br>\n";
      exit();
    }
    $renglon = db_fetch_object($resultado, 0);
    $id = $renglon->max_id;
    if ($SQL_TYPE != "postgres")
      $id++;
    
    for ($k=0; $k<4; $k++) {
      if ($tel[$k]) {
        if (in_array($k, $tel_fax)) {
          $es_fax = 't';
        }
        else
          $es_fax = 'f';
        $peticion = "INSERT INTO telefonos_proveedor (id_proveedor";
        if (!empty($_POST["clave_ld[$k]"]))
          $peticion.= ", clave_ld";
        $peticion .= ", numero";
        if (!empty($_POST["ext[$k]"]))
          $peticion.= ", ext";
        $peticion.= ", fax";
        $peticion.= sprintf(") VALUES (%d", $_POST['id']);
        if (!empty($_POST["clave_ld[$k]"]))
          $peticion.= ", " .$_POST["clave_ld[$k]"];
        $peticion.= ", " . $_POST["tel[$k]"];
        if ($ext[$k])
          $peticion.= ", " . $_POST["ext[$k]"];
        $peticion.= sprintf(", '$es_fax') ");

        if (!$resultado = db_query($peticion, $conn)) {
          die("<div class=\"error_f\">Error al agregar teléfonos de proveedor</div>\n");
          exit();
        }
      }
    }

    printf("<i>Proveedor %s agregado</i><br>", $_POST['nick']);
  }

  $peticion = "SELECT id, nick, razon_soc, calle, colonia, ciudad, estado, contacto";
  $peticion.= " FROM proveedores ORDER BY id";
  if (!$resultado = db_query($peticion, $conn)) {
    die("<div class=\"error_f\">Error al consultar proveedores</div>\n");
  }
  $num_ren_prov = db_num_rows($resultado);

  echo "<table width=\"100%\">\n";
  echo " <tr>\n";
  echo "  <th>Id.</th><th>Nick</th><th>Raz&oacute;n soc.</th><th>Calle</th>\n";
  echo "  <th>Colonia</th><th>Ciudad</th><th>Estado</th><th>Contacto</th>\n";
  echo " </tr>\n";
  for ($i=0; $i<$num_ren_prov; $i++) {
    if (!($i%4) || $i==0)
      $td_fondo = " bgcolor='#dcffdb'";
    else if (!(($i+2)%2))
      $td_fondo = " bgcolor='#fdffd3'";
    else
      $td_fondo = "";

    $reng = db_fetch_object($resultado, $i); 
    $peticion = "SELECT clave_ld, numero, ext, fax FROM ";
    $peticion.= "telefonos_proveedor WHERE id_proveedor=$reng->id";

    if (!$resultado2 = db_query($peticion, $conn)) {
      echo "<tr><td colspan=5><div class=\"error_f\">Error al consultar telefonos de proveedor</div>\n";
      echo db_errormsg($conn) . "</b></td></tr></table></body></html>\n";
      exit();
    }
    $num_ren2 = db_num_rows($resultado2);

    echo " <tr>\n";
    echo "  <td $td_fondo>";
    $href = sprintf("%s?accion=muestra&id=%d", $_SERVER['PHP_SELF'], $reng->id);
    echo "<a href=\"$href\">" . $reng->id . "</a></td>\n";
    echo "  <td $td_fondo>$reng->nick</td>\n";
    echo "  <td $td_fondo>";
    if ($reng->razon_soc)
      echo $reng->razon_soc;
    else
      echo "&nbsp;";
    echo "</td>\n";
    echo "  <td $td_fondo>";
    if ($reng->calle)
      echo $reng->calle;
    else
      echo "&nbsp;";
    echo "</td>\n";
    echo "  <td $td_fondo>";
    if ($reng->colonia)
      echo $reng->colonia;
    else
      echo "&nbsp;";
    echo "</td>\n";
    echo "  <td $td_fondo>";
    if ($reng->ciudad)
      echo $reng->ciudad;
    else
      echo "&nbsp;";
    echo "</td>\n";
    echo "  <td $td_fondo>";
    if ($reng->estado)
      echo $reng->estado;
    else
      echo "&nbsp;";
    echo "</td>\n";
    echo "  <td $td_fondo>";
    if ($reng->contacto)
      echo $reng->contacto;
    else
      echo "&nbsp;";
    echo "</td>\n";
    echo " </tr>\n";
    if ($num_ren2) {
      echo " <tr>\n  <td>&nbsp;</td>\n";
      for ($j=0; $j<$num_ren2; $j++) {
        echo "  <td $td_fondo>";
        $reng2 = db_fetch_object($resultado2, $j); 
        if (strtoupper($reng2->fax) != "F")
          echo "Fax: ";
        echo "($reng2->clave_ld)$reng2->numero";
        if (strlen($reng2->ext))
          echo " ext. $reng2->ext";
        echo "</td>\n";
      }
      echo " </tr>\n";
    }
  }
  echo "</table>\n";
  if ($i<10) {
    for ($j=0; $j<10-$i; $j++)
      echo "<br>\n";
  }

  db_close($conn);
?>


</BODY>
</HTML>
