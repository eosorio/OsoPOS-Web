<?  /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- */ 
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

<form action=<? echo $php_anterior ?> method=get>
<table border=0 cellspacing=0 cellpadding=1 width="500">
<tbody>
<tr>
 <td colspan=4><font face="helvetica,arial" color=blue>
 <b>Artículo no encontrado. Indica precio unitario y gravamen de
 I.V.A.</b><br><br></font>
<tr>
 <th><font size=-1 face="helvetica,arial">Código</font>
 <th><font size=-1 face="helvetica,arial">Descripción</font>
 <th><font size=-1 face="helvetica,arial">P.U.</font>
 <th><font size=-1 face="helvetica,arial">% IVA</font>
<tr>
 <td width="15%" align=center><font size=-1 face="helvetica,arial">
 <? echo $cod ?></font>
 <input type=hidden name=articulo_codigo[<? printf("%d", $num_arts-1) ?>]
  value="<? echo $cod ?>">
 <input type=hidden name=articulo_cantidad[<? printf("%d", $num_arts-1) ?>] value=1>
 <input type=hidden name=cod value="<? echo $cod ?>">
 <td width="60%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_descripcion[<? printf("%d", $num_arts-1) ?>]
  size="<? echo $MAXDES ?>" maxlength="<? echo $MAXDES ?>"></font>
 <td width="15%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_pu[<? printf("%d", $num_arts-1) ?>]
  size=10></font>
 <td width="10%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_iva_porc[<? printf("%d", $num_arts-1) ?>]
  size=2></font>
<tr>
 <td colspan=4 align="right"><font face="helvetica,arial">
 <?
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
 <input type=hidden name=bandera value=2>
 <input type=submit value="Ingresar"></font>
</tbody>
</table>
</form>
<br>
