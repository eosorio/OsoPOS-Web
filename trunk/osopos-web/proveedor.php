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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//ES"
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

    if (!$resultado = pg_exec($conn, $peticion)) {
      echo "Error al ejecutar $peticion<br>\n";
      echo pg_errormessage($conn) . "<br></body></html>\n";
      exit();
    }

    $peticion = "DELETE FROM telefonos_proveedor WHERE id_proveedor=$id";
    if (!$resultado = pg_exec($conn, $peticion)) {
      echo "Error al ejecutar $peticion<br>\n";
      echo pg_errormessage($conn) . "<br></body></html>\n";
      exit();
    }
      /* Ahora que no hay registro, se insertan */
    for ($i=0; $i<3; $i++) {
      if ($tel[$i]) {
        $peticion = "INSERT INTO telefonos_proveedor VALUES (";
        $peticion.= sprintf("%2d, %2d, %2d, %2d,", $id, $clave_ld[$i], $tel[$i], $ext[$i]);
        if ($tel_fax[$i])
          $peticion.= "'t'";
        else
          $peticion.= "'f'";
        $peticion.= ")";
        if (!$resultado = pg_exec($conn, $peticion)) {
          echo "Error al ejecutar $peticion<br>\n";
          echo pg_errormessage($conn) . "<br></body></html>\n";
          exit();
        }
      }
    }
  }
  else if ($accion == "muestra"  ||  $accion == "agrega") {
    if ($accion == "muestra") {
      $peticion = "SELECT * FROM proveedores WHERE id=$id";
      if (!$resultado = pg_exec($conn, $peticion)) {
        echo "Error al ejecutar $peticion<br>\n";
        echo pg_errormessage($conn) . "<br></body></html>\n";
        exit();
      }
      $reng = pg_fetch_object($resultado, 0);

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
      if (!$resultado = pg_exec($conn, $peticion)) {
        echo "Error al ejecutar $peticion<br>\n";
        echo pg_errormessage($conn) . "<br></body></html>\n";
        exit();
      }
      if ($num_ren = pg_numrows($resultado)) {
        for ($i=0;  $i<3 && $i<$num_ren; $i++) {
          $reng = pg_fetch_object($resultado, $i);
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
    }
    echo "<table width=\"100%\">\n";
    echo " <form action=\"$PHP_SELF?accion=$acc\" method=\"post\">\n";
    echo " <tr>\n";
    echo "  <td>ID.</td><td><input type=\"hidden\" name=id value=$id>$id</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td>Nick</td><td colspan=4><input type=text name=nick maxlength=15 size=15 $val_nick></td>\n";
    echo "  <td>Raz&oacute;n soc.</td><td colspan=4>";
    echo "<input type=text name=razon_soc maxlength=30 size=30 $val_razon></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td>Calle</td><td colspan=4><input type=text name=calle maxlength=30 size=30 $val_calle></td>\n";
    echo "  <td>Colonia</td><td colspan=4><input type=text name=colonia maxlength=25 size=25 $val_colonia></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td>Ciudad</td><td colspan=4><input type=text name=ciudad maxlength=30 size=30 $val_ciudad></td>\n";
    echo "  <td>Estado</td><td colspan=4><input type=text name=estado maxlength=30 size=30 $val_estado></td>\n";
    echo " </tr>\n";
    echo " <tr><td>&nbsp;<td>Area<td>N&uacute;mero<td>Ext.<td>&iquest;Fax?";
    echo "   <td>&nbsp;<td>Area<td>N&uacute;mero<td>Ext.<td>&iquest;Fax?";
    echo " <tr>\n";
    echo "  <td><img src=\"imagenes/telefono.gif\" width=\"30\" heigth=\"30\"></td>\n";
    echo "  <td><input type=\"text\" name=clave_ld[0] maxlength=3 size=3 $val_clave_ld[0]></td>\n";
    echo "  <td><input type=text name=tel[0] maxlength=7 size=7 $val_tel[0]></td>\n";
    echo "  <td><input type=text name=ext[0] maxlength=5 size=4 $val_ext[0]></td>\n";
    echo "  <td><input type=checkbox name=tel_fax[] $val_tel_fax[0]></td>\n";
    echo "  <td><img src=\"imagenes/telefono.gif\" width=\"30\" heigth=\"30\"></td>\n";
    echo "  <td><input type=\"text\" name=clave_ld[1] maxlength=3 size=3 $val_clave_ld[1]></td>\n";
    echo "  <td><input type=text name=tel[1] maxlength=7 size=7 $val_tel[1]></td>\n";
    echo "  <td><input type=text name=ext[1] maxlength=5 size=4 $val_ext[1]></td>\n";
    echo "  <td><input type=checkbox name=tel_fax[] $val_tel_fax[1]></td>\n";
    echo " </tr>\n <tr>\n";
    echo "  <td><img src=\"imagenes/telefono.gif\" width=\"30\" heigth=\"30\"></td>\n";
    echo "  <td><input type=\"text\" name=clave_ld[2] maxlength=[2] size=3 $val_clave_ld[2]></td>\n";
    echo "  <td><input type=text name=tel[2] maxlength=7 size=7 $val_tel[2]></td>\n";
    echo "  <td><input type=text name=ext[2] maxlength=5 size=4 $val_ext[2]></td>\n";
    echo "  <td><input type=checkbox name=tel_fax[] $val_tel_fax[2]></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td>Contacto</td>\n";
    echo "  <td colspan=4><input type=text name=contacto maxlength=40 size=40 $val_contacto></td>\n";
    echo "  <td>&nbsp;</td><td colspan=4><input type=submit $val_submit></td>\n";
    echo " </tr>\n";
    echo "</table>\n";
    echo "<hr>\n";
  }
  else if ($accion == "inserta") {
    $peticion = "INSERT INTO proveedores (nick, razon_soc, calle, colonia, ciudad,";
    $peticion.= "estado, contacto) VALUES ('$nick', '$razon_soc', '$calle', '$colonia', '$ciudad',";
    $peticion.= "'$estado', '$contacto')";
    if (!$resultado = pg_exec($conn, $peticion)) {
      echo "Error al ejecutar $peticion<br>\n";
      exit();
    }
    $peticion = "SELECT max(id) FROM proveedores";
    if (!$resultado = pg_exec($conn, $peticion)) {
      echo "Error al ejecutar $peticion<br>\n";
      exit();
    }
    $renglon = pg_fetch_object($resultado, 0);
    $id = $renglon->id + 1;
    for ($k=0; $k<3; $k++) {
      if ($tel[$k]) {
        if ($tel_fax[$k] == "on")
          $tel_fax = 't';
        else
          $tel_fax = 'f';
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
        $peticion.= ", '$tel_fax')";
        if (!$resultado = pg_exec($conn, $peticion)) {
          echo "Error al ejecutar $peticion<br>\n";
          exit();
        }
      }
    }

    echo "<i>Proveedor $nick agregado</i><br>";
  }

  $peticion = "SELECT id, nick, razon_soc, calle, colonia, ciudad, estado, contacto";
  $peticion.= " FROM proveedores ORDER BY id";
  if (!$resultado = pg_exec($conn, $peticion)) {
    echo "Error al ejecutar $peticion<br>\n";
    exit();
  }
  $num_ren_prov = pg_numrows($resultado);

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

    $reng = pg_fetch_object($resultado, $i); 
    $peticion = "SELECT \"clave_ld\", \"numero\", \"ext\", \"fax\" FROM ";
    $peticion.= "telefonos_proveedor WHERE id_proveedor=$reng->id";
    if (!$resultado2 = pg_exec($conn, $peticion)) {
      echo "<tr><td colspan=5><b>Error al ejecutar $peticion<br>\n";
      echo pg_errormessage($conn) . "</b></td></tr></table></body></html>\n";
      exit();
    }
    $num_ren2 = pg_numrows($resultado2);

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
        $reng2 = pg_fetch_object($resultado2, $j); 
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

  pg_close($conn);
?>


</BODY>
</HTML>
