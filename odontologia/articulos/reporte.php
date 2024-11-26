<?php
require "../conexion.php";
require "plantilla.php";

if (!empty($_POST)) {

        $estado = mysqli_escape_string($conexion, $_POST['articulo']);

    //consulta en mysql
    $sql = "SELECT art.id_art, art.nombre, art.des_art, art.precom_art, art.preven_art, 
    art.itbis_art, u.unidad,tp.tip_art, et.des_status FROM articulos AS art
    LEFT JOIN unidades AS u ON art.unidad = u.id_unidad  LEFT JOIN tip_articulo AS tp 
    ON art.tip_art = tp.id_tip LEFT JOIN estados AS et  ON art.status = et.id_status  WHERE et.id_status = $estado";
    //echo $sql;
    //exit;

    $resultado = $conexion->query($sql); //hago una colsuta a la base de datos 


    //orientacio de la hoja p-vertical l-horizontal, medida de pdf, tipo de tamaño
    $pdf = new PDF("p", "mm", "letter");
    $pdf->AliasNbPages();
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage(); //agrega una nueva hoja
    //$pdf->SetFont("Arial", "B", 12); //tipo de letra del reporte
    //$pdf->Cell(190, 5, "Reporte de empleados", 0, 1, "C");//celdas(x,y, titulo, border,salto de linea, alineacion)
    //$pdf->Image("images/informacion.png", 10, 5, 13);


    //$pdf->Ln(2);//saltos de linea 

    $pdf->SetFont("Arial", "B", 10); //tipo de letra del reporte

    $pdf->Cell(10, 5, "Id", 1, 0, "C");
    $pdf->Cell(60, 5, "Nombre", 1, 0, "C");
    $pdf->Cell(20, 5, "Compra", 1, 0, "C");
    $pdf->Cell(20, 5, "Venta", 1, 0, "C");
    $pdf->Cell(10, 5, "Itbis", 1, 0, "C");
    $pdf->Cell(20, 5, "Unidad", 1, 0, "C");
    $pdf->Cell(40, 5, "Tipo", 1, 0, "C");
    $pdf->Cell(20, 5, "Estado", 1, 1, "C");

    $pdf->SetFont("Arial", "", 8); //tipo de letra del reporte


    while ($fila = $resultado->fetch_assoc()) {

        $pdf->Cell(10, 5, $fila['id_art'], 1, 0, "C");
        $pdf->Cell(60, 5, utf8_decode($fila['nombre']), 1, 0, "C"); //funcion que imprime los caracteres especiales 
        $pdf->Cell(20, 5, $fila['precom_art'], 1, 0, "C");
        $pdf->Cell(20, 5, $fila['preven_art'], 1, 0, "C");
        $pdf->Cell(10, 5, $fila['itbis_art'], 1, 0, "C");
        $pdf->Cell(20, 5, $fila['unidad'], 1, 0, "C");
        $pdf->Cell(40, 5, $fila['tip_art'], 1, 0, "C");
        $pdf->Cell(20, 5, $fila['des_status'], 1, 1, "C");
    }




    $pdf->Output();
}
