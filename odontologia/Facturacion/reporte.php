<?php
require "../conexion.php";
require "plantilla.php";



if(!empty($_POST)){

//consulta en mysql

$fecha_inicio = mysqli_escape_string($conexion, $_POST['fecha_inicio']); //protejemos la consulta para evitar codigo malicioso
$fecha_final = mysqli_escape_string($conexion, $_POST['fecha_final']);

$sql = "SELECT id_fac, fec_fac, total_pag From factura WHERE fec_fac BETWEEN '{$fecha_inicio}' AND '{$fecha_final}'";
$sql2 = "SELECT SUM(total_pag) AS caja FROM factura WHERE fec_fac BETWEEN '{$fecha_inicio}' AND '{$fecha_final}'";
//exit;

$resultado = $conexion->query($sql);//hago una colsuta a la base de datos 
$resultado2 = $conexion->query($sql2);//hago una colsuta a la base de datos 
$data = mysqli_fetch_array($resultado2);
$caja = $data['caja'];
//echo $caja;

//orientacio de la hoja p-vertical l-horizontal, medida de pdf, tipo de tamaño
$pdf = new PDF("p", "mm", "letter");
$pdf->AliasNbPages();
$pdf->SetMargins(10,10,10);
$pdf->AddPage();//agrega una nueva hoja
//$pdf->SetFont("Arial", "B", 12); //tipo de letra del reporte
//$pdf->Cell(190, 5, "Reporte de empleados", 0, 1, "C");//celdas(x,y, titulo, border,salto de linea, alineacion)
//$pdf->Image("images/informacion.png", 10, 5, 13);


//$pdf->Ln(2);//saltos de linea 

$pdf->SetFont("Arial", "B", 10); //tipo de letra del reporte
$pdf->Cell(10);
$pdf->Cell(10, 5, "Id", 1, 0, "C");
$pdf->Cell(60, 5, "Fecha", 1, 0, "C");
$pdf->Cell(50, 5, "Pagos", 1, 1, "C");


$pdf->SetFont("Arial", "", 8); //tipo de letra del reporte

while($fila = $resultado->fetch_assoc()){
    $pdf->Cell(10);
    $pdf->Cell(10, 5, $fila['id_fac'], 1, 0, "C");
    $pdf->Cell(60, 5, $fila['fec_fac'], 1, 0, "C");
    $pdf->Cell(50, 5, "$".number_format($fila['total_pag'],2), 1, 1, "C");

}
$pdf->Ln(50);//saltos de linea 
$pdf->Cell(150);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(25, 5, "Total", 1, 0, "C");
$pdf->Cell(25, 5, "$".number_format($caja,2), 0, 1, "C");



$pdf->Output();

}


?>