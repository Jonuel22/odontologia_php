<?php 
include ("../conexion.php");
$id=mysqli_query($conexion,"SELECT COUNT(id_per) FROM persona");
if(isset($_POST['guardar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['apellido'])){
        $nombre=$_POST['nombre'];
        $apellido=$_POST['apellido'];
        $sexo= $_POST['sexo'];
        $documento= $_POST['documento'];
        $tip_docu= $_POST['tip_docu'];
        $fec_nac= $_POST['fec_nac'];
        $telefono= $_POST['telefono'];
        $direccion= $_POST['direccion'];
        $email= $_POST['correo'];
        $id_dir=$_POST['direccion'];
        $row = mysqli_fetch_array($id);
        $id=$row[0]+1;
        //DOCUMENTOS------------------------------------
        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_docu FROM documentos WHERE num_docu='$documento')");
        $resp=mysqli_fetch_array($validar);
        $id_docu=mysqli_query($conexion,"SELECT COUNT(id_docu) FROM documentos");	

        if ($resp[0]==0) {
            $row = mysqli_fetch_array($id_docu);
            $id_docu=$row[0]+1;
            $sql="INSERT INTO  documentos (id_docu,num_docu,tip_docu) values('$id_docu','$documento','$tip_docu')";
            try {
                $query=mysqli_query($conexion,$sql);
            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

        }
        else{
            $row = mysqli_fetch_array($id_docu);
            $id_docu=$row[0];
        }

        //EMAIL------------------------------------


        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_email FROM email WHERE email='$email')");

        $resp=mysqli_fetch_array($validar);

        $id_email=mysqli_query($conexion,"SELECT COUNT(id_email) FROM email");	

        if ($resp[0]==0) {
            $row = mysqli_fetch_array($id_email);
            $id_email=$row[0]+1;
            $sql="INSERT INTO  email (id_email,email) values('$id_email','$email')";


            try {
                $query=mysqli_query($conexion,$sql);
            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

        }
        else{
            $row = mysqli_fetch_array($id_email);
            $id_email=$row[0];	
        }

        //TELEFONO------------------------------------
        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_telf FROM telefono WHERE n_telf='$telefono')");

        $resp=mysqli_fetch_array($validar);

        $id_telf=mysqli_query($conexion,"SELECT COUNT(id_telf) FROM telefono");	

        if ($resp[0]==0) {
            $row = mysqli_fetch_array($id_telf);
            $id_telf=$row[0]+1;	
            $sql="INSERT INTO  telefono (id_telf,n_telf,tip_telf) values('$id_telf','$telefono','1')";

            try {
                $query=mysqli_query($conexion,$sql);
            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

        }
        else{
            $row = mysqli_fetch_array($id_telf);
            $id_telf=$row[0];	
        }

        //DATOS-------------------------------------------

        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_datos FROM datos WHERE email='$id_email'AND dat_dir='$id_dir'AND dat_docu='$id_docu' AND dat_telf='$id_telf')");
        $resp=mysqli_fetch_array($validar);
        $id_datos=mysqli_query($conexion,"SELECT COUNT(id_datos) FROM datos");	


        if ($resp[0]==0) {
            $row = mysqli_fetch_array($id_datos);
            $id_datos=$row[0]+1;

            $sql="INSERT INTO  datos (id_datos,dat_dir,dat_docu,dat_telf,email) values('$id_datos','$id_dir','$id_docu','$id_telf','$id_email')";

            try {
                $query=mysqli_query($conexion,$sql);

            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

        }
        else{
            $row = mysqli_fetch_array($id_datos);
            $id_datos=$row[0];	
        }

        

        //APELLIDOS----------------------------------
        $id_ape=mysqli_query($conexion,"SELECT MAX(id_ape) FROM apellidos");	
        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_ape FROM apellidos WHERE apellido='$apellido')");
        $resp=mysqli_fetch_array($validar);

        $row = mysqli_fetch_array($id_ape);
        if ($resp[0]==0) {
            $id_ape=$row[0]+1;
            $sql="INSERT INTO  apellidos (id_ape,apellido) values('$id_ape','$apellido')";
            try {
                
                echo $sql;
                $query=mysqli_query($conexion,$sql);

            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

        }
        else{
            $id_ape=$row[0];	
        }
        //PERSONA-------------------------------------------
        $sql="INSERT INTO  persona (id_per,nom_per,ape_per,fec_nac,sex_per,datos) values ('$id','$nombre','$id_ape','$fec_nac','$sexo','$id_datos')";
        try {
            $query=mysqli_query($conexion,$sql);
           

        } 
        catch (Exception $e) 
        {
            echo "ERROR:{$e}";	

        }
        echo'<script type="text/javascript">
        alert("Guardado");
        window.location.href="consulta_persona.php";
        </script>';
       
       

    }else{
        
        echo'<script type="text/javascript">
        alert("Campos Vacios");
        window.location.href="consulta_persona.php";
        </script>';
        
    }
}

