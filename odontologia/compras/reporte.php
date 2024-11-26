<?php
require "../conexion.php";
require "plantilla.php";

if(!empty($_POST)){

    $fecha_inicio = mysqli_escape_string($conexion, $_POST['fecha_inicio']); //protejemos la consulta para evitar codigo malicioso
    $fecha_final = mysqli_escape_string($conexion, $_POST['fecha_final']);
    
    $sql = "SELECT fc.id_fac, tp.tip_fac, fc.subtotal, fc.fec_fac, fc.total, c.concepto From factura_compra AS fc INNER JOIN tip_factura AS tp ON 
    fc.tip_fac = tp.id_tip INNER JOIN concepto_fact AS c ON fc.concepto = c.id
      WHERE fc.concepto =2 AND DATE(fc.fec_fac) BETWEEN CAST('{$fecha_inicio}' AS DATE) AND CAST('{$fecha_final}' AS DATE)";
    $sql2 = "SELECT SUM(total) AS caja FROM factura_compra WHERE concepto = 2 AND DATE(fec_fac) BETWEEN CAST('{$fecha_inicio}' AS DATE) AND CAST('{$fecha_final}' AS DATE)";
    $sql3 = "SELECT SUM(subtotal) AS sub FROM factura_compra WHERE concepto = 2 AND DATE(fec_fac) BETWEEN CAST('{$fecha_inicio}' AS DATE) AND CAST('{$fecha_final}' AS DATE)";

    //echo $sql;

    //exit;

$resultado = $conexion->query($sql);//hago una colsuta a la base de datos 
$resultado2 = $conexion->query($sql2);//hago una colsuta a la base de datos 
$data = mysqli_fetch_array($resultado2);
$caja = $data['caja'];
$resultado3 = $conexion->query($sql3);//hago una colsuta a la base de datos 
$data = mysqli_fetch_array($resultado3);
$sub = $data['sub'];


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
$pdf->Cell(20, 5, "Tipo", 1, 0, "C");
$pdf->Cell(60, 5, "Fecha y Hora", 1, 0, "C");
$pdf->Cell(30, 5, "Subtotal", 1, 0, "C");
$pdf->Cell(30, 5, "Total", 1, 0, "C");
$pdf->Cell(30, 5, "Concepto", 1, 1, "C");


$pdf->SetFont("Arial", "", 8); //tipo de letra del reporte

while($fila = $resultado->fetch_assoc()){
    $pdf->Cell(10);
    $pdf->Cell(10, 5, $fila['id_fac'], 1, 0, "C");
    $pdf->Cell(20, 5, $fila['tip_fac'], 1, 0, "C");
    $pdf->Cell(60, 5, $fila['fec_fac'], 1, 0, "C");
    $pdf->Cell(30, 5, $fila['subtotal'], 1, 0, "C");
    $pdf->Cell(30, 5, $fila['total'], 1, 0, "C");
    $pdf->Cell(30, 5, $fila['concepto'], 1, 1, "C");

}

$pdf->Ln(50);//saltos de linea 
$pdf->Cell(150);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(25, 5, "Sub-Total", 1, 0, "C");
$pdf->Cell(25, 5, $sub, 0, 1, "C");

$pdf->Ln(5);//saltos de linea 
$pdf->Cell(150);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(25, 5, "Total", 1, 0, "C");
$pdf->Cell(25, 5, $caja, 0, 1, "C");



$pdf->Output();

}


?>