<?php  /* -*- mode: php; indent-tabs-mode: nil; c-basic-offset: 2 -*-
        Almacen Web. Sub-M�dulo de inventarios de OsoPOS Web.

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

?>
<form action="<?php echo $PHP_SELF ?>" method="post">
<small>Mostrar almacen: <?php echo lista_almacen($conn, "almc", "Mostrar almacenes") ?>&nbsp;</small>
<input type="submit" value="Mostrar">
</form><br>
