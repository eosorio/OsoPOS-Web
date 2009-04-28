<h1>Módulo de Rentas</h1>
<table border="0" width="100%" height="500px">
<tr valign="top">
  <td>
<h2>Menú principal</h1>

<table border=0>
  <tr>
    <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?accion=renta">Renta de productos</a></td>
  </tr>
  <tr><td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?accion=clientes">Clientes con
  rentas pendientes</a></td></tr>
  <tr><td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?accion=productos">Productos que se encuentran rentados</a></td></tr>
  <tr><td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?accion=devolucion">Consulta de
  rentas por entregar</a></td></tr>
  <tr><td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?accion=devolucion_express">Devolución express</a></td></tr>
  <tr><td><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"><table>
 <tr>
  <td>Detalle de rentas</td>
  <td>
   <input type="hidden" name="accion" value="detalle_renta" />
   <input type="text" name="id" size="7" />
   </td>
  </tr>
 <tr>
   <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?accion=estadistica">Estadísticas de rentas</a></td>
 </tr>
 <tr>
   <td><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
     <table width="100%">
	 <tr>
	   <td><input type="hidden" name="accion" value="costos">
	   Costos y tiempos de entrega
	   </td>
	   <td><input type="text" size="20" name="codigo"></td>
	 </tr>
	 </table>
   </td>  
 </tr>
</table>
</form>
</td></tr>
</table>
</td>
</tr>
</table>

