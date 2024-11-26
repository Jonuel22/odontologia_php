<body> <div class="no-notificacion">

    

<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "odontologia";

$mensaje = "";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Llamar al procedimiento almacenado y obtener el mensaje de salida
$sql = "CALL CheckProductQuantity111(1)"; "SELECT @notification AS mensaje";
$result = $conn->multi_query($sql);

if ($result) {
    // Obtener y mostrar el mensaje de salida
    do {
        if ($result = $conn->store_result()) {
            $row = $result->fetch_assoc();
            echo "Notificación: " . $row['notification'] . "<br>";
            $result->free(); 
        }
    } while ($conn->more_results() && $conn->next_result());

    if (!$conn->errno) {
        echo '<div class="no-notificacion">No hay mas notificaciones</div>';
    }
} else {
    echo "Error al llamar al procedimiento: " . $conn->error;
}

// Cerrar la conexión
$conn->close();

?>
</div>
</body>
<style>

.no-notificacion {
    background-color: #e0fad8; /* Un color de fondo rojo claro, por ejemplo */
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #135219; /* Un borde rojo oscuro, por ejemplo */
    color: #000000; /* Un color de texto rojo oscuro, por ejemplo */
}

body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
</style>

