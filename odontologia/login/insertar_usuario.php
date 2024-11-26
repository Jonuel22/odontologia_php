<?php 
include ("../conexion.php");
$id=$query=mysqli_query($conexion,"SELECT COUNT(id_user) FROM usuarios");
$row = mysqli_fetch_array($id);
$id=$row[0]+1;
if(isset($_POST['registrar'])){
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $re_pass=$_POST['re_password'];
        $nivel= $_POST['nivel'];
        $nivel=mysqli_query($conexion,"SELECT id_niv FROM niveles WHERE nivel='$nivel'");
        $row = mysqli_fetch_array($nivel);
        $nivel=$row[0];
        //No puede haber 2 o mas usuarios con el mismo username
        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_user FROM usuarios WHERE nom_user='$username')");
        $resp=mysqli_fetch_array($validar);
        if ($resp[0]==0 AND $password==$re_pass) {
            $password=password_hash($password, PASSWORD_DEFAULT);
            $sql="INSERT INTO  usuarios (id_user,nom_user,pass_user,nivel,status) values('$id','$username','$password','$nivel','1')";
            $resultado = mysqli_query($conexion, $sql);
            if($resultado){
                echo'<script type="text/javascript">
                alert("Usuario Guardado");
                window.location.href="index.php";
                </script>';
            }
           
                
               
            
        }
        else{ //El usuario ya existe o las contraseñas no coinciden
            echo'<script type="text/javascript">
            alert("Usuario existente y/o Contraseñas no coinciden");
            window.location.href="index.php";
            </script>';
          }
    }else{
        
        echo'<script type="text/javascript">
        alert("Campos Vacios");
        window.location.href="index.php";
        </script>';
        
    }
}

?>


