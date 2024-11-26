<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
$conexion = new mysqli("localhost", "root", "", "odontologia");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recibe datos del formulario de detalles
$personaId = $_POST['persona_id'];
//$detalle1 = $_POST['detalle1'];
//$detalle2 = $_POST['detalle2'];

// Inserta en la tabla detalles
$queryDetalles = "INSERT INTO informacion_adicional (id_candt) VALUES ('$personaId')";
$conexion->query($queryDetalles);

// Cierra la conexión
$conexion->close();
?>