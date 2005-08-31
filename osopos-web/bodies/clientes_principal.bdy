<table border="0" width="100%" cellpadding=3>
<?php
$colw_icono = 18;
$colw_id = 100;
$colw_nombre_comer = 300;
$colw_telefono1 = 100;
$colw_email = 250;
$colw_contacto = 300;
$colw_tipo_cliente = 50;

/* Columna de icono */
$colg = "  <col width=$colw_icono>";
$th = "<th>&nbsp;</th>";

/* Columna de I.D. */
$colg.= "<col width=$colw_id>";
$th.= sprintf("  <th><a href=\"%s?order_by=id&order=%s\">I.D.</a></th>\n",
			  $_SERVER['PHP_SELF'], $order_by=="id" && !$order);

/* Columna e nombre comercial */
$colg.= "<col width=$colw_nombre_comer>";
$th.= sprintf("  <th><a href=\"%s?order_by=nombre_comer&order=%s\">Nombre comercial</a></th>\n",
	   $_SERVER['PHP_SELF'], $order_by=="nombre_comer" && !$order);

/* Teléfono */
$colg.= "<col width=$colw_telefono1>";
$th.= sprintf("  <th><a href=\"%s?order_by=telefono1&order=%s\">Teléfono</a></th>\n",
	   $_SERVER['PHP_SELF'], $order_by=="telefono1" && !$order);
/* e-mail */
$colg.= "<col width=$colw_email>";
$th.= sprintf("  <th><a href=\"%s?order_by=email&order=%s\">e-mail</a></th>\n",
	   $_SERVER['PHP_SELF'], $order_by=="email" && !$order);

/* Contacto */
$colg.= "<col width=$colw_contacto>";
$th.= sprintf("  <th><a href=\"%s?order_by=contacto&order=%s\">Contacto</a></th>\n",
	   $_SERVER['PHP_SELF'], $order_by=="contacto" && !$order);

/* Tipo de cliente */
$colg.= "<col width=$colw_tipo_cliente>";
$th.= sprintf("  <th><a href=\"%s?order_by=tipo_cliente&order=%s\">Tipo cliente</a></th>\n",
	   $_SERVER['PHP_SELF'], $order_by=="cliente" && !$order);

echo "<colgroup>\n  $colg\n  </colgroup>\n";
echo "<tr>\n  $th  </tr>\n";


for ($i=0; $i < $limit && $i < $num_r; $i++) {
  $r = db_fetch_object($db_res, $i);
  echo"<tr>\n";
  echo "   <td>\n";
  printf("   <form action=\"%s\" method=\"post\">\n", $_SERVER['PHP_SELF']);
  echo "     <input type=\"hidden\" name=\"action\" value=\"ver\">\n";
  echo "     <input type=\"hidden\" name=\"offset\" value=\"$offset\">\n";
  echo "     <input type=\"hidden\" name=\"order\" value=\"$order\">\n";
  echo "     <input type=\"hidden\" name=\"order_by\" value=\"$order_by\">\n";
  printf("     <input type=\"hidden\" name=\"id\" value=\"%s\">\n", $r->id);
  echo "     <input type=\"image\" src=\"imagenes/lupa.png\" alt=\"Detalles\">\n";
  echo "   </form>\n";
  echo "   </td>\n";

  printf("  <td class=\"serie\">%d</td>\n", $r->id);
  printf("  <td>%s</td>\n", $r->nombre_comer);
  printf("  <td>%s</td>\n", $r->telefono1);
  printf("  <td>%s</td>\n", $r->email);
  printf("  <td>%s</td>\n", $r->contacto);
  printf("  <td class=\"serie\">%d</td>\n", $r->tipo_cliente);
  echo "</tr>\n";
}
?>
</table>
