<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Invent Web. M�dulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2002 Eduardo Israel Osorio Hern�ndez

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
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
Proveedor: <?php lista_proveedores() ?>
<table>
<?php
{
  /*igm*/ $numren_compras;
  for ($i=0; $i<$numren_compras; $i++) {
?>
<tr>
  <th>C�digo</th><th>Cant.</th><th>P.U.</th>
</tr>
<tr>
  <td><input type="text" name="codigo" size=20></td>
  <td><input type="text" name="cant" size=4></td>
  <td><input type="text" name="pu" size=8></td>
</tr>
   <?php } ?>
<tr>
  <td colspan=3 align="right"><input type="button" value="Registrar"></td>
</tr>
</table>
</form>