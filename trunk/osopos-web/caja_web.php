<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 Caja Web 0.0.4-1. Módulo de caja de OsoPOS Web.

        Copyright (C) 2000,2001 Eduardo Israel Osorio Hernández
        iosorio@elpuntodeventa.com

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los términos de la Licencia Pública General GNU
publicada por la Free Software Foundation: ya sea en la versión 2 de la
Licencia, o (a su elección) en una versión posterior. 

        Este programa es distribuido con la esperanza de que sea útil, pero
SIN GARANTIA ALGUNA; incluso sin la garantía implícita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. Véase la Licencia Pública General
GNU para mayores detalles. 

        Debería usted haber recibido una copia de la Licencia Pública General
GNU junto con este programa; de no ser así, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 
*/


  include("include/general_config.inc");
  include("include/pos-var.inc");
  include("include/caja_web.inc");

{
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
  include("include/passwd.inc");
  }

  if (!isset($art)) {
	$art = array(new articulosClass);
  }
  /* Hay que quitar estas cochinadas en un futuro */
  if (!isset($articulo_codigo)) {
    $articulo_cantidad = array();
    $articulo_codigo = array();
    $articulo_iva_porc = array();
  }

  if (!isset($bandera))
    $bandera = 0;
  /* Opciones de la bandera: 0 Normal, 
	                         1 No esta en base de datos,
							 2 Se agrega descripción a mano
                             3 Fin de ingreso de artículos */

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - CajaWeb v. <? echo $caja_web_vers ?></title>
   <style type="text/css">
    body { background: white; font-family: helvetica }
    td.bg1 { background: <? echo $bg_color1 ?> }
    td.bg1_center {text-align: center; background: <? echo $bg_color1 ?> }
    td.bg1_right {text-align: right; background: <? echo $bg_color1 ?>}
    td.bg2 { background: <? echo $bg_color2 ?> }
    td.bg2_center {text-align: center; background: <? echo $bg_color2 ?> }
    td.bg2_right {text-align: right; background: <? echo $bg_color2 ?> }
    td.bg0 { }
    td.bg0_center {text-align: center }
    td.bg0_right {text-align: right }
    td.right_red {text-align: right; font-color: red}
    div.notify {font-style: italic; color: red}
   </style>

</head>

<?

  if ($mode == "express" && empty($codigo) && empty($num_arts)) {
    if ($bandera==0) {
?>
<script type="text/javascript">
 jcod = new Array();
 jpu  = new Array();
 jdesc = new Array();
 jdisc = new Array();
 jiva_porc = new Array();
<?
    /* Cargamos la tabla de articulos en variables de javascript */
    $query = "SELECT codigo, descripcion, pu, iva_porc, descuento FROM articulos ORDER BY codigo";
    if (!$resultado = db_query($query, $conn)) {
      echo "Error al ejecutar $peticion<br>\n";
      exit();
    }


    $num_ren = db_num_rows($resultado);
    for ($i=0; $i<$num_ren; $i++) {
      $renglon = db_fetch_object($resultado, $i);
      printf("jcod[%d] = '%s'; jpu[%d] = %.2f; jdesc[%d] = '%s'; jiva_porc[%d] = %.2f; jdisc[%d] = %.2f;\n",
			 $i, $renglon->codigo, $i, $renglon->pu, $i, str_replace("'", "\'", $renglon->descripcion),
			 $i, $renglon->iva_porc, $i, $renglon->descuento);
    }
  }

?>

  function shell_search(code, first, last) {
    middle = (last - first) div 2 + first;
    if (code < jcod[middle])
      return(shell_search(code, first, middle));
    else if (code > jcod[middle])
      return(shell_search(code, middle, last));
    else
      return(middle);
  }

  function find_code(codigo) {
    for (var i=0; i<<? echo $num_ren ?> && codigo!=jcod[i]; i++);
    return(jdesc[i]);
  }

  function muestra_articulo(code) {
    var s, t;
	var j;

    if (document.forma_articulo.cod.value=="") {
      return(true);
    }
    //    for (var i=0; i<<? echo $num_ren ?> && code!=jcod[i]; i++);
    i = shell_search(code, 0, <? echo $num_ren ?>);
    if (i == <? printf("%d", $num_ren) ?>) {
      alert("Artículo " + code + " no encontrado, introdúzcalo manualmente");
      return(false);
    }
    s = jcod[i];
	if (s.length > <? echo $MAXLEN_COD ?>)
	  s.length =  <? echo $MAXLEN_COD ?>;
	for (var j=0; j<<? echo $MAXLEN_COD ?>-jcod[i].length-1; s = s + " ", j++);
	s = s + "|" + jdesc[i];
	if (s.length > <? printf("%d", $MAXLEN_DESC + $MAXLEN_COD) ?>)
	  s.length =  <? printf("%d", $MAXLEN_DESC + $MAXLEN_COD)  ?>;
	for (j=0; j<<? echo $MAXLEN_DESC ?>-jdesc[i].length; s = s + " ", j++);
	s = s + "|";
	t = "" + jpu[i]; /* Aqui se pretende que se interprete como un string */
	for (j=0; j<15-t.length; s = s + " ", j++);
    s = s + jpu[i];
	s = s + "|" + jiva_porc[i];
	s = s + "|" + jdisc[i] + "\n";
    document.forma_articulo.lista_arts.value = document.forma_articulo.lista_arts.value + s;
    document.forma_articulo.cod.value = "";
    document.forma_articulo.cod.focus();
    return(false);
  }
</script>
<?
  }
