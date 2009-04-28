<h1>Costos de renta y tiempos de entrega</h1>
<table width="100%" border="0">
<colgroup>
  <col width="100px"><col width="*">
</colgroup>
<tr>
  <td class="item_tit">Código</td>
  <td><?php echo $codigo ?></td>
</tr>
<tr>
  <td class="item_tit">Descripción</td>
  <td><?php echo articulo_descripcion($conn, $codigo) ?></td>
</tr>
</table>
<br>
<?php
{

  $p_renta = array();

  if ($subaction == "editar")
	echo "<form action=\"$_SERVER['PHP_SELF']\" method=\"post\">\n";
?>
<table width="100%" border=1>
<colgroup>
  <col width="200px"><col width="*" span=7>
</colgroup>
<tr>
  <th>&nbsp;</th><th>Domingo</th><th>Lunes</th><th>Martes</th><th>Miercoles</th>
  <th>Jueves</th><th>Viernes</th><th>Sábado</th>
</tr>


<?php
  if (db_num_rows($db_res)==1 && $ren['dia']==0) {
    for ($i=1; $i<6; $i++) {
      $celda = sprintf("pu%d", $i);
      printf("<tr>\n");
      printf("  <td class=\"item_tit\">Importe %d</td>\n", $i);
      printf("  <td class=\"bg2_center\" colspan=7>%.2f</td>\n", $ren[$celda]);
      printf("</tr>\n");
    }
  }
?>
</table>

<br>
<table border=0 align="right">
<colgroup>
  <col width="30px" span=2>
</colgroup>
<tr>
  <td><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
      <input type="image" src="imagenes/lapiz.png">
	  <input type="hidden" name="action" value="ver">
	  <input type="hidden" name="subaction" value="editar">
	  <input type="hidden" name="codigo" value="<?php echo $codigo ?>">
      <input type="hidden" name="boton" value="prenta">
	  </form>
  </td>
  <td><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
      <input type="image" src="imagenes/lupa.png">
	  <input type="hidden" name="action" value="ver">
	  <input type="hidden" name="subaction" value="consultar">
	  <input type="hidden" name="codigo" value="<?php echo $codigo ?>">
      <input type="hidden" name="boton" value="prenta">
	  </form>
  </td>
</tr>
</table>
<br>
<br>
<?php } ?>