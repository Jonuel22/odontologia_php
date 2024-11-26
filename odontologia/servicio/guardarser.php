<?php 
    include('../conexion.php');

   // $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");
    $id_servicio=mysqli_query($conexion,"SELECT COUNT(id_ser) FROM servicios");
    if($_POST['guardar']){
        $servicio = $_POST['servicio'];
        $costo = $_POST['costo'];
        $row = mysqli_fetch_array($id_servicio);

        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_ser FROM servicios WHERE nom_ser='$servicio')");
        $resp=mysqli_fetch_array($validar);
        $id_servicio=mysqli_query($conexion,"SELECT COUNT(id_ser) FROM servicios");	

        
        if ($resp[0]==0) {//Si no existe lo agrego
            $row = mysqli_fetch_array($id_servicio);
            $id_servicio=$row[0]+1;
            $sql="INSERT INTO  servicios (id_ser,nom_ser,cost_ser,status) values('$id_servicio','$servicio','$costo','1')";
            $result= mysqli_query($conexion, $sql);

            if(!$result){
                die("Query failed");
            }

            $_SESSION['message']= 'Tarea Guardada';
            $_SESSION['message_type'] = 'success';

            header("Location: consulta_servicio.php");
        }
    }

?>