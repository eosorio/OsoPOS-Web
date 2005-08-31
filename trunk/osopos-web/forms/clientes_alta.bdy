<?php
{
  /* Matriz de tipos de clientes */
  $a_clientes_tipo = lista_tipo_cliente($conn);
  if (!is_array($a_clientes_tipo))
    $a_clientes_tipo = array();

  /* Matriz de estados */
  $a_estados = lista_domicilio_estados($conn);
  if (!is_array($a_estados))
    $a_estados = array();

  /* Cargamos matriz de países */
  $a_paises = lista_domicilio_paises($conn);
  if (!is_array($a_paises))
    $a_paises = array();
}
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
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
