<?  /* -*- mode: html; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
 Ingresa art�culo. Sub-M�dulo de caja de OsoPOS Web.

        Copyright (C) 2000 Eduardo Israel Osorio Hern�ndez

        Este programa es un software libre; puede usted redistribuirlo y/o
modificarlo de acuerdo con los t�rminos de la Licencia P�blica General GNU
publicada por la Free Software Foundation: ya sea en la versi�n 2 de la
Licencia, o (a su elecci�n) en una versi�n posterior. 

        Este programa es distribuido con la esperanza de que sea �til, pero
SIN GARANTIA ALGUNA; incluso sin la garant�a impl�cita de COMERCIABILIDAD o
DE ADECUACION A UN PROPOSITO PARTICULAR. V�ase la Licencia P�blica General
GNU para mayores detalles. 

        Deber�a usted haber recibido una copia de la Licencia P�blica General
GNU junto con este programa; de no ser as�, escriba a Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA02139, USA. 

*/
{

  include("include/general_config.inc");
  include("include/pos-var.inc");
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

  if (isset($cod)) {
	if (!isset($desc) && !isset($pu) && !isset($iva_porc)) {
      $peticion = "SELECT descripcion, pu, descuento,iva_porc FROM articulos ";
      $peticion.= "WHERE codigo='$cod'";
      if (!$resultado = pg_exec($conn, $peticion)) {
        echo "Error al ejecutar $peticion<br>\n";
        exit();
      }
      if (pg_numrows($resultado)) {
        $reng = pg_fetch_object($resultado, 0);
      }
	  $s_header = sprintf ("Location: %s?i=%d&cod=%s&desc=%s&pu=%.2f&iva_porc=%.2f&disc=%.2f", 
						  $php_anterior,  $i+1, $cod, str_replace(" ", "%20", $reng->descripcion),
						   $reng->pu, $reng->iva_porc, $reng->disc);
	  header($s_header);
	}
	else {
?>
<html>

<body bgcolor="white" background="imagenes/fondo.gif">

<form action="<? echo $php_anterior ?>" method=post>
<table border=0 cellspacing=0 cellpadding=1 width="500">
<tbody>
<tr>
 <td colspan=4><font face="helvetica,arial" color=blue>
 <b>Art�culo no encontrado. Indica precio unitario y gravamen de
 I.V.A.</b><br><br></font>
<tr>
 <th><font size=-1 face="helvetica,arial">C�digo</font>
 <th><font size=-1 face="helvetica,arial">Descripci�n</font>
 <th><font size=-1 face="helvetica,arial">P.U.</font>
 <th><font size=-1 face="helvetica,arial">% IVA</font>
<tr>
 <td width="15%" align=center><font size=-1 face="helvetica,arial">
 <? echo $cod ?></font>
 <input type=hidden name=articulo_codigo[<? printf("%d", $i-1) ?>]
  value="<? echo $cod ?>">
 <td width="60%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_descripcion[<? printf("%d", $i-1) ?>]
  size="<? echo $MAXDES ?>" maxlength="<? echo $MAXDES ?>"></font>
 <td width="15%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=articulo_pu[<? printf("%d", $i-1) ?>]
  size=10></font>
 <td width="10%" align=center><font size=-1 face="helvetica,arial">
 <input type=text name=art_iva_porc[<? printf("%d", $i-1) ?>]
  size=2></font>
<tr>
 <td colspan=4 align="right"><font face="helvetica,arial">
 <? printf("<input type=hidden name=i value=%d>\n", $i-1); ?>
 <input type=submit value="Ingresar"></font>
</tbody>
</table>
<br>
<hr>
</body>
</html>

<?
	}
  }
}

?>
