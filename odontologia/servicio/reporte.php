<?php
require "../conexion.php";
require "plantilla.php";

if(!empty($_POST)){

    $estado = mysqli_escape_string($conexion, $_POST['servicio']);

//consulta en mysql
$sql = "SELECT s.id_ser, s.nom_ser, s.cost_ser, ds.des_status FROM servicios AS s
LEFT JOIN estados AS ds ON s.status = id_status  WHERE ds.id_status = $estado";
//echo $sql;
//exit;

$resultado = $conexion->query($sql);//hago una colsuta a la base de datos 


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

$pdf->Cell(10, 5, "Id", 1, 0, "C");
$pdf->Cell(60, 5, "Servicio", 1, 0, "C");
$pdf->Cell(60, 5, "Costo", 1, 0, "C");
$pdf->Cell(50, 5, "Estado", 1, 1, "C");

$pdf->SetFont("Arial", "", 8); //tipo de letra del reporte


while($fila = $resultado->fetch_assoc()){
    
    $pdf->Cell(10, 5, $fila['id_ser'], 1, 0, "C");
    $pdf->Cell(60, 5, utf8_decode($fila['nom_ser']), 1, 0, "C");//funcion que imprime los caracteres especiales 
    $pdf->Cell(60, 5, "$".number_format($fila['cost_ser'],2), 1, 0, "C");
    $pdf->Cell(50, 5, $fila['des_status'], 1, 1, "C");

}




$pdf->Output();

}


?>