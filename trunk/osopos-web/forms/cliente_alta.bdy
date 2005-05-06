<?php
{
  /* Cargamos matriz de tipos de clientes */
  $query = "SELECT * FROM cliente_tipo ORDER BY id ASC";
  if (!$resultado = db_query($query, $conn)) {
    $mens = "<div class=\"error_nf\">Error al consultar tipos de clientes</div>\n";
  }
  else {
    $a_clientes_tipo = array();
    $res_maxr = db_num_rows($resultado);

    for ($i=0; $i < $res_maxr; $i++) {
      $ren = db_fetch_object($resultado, $i);
      $a_clientes_tipo[$ren->id] = $ren->tipo;
    }
  }

  /* Cargamos matriz de estados */
  $query = "SELECT * FROM domicilio_estados ORDER BY id ASC";
  if (!$resultado = db_query($query, $conn)) {
    $mens = "<div class=\"error_nf\">Error al consultar estados (domicilios)</div>\n";
  }
  else {
    $a_estados = array();
    $res_maxr = db_num_rows($resultado);

    for ($i=0; $i < $res_maxr; $i++) {
      $ren = db_fetch_object($resultado, $i);
      $a_estados[$ren->id] = $ren->nombre;
    }
  }
  
  /* Cargamos matriz de países */
  $query = "SELECT * FROM domicilio_paises ORDER BY id ASC";
  if (!$resultado = db_query($query, $conn)) {
    $mens = "<div class=\"error_nf\">Error al consultar países</div>\n";
  }
  else {
    $a_paises = array();
    $res_maxr = db_num_rows($resultado);

    for ($i=0; $i < $res_maxr; $i++) {
      $ren = db_fetch_object($resultado, $i);
      $a_paises[$ren->id] = $ren->nombre;
    }
  }
  


  if ($accion=="agregar") {

    $query = "INSERT INTO clientes (nombres, ap_paterno, ap_materno, tipo_cliente, ";
    $query.= "sexo, nombre_comer, dom_principal, email, url, telefono1, telefono2, ";
    $query.= "fax, contacto, observaciones, rfc) VALUES ( ";
    $query.= sprintf("'%s', '%s', '%s', %d, ",
                      $nombres, $ap_paterno, $ap_materno, $tipo_cliente);
    $query.= sprintf("'%s', '%s', %d, '%s', '%s', '%s', '%s', ",
		     $sexo, $nombre_comer, $dom_principal, $email, $url,
		     $telefono1, $telefono2);
    $query.= sprintf("'%s', '%s', '%s', '%s') ", $fax, $contacto, $observaciones, $rfc);

    if (!$resultado = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al insertar datos de cliente</div>\n";
      die($mens);
    }
    
    $query = "SELECT id FROM clientes WHERE nombres='$nombres' AND ap_paterno='$ap_paterno' ";
    $query.= "AND ap_materno='$ap_materno' AND nombre_comer='$nombre_comer' ";

    if (!$resultado = db_query($query, $conn)) {
      $mens = "<div class=\"error_nf\">Error al consultar datos de cliente</div>\n";
    }
    else {
      $id_cliente = db_result($resultado, 0, "id");

      $query = "INSERT INTO domicilios (id_cliente, dom_nombre, dom_calle, dom_numero, dom_inter, ";
      $query.= "dom_col, dom_mpo, dom_ciudad, dom_edo_id, dom_cp, ";
      $query.= "dom_pais_id, dom_telefono) VALUES (";
      $query.= sprintf("%d, '%s', '%s', '%s', '%s', ",
                       $id_cliente, $dom_nombre, $dom_calle, $dom_numero, $dom_inter);
      $query.= sprintf("'%s', '%s', '%s', %d, %d, ", 
                       $dom_col, $dom_mpo, $dom_ciudad, $dom_edo_id, $dom_cp);
      $query.= sprintf("%d, '%s')", $dom_pais_id, $dom_telefono);

      if (!$resultado = db_query($query, $conn)) {
        $mens = "<div class=\"error_nf\">Error al insertar domicilio del cliente $id_cliente</div>\n";
      }
      else {
        $query = "SELECT id FROM domicilios WHERE id_cliente=$id_cliente and dom_nombre='$dom_nombre' ";

        if (!$resultado = db_query($query, $conn)) {
          $mens = "<div class=\"error_nf\">Error al consultar domicilio del cliente $id_cliente</div>\n";
        }
        else {
          $id_domicilios = db_result($resultado, 0, "id");

          $query = "UPDATE clientes SET dom_principal=$id_domicilios WHERE id=$id_cliente ";


          if (!$resultado = db_query($query, $conn)) {
            $mens = "<div class=\"error_nf\">Error al resgitrar domicilio principal del cliente</div>\n";
          }
 
        } /* fin de consulta de id domicilio principal */
      }  /* fin de inserción de domicilio de cliente */
    }
  }
}
?>
<form action="<?php echo $PHP_SELF ?>" method="post">
<table>
  <tbody>
    <tr>
      <td>Nombres  </td>
      <td colspan=3><input type="text" name="nombres" size=60> </td>
    </tr>
    <tr>
      <td>Ap. paterno  </td>
      <td><input type="text" name="ap_paterno"> </td>
      <td>Ap. Materno  </td>
      <td><input type="text" name="ap_materno"> </td>
    </tr>
    <tr>
      <td>Tipo de cliente  </td>
      <td><select name="tipo_cliente">
