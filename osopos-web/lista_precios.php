<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Beta test. M�dulo de pruebas de OsoPOS Web.

        Copyright (C) 2004 Eduardo Israel Osorio Hern�ndez

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
  include("include/pos.inc");
  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }

?>
<html>
<head>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
</head>

<body>
<?php
   if (empty($alm))
     $alm = 1;
?>
<form action="<?php echo $PHP_SELF ?>" method="post">
Almac�n: <?php echo lista_almacen($conn, "alm", "--Seleccione un almacen--", $alm) ?>
<input type="submit" value="Mostrar"><br>
</form>
<br>
<form action="fpdf/lista_precios.php" method="post">
<input type="hidden" name="alm" value="<?php echo $alm ?>">
<input type="image" src="imagenes/impresora.jpg">
</form>
<?php

  $query = "SELECT al.codigo, ar.descripcion, al.pu, al.cant FROM almacen_1 al, ";
  $query.= "articulos ar WHERE al.codigo=ar.codigo AND al.id_alm=$alm ORDER BY ";
  $query.= "ar.id_depto, ar.descripcion ASC";
  
  if (!$db_res = db_query($query, $conn)) {
    $mens = "<div class=\"error_f\">Error al consultar articulos</div>\n";
    die($mens);
  }

  $num_ren = db_num_rows($db_res);

  echo "<table border=0 width=100%>\n";
  echo "<tr>\n";
  echo "  <th>C�digo</th><th>Descripci�n</th><th>P.U.</th><th>Ct.</th>\n";
  echo "</th>\n";

  for ($i=0; $i < $num_ren; $i++) {
    $ren = db_fetch_object($db_res, $i);
    echo "<tr>\n";
    printf("  <td>%s</td>\n", $ren->codigo);
    printf("  <td>%s</td>\n", $ren->descripcion);
    printf("  <td class=\"moneda\">%.2f</td>\n", $ren->pu);
    printf("  <td class=\"moneda\">%.2f</td>\n", $ren->cant);
    echo "</tr>\n";
  }
}
?>

</table>
</body>
</html>

