<?php 
$conexion = new mysqli("localhost", "root", "", "odontologia");
if(isset($_POST['registrar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['apellido'])){
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $educacion = $_POST['educacion'];
        $experiencia = $_POST['experiencia'];
        $habilidades = $_POST['habilidades'];
        $direccion = $_POST['direccion'];
       
        // Procesar el archivo si se proporcionó
        $archivo_nombre = "";
        $archivo_data = "";

        if ($_FILES['archivo']['size'] > 0) {
            $archivo_nombre = $_FILES['archivo']['name'];
            $archivo_temporal = $_FILES['archivo']['tmp_name'];
            $archivo_data = file_get_contents($archivo_temporal);
        }

        // Obtener el ID del puesto seleccionado
        $puesto_disponible_id = ($_POST['puesto_disponible'] === '0') ? null : $_POST['puesto_disponible'];

        // Insertar los datos en la base de datos
        $sql = "INSERT INTO curriculum (nombre, apellido, email, telefono, educacion, experiencia, habilidades,direccion, archivo_nombre, archivo_data, puesto_disponible_id)
        VALUES ('$nombre', '$apellido', '$email', '$telefono', '$educacion', '$experiencia', '$habilidades','$direccion', '$archivo_nombre', ?, ?)";

        // Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('bs', $archivo_data, $puesto_disponible_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo'<script type="text/javascript">
                alert("Registro Completo");
                window.location.href="postularempl.php";
            </script>';
        } else {
            echo "Error al registrar el currículum: " . $stmt->error;
        }

        // Cerrar la conexión
        $stmt->close();
        $conexion->close();
       

    }else{
        
        echo'<script type="text/javascript">
        alert("Campos Vacios");
        window.location.href="postularempl.php";
        </script>';
        
    }
}





?>
