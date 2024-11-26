<?php 
include ("../conexion.php");
$id_tip=mysqli_query($conexion,"SELECT COUNT(id_tip) FROM tip_documento");
if(isset($_POST['guardar'])){
    if(!empty($_POST['tip_docu'])){
        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_tip FROM tip_documento WHERE tip_docu='$tip_docu')");
        $resp=mysqli_fetch_array($validar);
        $id_tip=mysqli_query($conexion,"SELECT COUNT(id_tip) FROM tip_documento");	

        if ($resp[0]==0) {//Si no existe lo agrego
            $row = mysqli_fetch_array($id_tip);
            $id_tip=$row[0]+1;
            $sql="INSERT INTO  tip_documento (id_tip,tip_docu) values('$id_tip','$tip_docu')";

            try {
                $query=mysqli_query($conexion,$sql);
                echo'<script type="text/javascript">
                alert("Guardado");
                window.location.href="persona.php";
                </script>';

            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }


        }
        else{
            $row = mysqli_fetch_array($id_tip);
            $id_tip=$row[0];
        }

    }else{
        
        echo'<script type="text/javascript">
        alert("Campos Vacios");
        window.location.href="register.php";
        </script>';
        
    }
}



?>