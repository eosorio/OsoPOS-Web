<?  /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 Caja Web 0.0.2-1. Módulo de caja de OsoPOS Web.

        Copyright (C) 2000 Eduardo Israel Osorio Hernández
        iosorio@punto-deventa.com

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
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - CajaWeb v. <? echo $caja_web_vers ?></title>
</head>


<? if (strlen($cod)!=0  || !isset($num_arts)) { ?>

<body bgcolor="white" background="imagenes/fondo.gif" onload="document.forma_articulo.cod.focus()">

<form action="<? echo $PHP_SELF ?>" method=POST name=forma_articulo>


<table border=0 width=500>
<tr>

<td valign=middle><font face="helvetica,arial">
C&oacute;digo, cantidad o descripci&oacute;n:</font>

<td><font size=-1 face="helvetica,arial">
<input type=text name=cod size=20 maxlength=20>
</font>
<input type=hidden name=php_anterior value="<? echo $PHP_SELF ?>">

<td align=right><font face="helvetica,arial">
<input type=submit value="Finalizar venta"></font>

<td align=right><font face="helvetica,arial">
<input type=button value="Cancelar artículo" onClick="javascript:history.back()">
</font>
</table>
<? }  /* fin de if(!isset$cod).... */ ?>

<?
  if (strlen($cod)) {
    if ($bandera == 0) {
      $peticion = "SELECT descripcion, pu, descuento,iva_porc FROM articulos ";
      $peticion.= "WHERE codigo='$cod'";
      if (!$resultado = pg_exec($conn, $peticion)) {
        echo "Error al ejecutar $peticion<br>\n";
        exit();
      }
      $num_arts++;
      if (pg_numrows($resultado)) {
        $reng = pg_fetch_object($resultado, 0);
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

?>

<!-- Lista de articulos -->
<table border=0>
<tr>
<th>CT.</th>
<th>C&oacute;digo</th>
<th width=300>Descripci&oacute;n</th>
<th>P.U.</th>
<th>Desc.</th>
<th>&nbsp;</th>
</tr>

<tr>
<td colspan=6><hr></td>
</tr>

<?

      for($i=$num_arts-1, $subtotal=0;  $i>=0;  $i--) {
        if ($articulo_codigo[$i] == $articulo_codigo[$i-1]) {
          $i--;
          $num_arts--;
          $articulo_cantidad[$i] += 1;
        }
        echo "<tr>\n";
        echo "<td align=center><font size=-1 face=\"helvetica,arial\">";
        echo $articulo_cantidad[$i] . "</font>\n";
        echo "<input type=hidden name=articulo_cantidad[$i] value=\"" . $articulo_cantidad[$i] . "\">\n";
        echo "<td><font size=-1 face=\"helvetica,arial\">\n";
        echo "<input type=hidden name=articulo_codigo[$i] value=\"" . $articulo_codigo[$i] . "\">\n";
        echo "$articulo_codigo[$i]</font></td>\n";
        echo "<td><font size=-1 face=\"helvetica,arial\">";
        echo $articulo_descripcion[$i] . "\n";
        echo "<input type=hidden name=articulo_descripcion[$i] value=\"";
        echo $articulo_descripcion[$i] . "\"></font>\n";
        printf("<td align=right><font size=-1 face=\"helvetica,arial\">\n%.2f\n", $articulo_pu[$i]);
        echo "\n";
        printf("<input type=hidden name=articulo_pu[%d] value=\"%.2f\"></font></td>\n",
               $i, $articulo_pu[$i]);
        printf("<td align=right><font size=-1 face=\"helvetica,arial\">\n%.2f\n", $articulo_disc[$i]);
        printf("<input type=hidden name=articulo_disc[%d] value=\"%.2f\"></font>\n",
               $i, $articulo_disc[$i]);
        echo "<td><font size=-1 face=\"helvetica,arial\">";
        if(!$articulo_iva_porc[$i])
          echo "E";
        else
          echo "&nbsp;";
        echo "</font>\n<input type=hidden name=articulo_iva_porc[$i]";
        echo " value=\"$articulo_iva_porc[$i]\">\n";
        echo "</tr>\n";

        $subtotal += $articulo_pu[$i] * $articulo_cantidad[$i];
        $iva += $articulo_pu[$i] / (1+($articulo_iva_porc[$i]/100));
      }

?>
    <tr>
       <td colspan=6><hr></td>
    </tr>

    <tr>
      <td colspan=3 align=right><font size=+1 face="helvetica,arial">Total acumulado:</font></td>
      <td><font size=+1 face="helvetica,arial"><b><?php printf("%.2f", $subtotal) ?></b></font></td>
    </tr>
  <input type=hidden name=num_arts value="<? printf("%d", $num_arts) ?>">
  <input type=hidden name=subtotal value="<? printf("%.2f", $subtotal) ?>">
</table>

</form>

<?
    } /* fin de if($bandera!=1) */

    if ($bandera == 1) {
      printf("<input type=hidden name=num_arts value=\"%d\">\n", $num_arts);
      echo "</form>\n";
      include("bodies/ingresa_articulo.bdy");
    }
  }
  else {
    if ($num_arts>0) {

      $nm_ticket = tempnam($TMP_DIR, "cajaweb");
      /* Colocar aqui código para leer el id de venta, colocar una venta vacía para apartar folio
         y despues se actualiza */

	  include("bodies/caja_web_cobro.bdy");
    }
    else {
      printf("<input type=hidden name=num_arts value=\"%d\">\n", $num_arts);
      echo "</form>\n";
      echo "<font face=\"helvetica,arial\">Sin art&iacute;culos a cobrar</font>\n";
    }
  }
?>


</body>
</html>