<?php
while(list ($id_tipo, $valor) = each($a_clientes_tipo))
  echo "<option value=$id_tipo>$id_tipo: $valor\n";
?>
       </select>
      </td>
      <td>Sexo  </td>
      <td><select name="sexo">
       <option value='M' selected>M
       <option value='F'>F
       </select></td>
    </tr>
    <tr>
      <td>Nombre comercial  </td>
      <td colspan="2"><input type="text" name="nombre_comercial" size=60></td>
      <td>&nbsp;  </td>
    </tr>
    <tr>
      <td>Página Web  </td>
      <td><input type="text" name="url" size=40>  </td>
      <td>email  </td>
      <td><input type="text" name="email">  </td>

    </tr>
    <tr>
      <td>Teléfono 1  </td>
      <td><input type="text" name="telefono1"></td>
      <td>Teléfono 2  </td>
      <td><input type="text" name="telefono2"></td>
    </tr>
    <tr>
      <td>Fax  </td>
      <td><input type="text" name="fax">  </td>
      <td>Contacto  </td>
      <td><input type="text" name="contacto"></td>
    </tr>
    <tr>
      <td>R.F.C.</td>
      <td colspan="3"><input type="text" name="rfc" size="13"></td>
    </tr>
    <tr>
      <td>Observaciones</td>
      <td colspan="3"><input type="text" name="observaciones" size="100"></td>
    </tr>
   </tbody>
</table>
<br>
Domicilio principal:<br>
<input type="hidden" name="dom_nombre" value="Principal">
<table border=0>
<tr>
  <td>Calle:</td>
  <td colspan=3><input type="text" name="dom_calle" size=60></td>
</tr>
<tr>
  <td>Número exterior:</td>
  <td><input type="text" name="dom_numero"></td>
  <td>Número interior:</td>
  <td><input type="text" name="dom_inter"></td>
</tr>
<tr>
  <td>Colonia:</td>
  <td><input type="text" name="dom_col"></td>
  <td>Municipio/delegación:</td>
  <td><input type="text" name="dom_mpo"></td>
</tr>
<tr>
  <td>Ciudad:</td>
  <td><input type="text" name="dom_ciudad"></td>
  <td>Estado/Entidad:</td>
  <td><select name="dom_edo_id">
<?php
      while(list ($id_edo, $valor) = each($a_estados)) {
        echo "<option value=$id_edo";
        if ($ESTADO_OMISION==$valor)
          echo " selected";
        echo ">$valor\n";
      }
?>
</select>
</td>
</tr>

<tr>
  <td>País:</td>
  <td><select name="dom_pais_id">
<?php
      while(list ($id_pais, $valor) = each($a_paises)) {
        echo "<option value=$id_pais";
        if ($PAIS_OMISION==$valor)
          echo " selected";
        echo ">$valor\n";
      }
?>
    </select>
  </td>
  <td>C.P.</td>
  <td><input type="text" name="dom_cp" size=5></td>
</tr>

<tr>
  <td>Teléfono</td>
  <td colspan=3><input type="text" name="dom_telefono"></td>
</tr>
</table>

<input type="hidden" name="accion" value="agregar">
<input type="submit" value="Enviar">
</form>
