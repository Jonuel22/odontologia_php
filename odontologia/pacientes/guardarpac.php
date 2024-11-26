<?php 
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");
    if($_POST['guardar']){
        $id_per=$_POST['id_per'];


        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_pac FROM paciente WHERE id_per='$id_per')"); //valido que no se guarde 1 paciente que ya existe.
        $resp=mysqli_fetch_array($validar);
        
        if($resp[0]==0) //no existe
        {
        $id_pac=mysqli_query($conexion,"SELECT COUNT(id_pac) FROM paciente"); //Con esto hago un "auto increment" sin que el usuario tenga que digitar el ID
        $fec_ingreso=$_POST['fec_ingreso'];
        $seguro=$_POST['seguro'];
        $num_contrato=$_POST['num_contrato'];
        $id_seg=mysqli_query($conexion,"SELECT COUNT(id_seg) FROM seguro");
        $row = mysqli_fetch_array($id_seg);
        $id_seg=$row[0]+1;
        $padecimientos=$_POST['padecimientos'];
        $alergias=$_POST['alergias'];
        $seguro=mysqli_query($conexion,"SELECT id_ent FROM entidad WHERE nom_ent='$seguro'");
        $row = mysqli_fetch_array($seguro);
        $seguro=$row[0];
        
        
        
        //-------------AGREGANDO SEGURO
        $sql="INSERT INTO seguro (id_seg,nom_seg,seg_afi,num_contrato) values ('$id_seg','$seguro','$id_per','$num_contrato')";
        
        try {
        
        $query=mysqli_query($conexion,$sql);
        
        } 
        catch (Exception $e) 
        {
        echo "ERROR:{$e}";	
        }
        
        //------------AGREGANDO PACIENTE
        $row = mysqli_fetch_array($id_pac);
        $id_pac=$row[0]+1;
        
        $sql="INSERT INTO paciente (id_pac,id_per,fec_ingr,seg_pac,status,padec_pac,alerg_pac) values ('$id_pac','$id_per','$fec_ingreso','$id_seg','1','$padecimientos','$alergias')";
        
        try {
        
        $query=mysqli_query($conexion,$sql);
        echo'<script type="text/javascript">
            
            alert("Paciente Guardado");
           window.location.href="consulta_pacientes.php";
            </script>';
        } 
        catch (Exception $e) 
        {
        echo "ERROR:{$e}";	
        }
        
        
        }
        
        
        
        
        else{ //existe
            
        echo'<script type="text/javascript">
            
            alert("El paciente ya existe");
            window.location.href="consulta_pacientes.php";
            </script>';
        
        } 
        
    }

?>