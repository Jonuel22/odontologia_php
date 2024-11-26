<?php
    include('../conexion.php');

   // $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $query = "UPDATE clientes 
SET status='2'
        WHERE idcli = $id";
        $result = mysqli_query($conexion, $query);
        if(!$result){
            die("Query failed");
        }

        $_SESSION['message'] = 'Tarea eliminada correctamente';
        $_SESSION['message_type'] = 'danger';
        header("Location: consulta_clientes.php");
    }

?>