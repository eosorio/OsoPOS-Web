<?php /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
invent_item_gral_nuevo_pcosto.bdy. Cuerpo del m�dulo de inventarios de OsoPOS Web.

        Copyright (C) 2003 Eduardo Israel Osorio Hern�ndez

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los t�rminos de la Licencia P�blica General GNU
publicada por la Free Software Foundation: ya sea en la versi�n 2 de la
Licencia, o (a su elecci�n) en una versi�n posterior. */
?>
<h4>Nuevo precio de costo</h4>
<form action="<?php echo $PHP_SELF ?>" method="post">
<table border=1>
<colgroup>
  <col width="200"><col width="100"><col width="50"><col width="95">
  <col width="100"><col width="95">
</colgroup>
<tr>
  <th>Proveedor</th><th>Costo</th><th>% IVA</th><th>Divisa</th><th>Tiempo entrega</th><th>Clave prov.</th>
  <th>Costo env�o</th><th>Div. envio</th>
</tr>
<tr>
  <td><?php lista_proveedores(FALSE, "id_prov", "Seleccione uno") ?></td>
  <td><input type="text" size="10" name="costo1"><input type="hidden" name="codigo" value="<?php echo $codigo ?>"></td>
  <td><input type="text" size="5" name="iva_porc"></td>
  <td><select name="divisa"><?php echo lista_divisas($conn, $DIVISA_OMISION) ?></select></td>
  <td><input type="text" size="3" name="entrega1"><input type="hidden" name="boton" value="general"></td>
  <td><input type="text" size="10" name="prov_clave"><input type="hidden" name="action" value="ver"></td>
  <td><input type="text" size="10" name="costo_envio1"><input type="hidden" name="subaction" value="agrega_pcosto">
  <td><select name="divisa_env"><?php echo lista_divisas($conn, $DIVISA_OMISION) ?></select></td>
</tr>
</table>
<input type="submit" value="Agregar">
</form>
