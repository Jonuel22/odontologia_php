<?php
require "../includes/fpdf/fpdf.php";

class PDF extends FPDF
{
// Cabecera de página 
function Header()//debe llamarse asi para que funcione
{ 
    // Logo  se trabaja con this directamente
    $this->Image("../includes/images/informacion.png", 10, 5, 13);
    // Arial bold 15
    $this->SetFont("Arial", "B", 12); //tipo de letra del reporte
    // Título
    $this->Cell(25);
    $this->Cell(150, 5, utf8_decode("Cierre Caja"), 0, 0, "C");//celdas(x,y, titulo, border,salto de linea, alineacion)
    //fecha
    $this->SetFont("Arial", "", 10); //tipo de letra del reporte
    $this->Cell(25, 5, "Fecha: ".date("d/m/y"), 0, 1, "C");
    // Salto de línea
    $this->Ln(10);
}

// Pie de página
function Footer()//debe llamarse asi para que funcione
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);//de abajo hacia arriba
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página 1/10
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');//0 significa que utilizara todo el ancho de la pagina 
}
}



?>