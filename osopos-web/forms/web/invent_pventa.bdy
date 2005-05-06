<?php /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*- 
        invent_pventa.bdy. Cuerpo del m�dulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2003 Eduardo Israel Osorio Hern�ndez

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
  $query = "SELECT max(id) as max_alm FROM almacenes ";
  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }
  
  $head_width = 85;
  $alm_col_width = 100;
  $max_alm = db_result($resultado, 0, "max_alm");
  $t_width = $max_alm*$alm_col_width + $head_width;

  /*  $query = "SELECT  as max_alm FROM almacenes ";
  if (!$resultado = db_query($query, $conn)) {
    echo "Error al ejecutar $query<br>\n";
    exit();
  }*/
  
  $renglon = array(); /* renglones de la tabla en hipertexto */
  $renglon[0] = "<th>&nbsp;</th>";         /* Nombres de almacenes */
  $renglon[1] = "<td>Precio 1</td>";         /* Precio 1 */
  $renglon[2] = "<td>Precio 2</td>";         /* Precio 2 */
  $renglon[3] = "<td>Precio 3</td>";         /* Precio 3 */
  $renglon[4] = "<td>Precio 4</td>";         /* Precio 4 */
  $renglon[5] = "<td>Precio 5</td>";         /* Precio 5 */
  $renglon[6] = "<td>Existencia</td>";            /* Existencia */
  $renglon[7] = "<td>Ex. minima</td>";     /* Ex. m�xima */
  $renglon[8] = "<td>Ex. m�xima</td>";     /* M�nima */
  $r_colgroup = sprintf("<col width=\"%d\"><col width=\"%d\" span=\"%d\">", 
                        $head_width, $alm_col_width, $max_alm);

  $art = array(new articulosClass);
  for ($i=1; $i<=$max_alm; $i++) {
    $art[$i] = new articulosClass;
    $query1 = "SELECT nombre FROM almacenes WHERE id=$i";
    $query2 = "SELECT pu,pu2,pu3,pu4,pu5, cant, c_min, c_max FROM almacen_$i ";
    $query2.= sprintf("WHERE codigo='%s' ", $codigo);

    if ((!$db_res2 = db_query($query2, $conn)) || (!$db_res1 = db_query($query1, $conn))) {
      echo "Error al consultar informaci�n del producto<br>\n";
      exit();
    }

    if (db_num_rows($db_res1))
      $nm_alm = db_result($db_res1, 0, 0);
    else
      $nm_alm = FALSE;

    if (strlen($nm_alm))
      $renglon[0].= sprintf("<th>%s</th> ", $nm_alm);
    else
      $renglon[0].= sprintf("<th>no. %d, desconocido</th> ", $i);

    if (db_num_rows($db_res2))
      $item = db_fetch_row($db_res2, 0);
    else
      $item = FALSE;

    for ($j=1; $j < count($renglon); $j++) {
      if (is_array($item)) {
        //asignar tambien el siguiente elemento del array al objeto $art[$i]
        if ($j<=5)
          $renglon[$j].= sprintf("<td class=\"moneda\">%.2f</td>", $item[$j-1]);
        else
          $renglon[$j].= sprintf("<td class=\"serie\">%.2f</td>", $item[$j-1]);
      }
      else
        $renglon[$j].= "<td>N/D</td>";
    }
  }
}
?>
<?php
  $boton = "pventa"; /* Indicador de que bot�n debe aparecer presionado */
  include("bodies/web/invent_costos_head.bdy");
?>
<table border=0 width="100%">
<colgroup>
  <col width="50"><col width="200"><col width="200"><col>
</colgroup>
<tr>
  <td>Clave</td><td><?php echo $codigo ?></td>
  <td>Descripci�n</td><td><?php echo articulo_descripcion($conn, $codigo) ?></td>
</tr>
<tr>
  <td>Divisa</td><td><?php lista_divisas($conn, $DIVISA_OMISION) ?></td>
  <td>Departamento</td><td><?php echo nombre_depto($conn, $id_dept) ?></td>
</tr>
</table>


<table border="1" width="<?php echo $t_width ?>">
<?php
    printf("<colgroup>\n%s\n</colgroup>\n", $r_colgroup);
    for ($i=0; $i< count($renglon); $i++)
       printf("<tr>\n  %s\n</tr>\n", $renglon[$i]);
?>
</table>
