<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-

 Cliente Web 0.4-1. Módulo de clientes de OsoPOS Web.

        Copyright (C) 2000,2003 Eduardo Israel Osorio Hernández

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
  /*igm*/ $debug = 0;
{
  include("include/general_config.inc");
  include("include/pos-var.inc");

  if (isset($salir)) {
    include("include/logout.inc");
  }
  else {
    include("include/passwd.inc");
  }


  function td_status($status) {
    $s = "<table>\n";
    $s.= "<colgroup>\n  <col span=8 width=14px>\n</colgroup>\n";
    $s.= "<tr>\n";
    for ($i=0; $i<strlen($status); $i++) {
      $s.= "  <td style=\"border-style: solid; border-color: teal; border-width: thin\">";
      if ($status[$i] == '1')
        $s.="X";
      else
        $s.= "&nbsp;";
      $s.= "</td>\n";
    }
    $s.= "</tr>\n";
    $s.= "</table>\n";
    return($s);
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="E. Israel Osorio Hernández">
   <title>OsoPOS Web - Clientes v. <? echo $factur_web_vers ?></title>
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/cuerpo.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/numerico.css">
   <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/extras.css">
   <style type="text/css">
    td.campo {font-face: helvetica,arial}
    td.nm_campo {text-align: right}
    td.right {font-face: helvetica, arial; text-align: right}
    td.right_red {text-align: right; font-face: helvetica, arial; color: red;}
    
   </style>

</head>

<body>
<?php
  $ahora = date("Y-m-d H:m:s");
  if (empty($accion) || $accion=="alta") {
    //    include("forms/alta_cliente.bdy");
    include("forms/cliente_alta.bdy");
  }

  else if($accion == "agregar") {
    $query = "INSERT INTO clientes (nombres, ap_paterno, ap_materno, tipo_cliente, ";
    $query.= "sexo, nombre_comer, dom_principal, email, url, telefono1, telefono2, ";
    $query.= "fax, contacto, observaciones, rfc) VALUES ( ";
    $query.= sprintf("'%s', '%s', '%s', %d, ",
                      $nombres, $ap_paterno, $ap_materno, $tipo_cliente);
    $query.= sprintf("'%s', '%s', %d, '%s', '%s', '%s', '%s', ",
		     $sexo, $nombre_comer, $dom_principal, $email, $url,
		     $telefono1, $telefono2);
    $query.= sprintf("'%s', '%s', '%s', '%s') ", $fax, $contacto, $observaciones, $rfc);

    if (!$resultado = db_query($query, $conn)) {
      $mens = "<div class=\"error_f\">Error al insertar datos de cliente</div>\n";
      die($mens);
    }
    
    $query = "SELECT id FROM clientes WHERE nombres='$nombres' AND ap_paterno='$ap_paterno' ";
    $query.= "AND ap_materno='$ap_materno' AND nombre_comer='$nombre_comer' ";

    if (!$resultado = db_query($query, $conn)) {
      $mens = "<div class=\"error_nf\">Error al consultar datos de cliente</div>\n";
    }
    else {
      $id_cliente = db_result($resultado, 0, "id");

      $query = "INSERT INTO domicilios (id_cliente, dom_nombre, dom_calle, dom_numero, dom_inter, ";
      $query.= "dom_col, dom_mpo, dom_ciudad, dom_edo_id, dom_cp, ";
      $query.= "dom_pais_id, dom_telefono) VALUES (";
      $query.= sprintf("%d, '%s', '%s', '%s', '%s', ",
                       $id_cliente, $dom_nombre, $dom_calle, $dom_numero, $dom_inter);
      $query.= sprintf("'%s', '%s', '%s', %d, %d, ", 
                       $dom_col, $dom_mpo, $dom_ciudad, $dom_edo_id, $dom_cp);
      $query.= sprintf("%d, '%s')", $dom_pais_id, $dom_telefono);

      if (!$resultado = db_query($query, $conn)) {
        $mens = "<div class=\"error_nf\">Error al insertar domicilio del cliente $id_cliente</div>\n";
      }
      else {
        $query = "SELECT id FROM domicilios WHERE id_cliente=$id_cliente and dom_nombre='$dom_nombre' ";

        if (!$resultado = db_query($query, $conn)) {
          $mens = "<div class=\"error_nf\">Error al consultar domicilio del cliente $id_cliente</div>\n";
        }
        else {
          $id_domicilios = db_result($resultado, 0, "id");

          $query = "UPDATE clientes SET dom_principal=$id_domicilios WHERE id=$id_cliente ";


          if (!$resultado = db_query($query, $conn)) {
            $mens = "<div class=\"error_nf\">Error al resgitrar domicilio principal del cliente</div>\n";
          }
 
        } /* fin de consulta de id domicilio principal */
      }  /* fin de inserción de domicilio de cliente */
    }
  }

  /*  else if($accion=="registrar") {
    if ($n_anio < 20)
      $n_anio+= 1900;
    $query = "INSERT INTO cliente (razon_soc, ap_paterno, ap_materno, nombres, ";
    $query_v = sprintf("'%s', '%s', '%s', '%s', ", $razon_soc, $ap_paterno, $ap_materno, $nombres);

    $query.= "dom_calle, dom_numero, dom_inter, dom_col, dom_ciudad, dom_edo, ";
    $query_v.= sprintf("'%s', '%s', '%s', '%s', '%s', '%s', ",
                       $dom_calle, $dom_numero, $dom_inter, $dom_col, $dom_ciudad, $dom_edo);

    $query.= "dom_cp, dom_tel_casa, dom_tel_trabajo, ";
    $query_v.= sprintf("%d, '%s', '%s', ", $dom_cp, $dom_tel_casa, $dom_tel_trabajo);

    $query.= "referencia1, referencia2, relacion_ref1, relacion_ref2, ";
    $query_v.= sprintf("'%s', '%s', '%s', '%s', ", $referencia1, $referencia2, $relacion_ref1, $relacion_ref2);

    $query.= "dom_tel_ref1, dom_tel_ref2, ";
    $query_v.= sprintf("'%s', '%s', ", $dom_tel_ref1, $dom_tel_ref1);

    $query.= "f_nam, sexo, ocupacion, edo_civil, ";
    $query_v.= sprintf("'%s-%s-%s', '%s', '%s', '%s', ", $n_anio, $n_mes, $n_dia, $sexo, $ocupacion, $edo_civil);

    $query.= " alta, baja, status) ";
    $query_v.= sprintf("'%s', NULL, B'00000000' ", $ahora);

    $query.= "VALUES ($query_v) ";

    if (!@$db_res = db_query($query, $conn)) {
      $mens = "<font style=\"text-size: big; font-weight: bold\">Error al registrar cliente</font><br>" . db_errormsg($conn);
      die($mens);
    } 
    echo "<i>Cliente $nombres $ap_paterno $ap_materno, registrado</i><br>\n";
  } */
  else if($accion=="consulta") {
    $query = "SELECT id, status, nombres, ap_paterno, ap_materno, telefono1, telefono2, ";
    $query.= "sexo FROM clientes ";

    if (!$db_res = db_query($query, $conn)) {
      $mens = "<font style=\"text-size: big; font-weight: bold\">Error al consultar clientes</font><br>" . db_errormsg($conn);
      die($mens);
    } 
    include("bodies/cliente_consulta.bdy");
  }
  include("bodies/menu/cliente.bdy");
  include("bodies/menu/general.bdy");
}
?>
</body>
</html>