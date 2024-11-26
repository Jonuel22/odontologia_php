<?php
require "../conexion.php";
require "plantilla.php";

if(!empty($_POST)){

$cargo = mysqli_escape_string($conexion, $_POST['cargo']); //protejemos la consulta para evitar codigo malicioso

//consulta en mysql
$sql = "SELECT ep.id_empl, ps.nom_per, ps.fec_nac, ps.sex_per, 
a.apellido,c.cargo,c.sueldo, et.des_status FROM empleados AS ep
LEFT JOIN persona AS ps ON ep.id_per = ps.id_per LEFT JOIN apellidos AS a 
ON ps.ape_per = a.id_ape LEFT JOIN cargos AS c ON ep.cargo = c.id_cargo
LEFT JOIN estados AS et  ON ep.status = et.id_status WHERE c.id_cargo = $cargo";
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
$pdf->Cell(60, 5, "Nombre", 1, 0, "C");
$pdf->Cell(60, 5, "Apellido", 1, 0, "C");
$pdf->Cell(50, 5, "Cargo", 1, 0, "C");
$pdf->Cell(20, 5, "Sueldo", 1, 1, "C");

$pdf->SetFont("Arial", "", 8); //tipo de letra del reporte


while($fila = $resultado->fetch_assoc()){
    
    $pdf->Cell(10, 5, $fila['id_empl'], 1, 0, "C");
    $pdf->Cell(60, 5, utf8_decode($fila['nom_per']), 1, 0, "C");//funcion que imprime los caracteres especiales 
    $pdf->Cell(60, 5, utf8_decode($fila['apellido']), 1, 0, "C");
    $pdf->Cell(50, 5, $fila['cargo'], 1, 0, "C");
    $pdf->Cell(20, 5, "$".number_format($fila['sueldo'],2), 1, 1, "C");

}




$pdf->Output();

}


?>