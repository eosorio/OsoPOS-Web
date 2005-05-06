<?php
  include("../include/general_config.inc");
  include("$OSOPOSWEB_DIR/include/pos-var.inc");
  include("$OSOPOSWEB_DIR/include/pos.inc");
  if (isset($salir)) {
    include("$OSOPOSWEB_DIR/include/logout.inc");
  }
  else {
    include("$OSOPOSWEB_DIR/include/passwd.inc");
  }

define('FPDF_FONTPATH','font/');
require('fpdf.php');

if (empty($alm))
  $alm = 1;

class PDF extends FPDF

{
//Cabecera de página
function Header()
{
  global $alm;
	//Logo
	$this->Image('../imagenes/logo.png',10,8,33);
	//Arial bold 15
	$this->SetFont('Arial','B',15);
	//Movernos a la derecha
	$this->Cell(60);
	//Título
	$titulo = sprintf("Lista de precios y existencias\n Almacen %d", $alm);
	$ancho = $this->GetStringWidth($titulo)+6;
	$this->MultiCell($ancho,10,$titulo,1,'C');
	//Salto de línea
	$this->Ln(10);

	$this->SetFont('Times','B',12);
	$this->Cell(30,6, 'Código', 0, 0, 'C');
	$this->Cell(100,6, 'Descripción', 0, 0, 'C');
	$this->Cell(20,6, 'P. Unitario', 0, 0, 'C');
	$this->Cell(10,6, 'Divisa', 0, 0, 'C');
	$this->Cell(20,6,  'Ex.', 0, 0, 'C');
	$this->Ln(5);

}

//Pie de página
function Footer()
{
	//Posición: a 1,5 cm del final
	$this->SetY(-15);
	//Arial italic 8
	$this->SetFont('Arial','I',8);
	//Número de página
	$this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

  $query = "SELECT al.codigo, ar.descripcion, al.pu, al.divisa, al.cant, ar.id_depto ";
  $query.= "FROM almacen_1 al, articulos ar WHERE al.codigo=ar.codigo ";
  $query.= "AND al.id_alm=$alm ORDER BY ar.id_depto, ar.descripcion ASC";
  
  if (!$db_res = db_query($query, $conn)) {
    $mens = "<div class=\"error_f\">Error al consultar articulos</div>\n";
    die($mens);
  }

  $num_ren = db_num_rows($db_res);

  $id_depto = -1;
  for ($i=0; $i < $num_ren; $i++) {
    $ren = db_fetch_object($db_res, $i);
    if ($id_depto != $ren->id_depto) {
      $pdf->SetFont('Times', 'U', 12);
      $pdf->Cell(100,6, nombre_depto($conn, $ren->id_depto), 0, 1);
      $id_depto = $ren->id_depto;
    }     
    $pu = sprintf("%.2f", $ren->pu);
    $pdf->SetFont('Courier', '', 8);
    $pdf->Cell(30,6, $ren->codigo, 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100,6, $ren->descripcion);
    $pdf->Cell(20,6, $pu, 0, 0, 'R');
    $pdf->Cell(20,6, $ren->divisa, 0, 0, 'C');
    $pdf->Cell(20,6,  $ren->cant, 0, 1, 'C');

  }


$pdf->Output();
?>
