<?php
include('../conexion.php');

// $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE articulos
    SET status='2'
    WHERE id_art = $id";
    $result = mysqli_query($conexion, $query);
    if (!$result) {
        die("Query failed");
    }
}
$_SESSION['message'] = 'ELIMINADO';
$_SESSION['message_type'] = 'danger';
header("Location: consulta_articulos.php");
