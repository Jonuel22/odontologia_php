<?php
include("../includes/reportes/header.php");
require "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/estilosr.css">
    <title>Reporte</title>
</head>
<body>

<form action="reporte.php" class="reporte-form validate-form" method="post" autocomplete="off">
    <div class="container-reporte">
        <div class="wrap-reporte">
            <label for="" class="reporte-form-title">SELECIONE FECHAS </label>
        
        <div class="form-group">
            <label for="fecha_inicio">Fecha Inicio</label>
            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>

        </div>

        <div class="form-group">
            <label for="fecha_final">Fecha Final</label>
            <input type="date" class="form-control" name="fecha_final" id="fecha_final" required>

        </div>

        <br />

        <div class="container-reporte-form-btn">
                    <div class="wrap-reporte-form-btn">
                        <div class="reporte-form-bgbtn"></div>
                        <button type="submit" value="Iniciar" name="inicio" id="btn"
                            class="reporte-form-btn">Generar</button>
                    </div>
                    <br>
                </div>

        
        </div>
        </div>
    </div>


</form>
    
</body>
