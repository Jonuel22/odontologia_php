<?php
include('../conexion.php');
if(isset($_POST['inicio'])){
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        $usuario=$_POST['username'];
        $contraseña=$_POST['password'];
        $encript=mysqli_query($conexion,"SELECT pass_user FROM usuarios where nom_user ='$usuario'");
        $filas=mysqli_fetch_array($encript);
        $encript=$filas[0];
        
        if(password_verify($contraseña, $encript)){
            session_start();
            $_SESSION['cliente'] = $usuario;
            header("location:../principal/menu.php");
        }else{
            echo'<script type="text/javascript">
            alert("Datos incorrectos");
            window.location.href="index.php";
            </script>';
        }
    }else{
        echo'<script type="text/javascript">
        alert("Campos Vacios");
        window.location.href="index.php";
        </script>';
    }
    mysqli_free_result($encript);
    mysqli_close($conexion);
}