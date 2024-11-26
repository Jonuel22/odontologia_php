<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
$conexion = new mysqli("localhost", "root", "", "odontologia");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recibe datos del formulario de información personal
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$documento = $_POST['documento'];
$fecha = $_POST['fechaNacimiento'];
$estado = "En Proceso";


// Inserta en la tabla personas y obtén el ID generado automáticamente
$queryPersona = "INSERT INTO candidatos (nombre, apellido, email, telefono,residencia_act,fecha_nac ,dc_ident, estado)
VALUES ('$nombre', '$apellido', '$email', '$telefono','$direccion', '$fecha','$documento','$estado')";
$conexion->query($queryPersona);
$idPersona = $conexion->insert_id;

// Cierra la conexión
$conexion->close();

// Responde con el ID de la persona en formato JSON
echo json_encode(['id_cand' => $idPersona]);
?>