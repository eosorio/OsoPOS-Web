<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- */
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
      "http://www.w3.org/TR/REC-html40/loose.dtd">
 
<HTML><HEAD><TITLE>OsoPOS Web - Subm&oacute;dulo de proveedores</TITLE></HEAD>
<BODY BGCOLOR="white" BACKGROUND="imagenes/fondo.gif">


<?
  if ($accion == "cambia") {
    $peticion = "UPDATE proveedores SET nick='$nick'";
    if ($razon_soc)
      $peticion.= ", razon_soc='$razon_soc'";
    if ($calle)
      $peticion.= ", calle='$calle'";
    if ($colonia)
      $peticion.= ", colonia='$colonia'";
    if ($ciudad)
      $peticion.= ", ciudad='$ciudad'";
    if ($estado)
      $peticion.= ", estado='$estado'";
    if ($contacto)
      $peticion.= ", contacto='$contacto'";
    if ($email)
      $peticion.= ", email='$email'";
    if ($url)
      $peticion.= ", url='$url'";
    $peticion.= " WHERE id=$id";

    //    if (!$resultado = pg_exec($conn, $peticion)) {
    if (!$resultado = db_query($peticion, $conn)) {
      echo "Error al ejecutar $peticion<br>\n";
      echo db_errormessage($conn) . "<br></body></html>\n";
      exit();
    }

    $peticion = "DELETE FROM telefonos_proveedor WHERE id_proveedor=$id";
    //    if (!$resultado = pg_exec($conn, $peticion)) {
    if (!$resultado = db_query($peticion, $conn)) {
      echo "Error al ejecutar $peticion<br>\n";
      echo db_errormsg($conn) . "<br></body></html>\n";
      exit();
    }
      /* Ahora que no hay registro, se insertan */
    for ($i=0; $i<3; $i++) {
      if ($tel[$i]) {
        $peticion = "INSERT INTO telefonos_proveedor VALUES (";
        $peticion.= sprintf("%2d, %2d, %2d, %2d,", $id, $clave_ld[$i], $tel[$i], $ext[$i]);
        if ($es_fax[$i])
          $peticion.= "'t'";
        else
          $peticion.= "'f'";
        $peticion.= ")";
        //        if (!$resultado = pg_exec($conn, $peticion)) {
         if (!$resultado = db_query($peticion, $conn)) {
         echo "Error al ejecutar $peticion<br>\n";
          echo db_errormsg($conn) . "<br></body></html>\n";
          exit();
        }
      }
    }
  }
  else if ($accion == "muestra"  ||  $accion == "agrega") {
    if ($accion == "muestra") {
      $peticion = "SELECT * FROM proveedores WHERE id=$id";
      //      if (!$resultado = pg_exec($conn, $peticion)) {
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al ejecutar $peticion<br>\n";
        echo db_errormsg($conn) . "<br></body></html>\n";
        exit();
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
      //      if (!$resultado = pg_exec($conn, $peticion)) {
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al ejecutar $peticion<br>\n";
        echo db_errormsg($conn) . "<br></body></html>\n";
        exit();
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
		echo "Error al ejecutar $peticion<br>\n";
		exit();
	  }
	  $renglon = db_fetch_object($resultado, 0);
	  $id = $renglon->id + 1;
    }
    include ("forms/proveedor.inc");
    echo "<hr>\n";
  }
  else if ($accion == "inserta") {
    $peticion = "INSERT INTO proveedores (nick, razon_soc, calle, colonia, ciudad,";
    $peticion.= "estado, contacto) VALUES ('$nick', '$razon_soc', '$calle', '$colonia', '$ciudad',";
    $peticion.= "'$estado', '$contacto')";
    //    if (!$resultado = pg_exec($conn, $peticion)) {
    if (!$resultado = db_query($peticion, $conn)) {
      echo "Error al ejecutar $peticion<br>\n";
      exit();
    }
    $peticion = "SELECT max(id) as max_id FROM proveedores";
    //    if (!$resultado = pg_exec($conn, $peticion)) {
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
        if ($clave_ld[$k])
          $peticion.= ", clave_ld";
        $peticion .= ", numero";
        if ($ext[$k])
          $peticion.= ", ext";
        $peticion.= ", fax";
        $peticion.= ") VALUES ($id";
        if ($clave_ld[$k])
          $peticion.= ", " .$clave_ld[$k];
        $peticion.= ", " . $tel[$k];
        if ($ext[$k])
          $peticion.= ", " . $ext[$k];
        $peticion.= ", '$es_fax')";

        if (!$resultado = db_query($peticion, $conn)) {
          echo "Error al ejecutar $peticion<br>\n";
          exit();
        }
      }
    }

    echo "<i>Proveedor $nick agregado</i><br>";
  }

  $peticion = "SELECT id, nick, razon_soc, calle, colonia, ciudad, estado, contacto";
  $peticion.= " FROM proveedores ORDER BY id";
//  if (!$resultado = pg_exec($conn, $peticion)) {
  if (!$resultado = db_query($peticion, $conn)) {
    echo "Error al ejecutar $peticion<br>\n";
    exit();
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
      echo "<tr><td colspan=5><b>Error al ejecutar $peticion<br>\n";
      echo db_errormesg($conn) . "</b></td></tr></table></body></html>\n";
      exit();
    }
    $num_ren2 = db_num_rows($resultado2);

    echo " <tr>\n";
    echo "  <td $td_fondo>";
    $href = "$PHP_SELF?accion=muestra&id=$reng->id";
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
  echo "<hr>\n";
  echo "<div align=\"right\">\n";
  echo "<a href=\"invent_web.php\">Productos</a> | \n";
  echo "<a href=\"depto.php\">Departamentos</a> | \n";
  echo "<a href=\"$PHP_SELF?accion=agrega\">Agregar proveedor</a> | ";
  echo "<a href=\"$PHP_SELF?salir=1\">Salir del sistema</a>\n";
  echo "</div>\n";

  db_close($conn);
?>


</BODY>
</HTML>
