<table border="1">
<colgroup>
<col width=10><col width=40><col width=150><col width=130>
<col width=200><col width=300><col width=100>

</colgroup>
  <tbody>
    <tr>
      <th >&nbsp;</th>
      <th >Folio</th>
      <th >Fecha</th>
      <th >R.F.C.</th>
      <th >Calle y número</th>
      <th >Ciudad, Estado y C.P.</th>
      <th >Total</th>
    </tr>
	  <?
for ($i=0; $i<db_num_rows($db_res); $i++) {
  $ren = db_fetch_object($db_res, $i);
?>
   <tr>
      <td valign="top">&nbsp;</td>
      <td class="serie"><? echo $ren->id ?></td>
      <td class="fecha"><? echo $ren->fecha ?></td>
      <td class="serie"><? echo $ren->rfc ?></td>
      <td><? printf("%s %d", $ren->dom_calle, $ren->dom_numero) ?> </td>
      <td><? printf("%s, %s. C.P. ", $ren->dom_ciudad, $ren->dom_edo, $ren->dom_cp) ?> </td>
      <td class="moneda"><? printf("%.2f", $ren->subtotal + $ren->iva) ?></td>
    </tr>
<?
}
?>
  </tbody>
</table>
