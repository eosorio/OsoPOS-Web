<?php  /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ 
{

  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }

  if (!isset($articulo_codigo)) {
    $articulo_cantidad = array();
    $articulo_codigo = array();
    $articulo_iva_porc = array();

  }
}

?>

<form action="<?php echo $php_anterior ?>" method="get">
<table border=0 cellspacing=0 cellpadding=1 width="500">
<tbody>
<tr>
 <td colspan=4><font face="helvetica,arial" color=blue>
 <b>Art�culo no encontrado. Indica precio unitario y gravamen de
 I.V.A.</b><br><br></font>
<tr>
 <th><small>C�digo</small></th>
 <th><small>Descripci�n</small></th>
 <th><small>P.U.</small></th>
 <th><small>% IVA</small></th>
<tr>
 <td width="15%" align=center><font size=-1 face="helvetica,arial">
 <?php echo $cod ?></font>
 <input type=hidden name=articulo_codigo[<?php printf("%d", $num_arts-1) ?>]
  value="<?php echo $cod ?>">
 <input type=hidden name=articulo_cantidad[<?php printf("%d", $num_arts-1) ?>] value=1>
 <input type=hidden name=cod value="<?php echo $cod ?>">
 <td width="60%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_descripcion[<?php printf("%d", $num_arts-1) ?>]
  size="<?php echo $MAXDES ?>" maxlength="<?php echo $MAXDES ?>"></font>
 <td width="15%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_pu[<?php printf("%d", $num_arts-1) ?>]
  size=10></font>
 <td width="10%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_iva_porc[<?php printf("%d", $num_arts-1) ?>]
  size=2></font>
<tr>
 <td colspan=4 align="right"><font face="helvetica,arial">
 <?php
  printf(" <input type=hidden name=num_arts value=%d>\n", $num_arts); 
  for ($i=$num_arts-2; $i>=0; $i--) {
	printf("  <input type=hidden name=articulo_cantidad[%d] value=%d>\n",
		   $i, $articulo_cantidad[$i]);
	printf("  <input type=hidden name=articulo_iva_porc[%d] value=%.2f>\n",
		   $i, $articulo_iva_porc[$i]);
	printf("  <input type=hidden name=articulo_pu[%d] value=%.2f>\n",
		   $i, $articulo_pu[$i]);
	printf("  <input type=hidden name=articulo_disc[%d] value=%.2f>\n",
		   $i, $articulo_disc[$i]);
	printf("  <input type=hidden name=articulo_descripcion[%d] value=\"%s\">\n",
		   $i, $articulo_descripcion[$i]);
	printf("  <input type=hidden name=articulo_codigo[%d] value=\"%s\">\n",
		   $i, $articulo_codigo[$i]);
  }
?>
 <input type="hidden" name="bandera" value="2">
 <input type=submit value="Ingresar"></font>
</tbody>
</table>
</form>
<br>
