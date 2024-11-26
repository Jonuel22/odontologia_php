<?php
    include('conexion.php');

  //  $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
       $query = "UPDATE usuarios
SET status='2'
        WHERE id_user = $id";
        if(!$result){
            die("Query failed");
        }

        $_SESSION['message'] = 'Tarea eliminada correctamente';
        $_SESSION['message_type'] = 'danger';
        header("Location: consultauser.php");
    }

?>