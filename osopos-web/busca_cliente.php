<? /* -*- mode: c; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Busca cliente. Submódulo de facturación de OsoPOS Web.

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
?>

<!doctype html public "-//w3c//dtd html 4.0 transitional//en">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS - FacturWeb v. 0.2</title>
</head>


<body text="#000000" bgcolor="#FFFFFF" link="#0000EF" vlink="#51188E" alink="#FF0000" background="imagenes/fondo.gif">

<?
  include("include/general_config.inc");
  include("include/encabezado.inc");
  include("include/pos-var.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
    /*    $conn = pg_connect("dbname=$DB_NAME user=$DB_OWNER");
    if (!$conn) {
      echo "ERROR: Al conectarse a la base de datos $DB_NAME<br>\n</body></html>";
      exit();
      }*/
  }

?>

<form action="busca_cliente.php" method=post>
<table border=0 cellspacing=0 cellpadding=0 width="500">
<tbody>
 <tr>
  <td colspan=3><h4><font face="helvetica,arial">Buscar estos datos en registro:</font></h4>
 <tr>
  <td><font face="helvetica,arial" >Razón social:</font>
  <td><font face="helvetica,arial" ><input type=text name=razon_soc size=50></font>
  <td><font face="helvetica,arial" ><input type=submit value="Encontrar cliente"><input type=hidden name=fase value=1></font>
 <tr>
  <td><font face="helvetica,arial" >R.F.C.</font><input type=hidden name=id_venta value="<? echo $id_venta ?>">
  <td><font face="helvetica,arial" ><input type=text name=rfc size=13 maxlength=13></font>
  <td><font face="helvetica,arial" ><input type=hidden name=php_anterior value="<? echo $php_anterior ?>">&nbsp;</font>
 <tr>
  <td><font face="helvetica,arial" >C.U.R.P.</font>
  <td><font face="helvetica,arial" ><input type=text value="<? echo $curp ?>" name=curp <? echo "size=\"$MAXCURP\" maxlength=\"$MAXCURP\"" ?>></font>
  <td><font face="helvetica,arial" ><input type=hidden name=id value="<? echo $id ?>">&nbsp<input type=hidden name=fase value=2></font>
</tbody>
</table>
</form>
<br>
<hr>
<div align="right">
<font face="helvetica,arial" size="+1">
<a href="<? echo "$php_anterior?id_venta=$id_venta" ?>">Regresar</a>
</div>

<? /***********  fase dos **********/

if ($fase == 2) {
  $query = "";
  $datos = "?";

  if (strlen($razon_soc))
	$query = "nombre~*'$razon_soc'";
  if (strlen($rfc)) {
	if (strlen($query))
	  $query .= " AND ";
	$query .= "rfc~*'$rfc'";
  }
  if (strlen($curp)) {
	if (strlen($query))
	  $query .= " AND ";
	$query .= "curp~*'$curp'";
  }

  $query = "SELECT * FROM clientes_fiscales WHERE " . $query;
  $result =  pg_exec($conn, $query);
  if (!$result) {
	echo "Error al ejecutar $query<br>" . pg_errormessage($conn) . "<br>\n";
	exit();
  }

?>

Seleccione uno de los registros siguientes:<br>
<form action="<? echo $php_anterior ?>" method=post>
<table border=0 cellspaciing=0 cellpadding=0 width="100%">
 <th>&nbsp;
 <th>R.F.C.
 <th>Nombre
 <th>C.U.R.P.
 <tbody>

<?
  for ($i=0; $i<pg_numrows($result); $i++) {
	$renglon = pg_fetch_object($result, $renglon);
	echo "<tr>\n";
	echo " <td><input type=radio name=rfc value=";
	printf ("\"%s|%s|%s\"", $renglon->rfc, $renglon->nombre, $renglon->curp);
	if (!$i)
	  echo " checked";
	echo ">\n";
	echo " <td>" . $renglon->rfc . "\n";
	echo " <td>" . $renglon->nombre . "\n";
	echo " <td>" . $renglon->curp . "\n";
  }
  echo "<tr>\n"; 
  echo " <td colspan=4 align=center>\n";
  echo " <input type=hidden name=decodifica_rfc value=1>\n";
  echo " <input type=hidden name=id_venta value=\"$id_venta\">\n";
  echo " <input type=submit value=\"Continuar captura\">\n";
?>

</tbody>
</table>
</form>

<?
	}
?>

</body>
</html>

