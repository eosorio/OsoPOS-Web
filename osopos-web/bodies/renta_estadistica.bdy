<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->
<h1>Estadísticas</h1>
<table border=0 width="100%" height="500px">
<tr valign="top">
  <td>
<?php
{
  if ($subaccion=="recuperacion") {
    $limit = 20;
    //  $limit = 5; /* igm. Absurdamente corto, para probar solamente */
    if (!isset($pagina))
      $pagina = 0;

    echo "<h2>Costos de recuperación</h2>\n";
    /* Asumo que se consulta más rápido al extraer un count() que contando una por una */
    $query = "SELECT count(rc.serie) ";
    $query.= "FROM articulos_recup_costo rc, ";
    $query.= "articulos a, articulos_series s WHERE rc.serie=s.id AND ";
    $query.= "s.codigo=a.codigo ";
	
    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al consultar costos de recuperación</div>";
      /*img*/ echo "<i>$query</i><br>\n";
      die($mens);
    }
    $num_items = db_result($db_res, 0, 0);

    $query = "SELECT rc.serie, a.descripcion, rc.costo, rc.ingreso, ";
    $query.= "rc.costo-rc.ingreso AS resta FROM articulos_recup_costo rc, ";
    $query.= "articulos a, articulos_series s WHERE rc.serie=s.id AND ";
    $query.= "s.codigo=a.codigo ORDER BY resta ASC ";
	$query.= sprintf("LIMIT %d OFFSET %d ", $limit, $pagina * $limit);
	
    if (!@$db_res = db_query($query, $conn)) {
      /*igm*/ echo "<i>$query</i><br>\n";
      $mens = "<div class=\"error_f\">Error al consultar costos de recuperación</div>";
      die($mens);
    }
	$num_ren = db_num_rows($db_res);
	if ($num_ren) {
	  echo "<table width=\"100%\" cellpadding=1>\n";
	  echo "<colgroup>\n";
	  echo "  <col width=20><col width=200><col width=*><col width=150 span=2><col width=100>\n";
	  echo "</colgroup>\n";
	  echo "<tr>\n";
	  echo "  <th>&nbsp;</th><th>Serie</th><th>Descripción</th><th>Costo</th><th>Recuperación</th><th>Restante</th>\n";
	  echo "</tr>\n";
	  for ($i=0; $i<$num_ren; $i++) {
		$ren = db_fetch_object($db_res, $i);
		echo "<tr>\n";
		echo "  <td>";
		if ($ren->resta <=0 )
		  echo "<img src=\"imagenes/ok.png\" width=16 length=16>";
		else
		  echo "&nbsp;";
		echo "  </td>\n";
		printf("  <td class=\"serie\">%s</td>\n", $ren->serie);
		printf("  <td>%s</td>\n", $ren->descripcion);
		printf("  <td class=\"moneda\">%.2f</td>\n", $ren->costo);
		printf("  <td class=\"moneda\">%.2f</td>\n", $ren->ingreso);
		printf("  <td class=\"moneda\">%.2f</td>\n", $ren->resta);
		echo "</tr>\n";
	  }
	  echo "</table>\n";
?>
<br>
<table width=150 border=0 align="center">
<tr>
  <td>
  <?php if ($pagina>0) { ?>
    <form action="<?php echo $PHP_SELF ?>" method="post">
	<input type="hidden" name="accion" value="estadistica">
	<input type="hidden" name="subaccion" value="recuperacion">
	<input type="image" src="imagenes/web/botones/anterior.png">
	<input type="hidden" name="pagina" value=<?php printf("\"%d\"", $pagina-1) ?>>
	<input type="hidden" name="boton" value="anterior">
	</form>
  <?php } else echo "&nbsp;"					 ?>
  </td>

  <td>
  <?php if ($pagina*$limit+1 < $num_items) { ?>
    <form action="<?php echo $PHP_SELF ?>" method="post">
	<input type="hidden" name="accion" value="estadistica">
	<input type="hidden" name="subaccion" value="recuperacion">
	<input type="image" src="imagenes/web/botones/siguiente.png">
	<input type="hidden" name="pagina" value=<?php printf("\"%d\"", $pagina+1) ?>>
	<input type="hidden" name="boton" value="siguiente">
  <?php } else echo "&nbsp;"					 ?>
	</form>
  </td>
</tr>
</table>
<table width="100%" align="center">
<colgroup>
  <col width=20><col><col width=20><col>
</colgroup>
<tr>
  <td><img src="imagenes/ok.png" width=16 length=16></td>
  <td style="font-size: small">Costo recuperado</td>
</tr>
<tr>
  <td><img src="imagenes/web/botones/anterior.png" width=16 length=16></td>
  <td style="font-size: small">Página anterior</td>
</tr>
<tr>
  <td><img src="imagenes/web/botones/siguiente.png" width=16 length=16></td>
  <td style="font-size: small">Página siguiente</td>
</tr>
</table>

<br><br>
<?php
	}
	else
	  echo "No hay datos a reportar<br><br><hr>\n";
  }
  else {
	echo "<a href=\"$PHP_SELF?accion=estadistica&subaccion=recuperacion\">";
	echo "Costos de recuperación</a>\n";
	echo "<br><br><br>\n";
  }
  echo "  </td>\n";
  echo "</tr>\n";
  echo "</table>\n";
  include("bodies/menu/renta.bdy");
}
?>