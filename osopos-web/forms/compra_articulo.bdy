<?  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Invent Web. Módulo de inventarios de OsoPOS Web.

        Copyright (C) 2000-2002 Eduardo Israel Osorio Hernández

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
<form action="<? echo $PHP_SELF ?>" method="post">
Proveedor: <? lista_proveedores() ?>
<table>
<?
{
  /*igm*/ $numren_compras;
  for ($i=0; $i<$numren_compras; $i++) {
?>
<tr>
  <th>Código</th><th>Cant.</th><th>P.U.</th>
</tr>
<tr>
  <td><input type="text" name="codigo" size=20></td>
  <td><input type="text" name="cant" size=4></td>
  <td><input type="text" name="pu" size=8></td>
</tr>
   <? } ?>
<tr>
  <td colspan=3 align="right"><input type="button" value="Registrar"></td>
</tr>
</table>
</form>