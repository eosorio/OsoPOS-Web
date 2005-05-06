<table border="0" width="700px" cellspacing=0 cellpadding=0>
<colgroup>
  <col width="101px" span=5><col width=*>
</colgroup>
<tr>
  <td>
    <form action="<?php echo $PHP_SELF ?>" method="post">
    <input type="hidden" name="codigo" value="<?php echo $codigo ?>">
    <input type="hidden" name="id_depto" value="<?php printf("%d", $id_depto) ?>">
    <input type="hidden" name="action" value="ver">
    <input type="hidden" name="boton" value="general">
<?php
/*   if (!isset($boton) || $boton=="general") */
    echo "    <input type=\"image\" src=\"imagenes/web/pestanias/general.png\">\n";
/*  else 
    echo "    <input type=\"image\" src=\"imagenes/web/botones/btn_general_inactivo.png\">\n"; */
?>
    </form>
  </td>
  <td>
    <form action="<?php echo $PHP_SELF ?>" method="post">
    <input type="hidden" name="codigo" value="<?php echo $codigo ?>">
    <input type="hidden" name="action" value="ver">
    <input type="hidden" name="boton" value="costos">
<?php

/*   if ($boton=="costos") */
    echo "    <input type=\"image\" src=\"imagenes/web/pestanias/costos.png\">\n";
/*   else */
/*     echo "    <input type=\"image\" src=\"imagenes/web/botones/btn_costos_inactivo.png\">\n"; */
?>
    </form>
  </td>
  <td>
    <form action="<?php echo $PHP_SELF ?>" method="post">
    <input type="hidden" name="codigo" value="<?php echo $codigo ?>">
    <input type="hidden" name="id_depto" value="<?php printf("%d", $id_depto) ?>">
    <input type="hidden" name="action" value="ver">
    <input type="hidden" name="boton" value="pventa">
<?php

/*   if ($boton=="pventa") */
    echo "    <input type=\"image\" src=\"imagenes/web/pestanias/p_venta.png\">\n";
/*   else */
/*     echo "    <input type=\"image\" src=\"imagenes/web/botones/btn_pventa_inactivo.png\">\n"; */
?>
    </form>
  </td>
  <td>
    <form action="<?php echo $PHP_SELF ?>" method="post">
    <input type="hidden" name="codigo" value="<?php echo $codigo ?>">
    <input type="hidden" name="action" value="ver">
    <input type="hidden" name="boton" value="prenta">
<?php

/*   if ($boton=="prenta") */
    echo "    <input type=\"image\" src=\"imagenes/web/pestanias/p_renta.png\">\n";
/*   else */
/*     echo "    <input type=\"image\" src=\"imagenes/web/botones/btn_prenta_inactivo.png\">\n"; */
?>
        </form>
  </td>
  <td>
    <form action="<?php echo $PHP_SELF ?>" method="post">
    <input type="hidden" name="codigo" value="<?php echo $codigo ?>">
    <input type="hidden" name="action" value="ver">
    <input type="hidden" name="boton" value="series">
<?php

/*   if ($boton=="series") */
    echo "    <input type=\"image\" src=\"imagenes/web/pestanias/series.png\">\n";
/*   else */
/*     echo "    <input type=\"image\" src=\"imagenes/web/botones/btn_series_inactivo.png\">\n"; */
?>
    </form>
  </td>
</tr>
<tr height="3px" style="background-color: #eb1717">
  <td colspan=6 height="3px"></td>
</tr>
</table>
