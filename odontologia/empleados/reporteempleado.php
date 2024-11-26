<?php
include("../includes/reportes/header.php");
require "../conexion.php";
$sql = "SELECT id_cargo, cargo FROM cargos";
$resultado = $conexion->query($sql);
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
            <span class="reporte-form-title">Reporte de Empleados</span>
            <br>
            <div class="select">
			    <select class="form-control" id="cargo" name="cargo">
                    <option value="selected disable">Seleccione cargo</option>
                    <?php while($fila = $resultado->fetch_assoc()){ ?>
                    <option value="<?php echo $fila['id_cargo'];?>"><?php echo $fila['cargo'];?></option>

                    <?php } ?>
	
				</select>
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


</form>
    
</body>