?>

<? if (strlen($cod)!=0  || !isset($num_arts)) { ?>

<body bgcolor="white" background="imagenes/fondo.gif"
 onload="document.forma_articulo.cod.focus()">

<?
  if ($imprime_cabecera == 1) {
    include("include/minegocio.inc");
    switch(print_ticket_header()) {
      case 0:
        echo "<i>Ticket impreso</i><br>\n";
        break;
      case 1:
        echo "<b>Error: No puedo leer la cabecera de ticket</b><br>\n";
        break;
      case 2:
        echo "<b>Error: No puedo imprimir la cabecera de ticket</b><br>\n";
        break;
    }
  }    
?>

<form action="<? echo $PHP_SELF ?>" method="POST" name="forma_articulo"<?
   if ($mode == "express")
     echo " onsubmit=\"muestra_articulo(document.forma_articulo.cod.value)\">\n";
   else
     echo ">\n";
?>

<table border=0 width=500>
<tr>

<td valign="middle">
C&oacute;digo, cantidad o descripci&oacute;n:
</td>

<td><small>
<input type="text" name="cod" size=20 maxlength=20
onblur="muestra_articulo(forma_articulo.cod.value)">
</small>
<input type="hidden" name="php_anterior" value="<? echo $PHP_SELF ?>">
</td>

<td align="right">
<input type="button" name="ingresa" value="Ingresa articulo"
<?
    if ($mode=="express") {
?>
onClick="muestra_articulo(forma_articulo.cod.value)"
<?
    }
?>
>
</td>

<td align=right>
<input type=button value="Cancelar artículo" onClick="javascript:history.back()">
</td>

<td align="right">
<input type="submit" value="Finalizar venta">
</td>

<?
    if ($mode=="express") {
?>
<td align="right">
<input type="text" size=0>
</td>
<?
    }
?>

</tr>
</table>

<? }  /* fin de if(!isset$cod).... */ ?>

<?

  if (strlen($cod)) {
    if ($bandera == 0) {
      $peticion = "SELECT descripcion, pu, descuento,iva_porc FROM articulos ";
      $peticion.= "WHERE codigo='$cod'";
      if (!$resultado = db_query($peticion, $conn)) {
        echo "Error al ejecutar $peticion<br>\n";
        exit();
      }
      $num_arts++;
      if (db_num_rows($resultado)) {
        $reng = db_fetch_object($resultado, 0);
		$art[$num_arts-1] = new articulosClass;
		$art[$num_arts-1]->codigo = $cod;
        /* Hacer esto una clase */
        $articulo_codigo[$num_arts-1] = $cod;
        $articulo_descripcion[$num_arts-1] = $reng->descripcion;
        $articulo_pu[$num_arts-1] = $reng->pu;
        $articulo_disc[$num_arts-1] = $reng->disc;
        $articulo_iva_porc[$num_arts-1] = $reng->iva_porc;
        $articulo_cantidad[$num_arts-1] = 1;
      }
      else
        $bandera = 1;
    }  /* fin de if ($bandera == 0) */
    if ($bandera != 1) {
      include("bodies/caja_lista_arts.bdy");
      echo "</form>\n";     
    }
    if ($bandera == 1) {
      if ($mode == "express") {
		echo "<font face=\"Courier\">\n";
        printf("<textarea name=\"lista_arts\" cols=%d rows=20>\n", $lista_arts_cols);
        include("forms/cajaexp_lista_arts.bdy");
        echo "</textarea>\n";
		echo "</font>\n";
		echo "<input type=\"hidden\" name=\"mode\" value=\"express\">\n";
		echo "<input type=\"hidden\" name=\"num_arts\" value=1>\n";
      }
	  else
		printf("<input type=\"hidden\" name=\"num_arts\" value=\"%d\">\n", $num_arts);
      echo "</form>\n";
      include("bodies/ingresa_articulo.bdy");

    }
  } // fin de if ($strlen(cod))
  else {
    if ($num_arts>0) {

      $nm_ticket = tempnam($TMP_DIR, "cajaweb");
	  include("bodies/caja_web_cobro.bdy");
    }
    else {
      if ($mode == "express") {
		echo "<font face=\"Courier\">\n";
        echo "<textarea name=\"lista_arts\" cols=100 rows=20>\n";
        echo "</textarea>\n";
		echo "</font>\n";
		echo "<input type=\"hidden\" name=\"mode\" value=\"express\">\n";
		echo "<input type=\"hidden\" name=\"num_arts\" value=1>\n";
      }
      else
		printf("<input type=hidden name=num_arts value=\"%d\">\n", $num_arts);
      echo "</form>\n";
      if ($mode != "express")
        echo "Sin art&iacute;culos a cobrar\n";
    }
  }
?>


</body>
</html>
