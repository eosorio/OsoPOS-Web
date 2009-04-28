<!-- -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- -->
<h1>Renta de productos</h1>
<table border=0 width="100%" height="500px">
<tr>
  <td valign="top">
<?php
{
  if (empty($id_cliente)) { ?>
<form action=<?php echo "\"$_SERVER['PHP_SELF']\"" ?> method="post">
Indique número de cliente: 
<input type="hidden" name="accion" value="renta" />
<input type="hidden" name="subaccion" value="prever" />
<input type="text" name="id_cliente" size="5" />
</form>

ó
<br>
<table border="0" cellpadding="10">
<tr>
  <td><a href="cliente.php">Cliente nuevo</a></td>
  <td><a href="cliente.php?accion=consulta">Consulta de clientes</a></td>
</tr>
</table>
<?php
  }
  else {
    $cliente = datos_cliente($conn, $id_cliente);
    if (isset($subaccion) && $subaccion=="registrar")
      echo "Confirme datos y presione <i>Registrar</i><br>\n";
?>
<table>
<tr>
  <th>Cliente</th><th>Nombre</th>
</tr>
<tr>
  <td class="serie"><?php echo $cliente->id ?></td>
  <?php printf("<td>%s %s</td>\n", $cliente->nombres, $cliente->ap_paterno) ?>
</tr>
</table>
<hr>
<?php
    if (!(count($ser) && empty($serie)) && (isset($subaccion) && $subaccion!="registrar")) {
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="express">
Serie del producto:
<input type="text" name="serie" size="20" />
<input type="hidden" name="accion" value="renta" />
<input type="hidden" name="subaccion" value="prever" />
<input type="hidden" name="id_cliente" value="<?php echo $id_cliente ?>" />
<?php
    
//      for ($i=0; $i < count($ser); $i++) {
      $i=0; $num_ser = count($ser);
      while ($i < $num_ser && $num_ser) {
        printf("<input type=\"hidden\" name=\"ser[]\" value=\"%s\" />\n", $ser[$i]);
        $i++;
      }
      if (!$error && !empty($serie))
        printf("<input type=\"hidden\" name=\"ser[]\" value=\"%s\" />\n", $serie);
?>
</form>
<small>Deje la casilla en blanco y presione <i>&lt;Intro&gt;</i> para finalizar</small>
<br><br>
<?php
    }
    if (!empty($serie) && (isset($subaccion) && $subaccion!="registrar")) {

      $error = 0;
    
      $query = "SELECT s.codigo, s.almacen, ar.descripcion FROM articulos ar, ";
      $query.= "articulos_series s WHERE s.codigo=ar.codigo AND s.id='$serie' ";
    
      if (!@$db_res = db_query($query, $conn)) {
	$mens = "<div class=\"error_f\">Error al consultar detalles del producto.</div><br>";
	$mens.= db_errormsg($conn);
	die($mens);
      }
      if (!db_num_rows($db_res)) {
	printf("<div class=\"error_nf\">No existen datos del número de serie %s </div><br>\n",
	       $serie);
	$error++;
      }
      else {
	$ren = db_fetch_object($db_res, 0);
	$s_tabla = sprintf("<table style=\"border: ridge olive; font-size: small;\" cellpadding=3 width=400px>\n");
	$s_tabla.= sprintf("<tr>\n");
	$s_tabla.= sprintf("  <td colspan=3><b>Producto agregado a la lista</b></td>\n");
	$s_tabla.= sprintf("<tr>\n");
	$s_tabla.= sprintf("  <td>$serie</td>\n");
	$s_tabla.= sprintf("  <td>%s</td>\n", $ren->codigo);
	$s_tabla.= sprintf("  <td>%s</td>\n", $ren->descripcion);
	$s_tabla.= sprintf("</tr>\n");
	$s_tabla.= sprintf("</table>\n");
	$s_tabla.= sprintf("<br><br>\n");
      }

      $query = "SELECT * FROM rentas_detalle det WHERE det.status&B'10000000' != B'10000000' ";
      $query.= "AND det.serie='$serie' ";

      if (!@$db_res = db_query($query, $conn)) {
	$mens = "<div class=\"error_f\">Error al consultar detalle de rentas</div><br>";
	$mens.= db_errormsg($conn);
	die($mens);
      }
      if (db_num_rows($db_res)) {
	$ren = db_fetch_object($db_res, 0);
	printf("<div class=\"error_nf\">Error: El producto con serie %s ",
	       $ren->serie);
	printf("no ha sido devuelto de la operación %d.<br>\n", $ren->id);
	printf("F. de entrega %s</div>\n", $ren->f_entrega);
	$error++;
	echo "<br>\n";
      }
      else
	echo $s_tabla;

    }
    else if (count($ser) && (isset($subaccion) && $subaccion=="prever")) {
      $hoy = getdate();

      $query = "SELECT s.id, s.codigo, s.almacen, a.descripcion, rta.unidad_t, ";
      $query.= "rta.pu1 AS pu, rta.tiempo FROM articulos a, articulos_rentas rta, ";
      $query.= "articulos_series s WHERE s.codigo=a.codigo AND rta.codigo=s.codigo AND ";
      $query.= sprintf("(rta.dia=0 OR rta.dia=%d) AND (", date("w")+1);
      for ($i=0; $i < count($ser); $i++) {
	if ($i)
	  $query.= " OR ";
	$query.= sprintf("s.id='%s' ", $ser[$i]);
      }
      $query.= ") ";
	  
      if (!@$db_res = db_query($query, $conn)) {
	$mens = "<div class=\"error_f\">Error al consultar detalles de productos.</div><br>";
	$mens.= db_errormsg($conn);
	die($mens);
      }
      $num_ren = db_num_rows($db_res);
      include("bodies/renta_salida_prevista.bdy");

?>
<?php
    }
    else if(isset($subaccion) && $subaccion=="registrar") {
      include("bodies/renta_salida_confirmar.bdy");
    }
  }
}
?>
<br>
  </td>
</tr>
</table>
<hr>
<?php  include("bodies/menu/renta.bdy"); ?>
