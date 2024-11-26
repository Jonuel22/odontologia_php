<?php 
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");
    $id_dir=mysqli_query($conexion,"SELECT COUNT(id_dir) FROM direccion");
    if($_POST['guardar']){
        $pais=$_POST['pais'];
        $municipio=$_POST['municipio'];
        $provincia= $_POST['provincia'];
        $calle= $_POST['calle'];
        $row = mysqli_fetch_array($id_dir);
        $id_dir=$row[0]+1;

        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_pais FROM pais WHERE nom_pais='$pais')");
        $resp=mysqli_fetch_array($validar);

        if ($resp[0]==0) {
            $id_pais=mysqli_query($conexion,"SELECT COUNT(id_pais) FROM pais");
            $row = mysqli_fetch_array($id_pais);
            $id_pais=$row[0]+1;
            $sql="INSERT INTO pais (id_pais,nom_pais) values ('$id_pais','$pais')";

            try {

                $query=mysqli_query($conexion,$sql);

            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }
	
        }
        else {

            $id_pais=mysqli_query($conexion,"SELECT id_pais FROM pais WHERE nom_pais='$pais'");
            $row = mysqli_fetch_array($id_pais);
            $id_pais=$row[0];
        }


        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_prov FROM provincia WHERE nom_prov='$provincia')");
        $resp=mysqli_fetch_array($validar);
        if ($resp[0]==0) {
            $id_prov=mysqli_query($conexion,"SELECT COUNT(id_prov) FROM provincia");
            $row = mysqli_fetch_array($id_prov);
            $id_prov=$row[0]+1;

            $sql="INSERT INTO provincia (id_prov,pais,nom_prov) values ('$id_prov','$id_pais','$provincia')";

            try {
                $query=mysqli_query($conexion,$sql);

            }    
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

        }
        else{
	
	        $id_prov=mysqli_query($conexion,"SELECT id_prov FROM provincia WHERE nom_prov='$provincia'");
            $row = mysqli_fetch_array($id_prov);
            $id_prov=$row[0];
        }


        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_mun FROM municipio WHERE nom_mun='$municipio')");
        $resp=mysqli_fetch_array($validar);
        if ($resp[0]==0) {
            $id_mun=mysqli_query($conexion,"SELECT COUNT(id_mun) FROM municipio");
            $row = mysqli_fetch_array($id_mun);
            $id_mun=$row[0]+1;

            $sql="INSERT INTO municipio (id_mun,id_prov,nom_mun) values('$id_mun','$id_prov','$municipio')";

            try {
                $query=mysqli_query($conexion,$sql);

            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

        }
        else{

	        $id_mun=mysqli_query($conexion,"SELECT id_mun FROM municipio WHERE nom_mun='$municipio'");
            $row = mysqli_fetch_array($id_mun);
            $id_mun=$row[0];
        }

        $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_calle FROM calles WHERE nom_calle='$calle')");
        $resp=mysqli_fetch_array($validar);

        if ($resp[0]==0) {
            $id_calle=mysqli_query($conexion,"SELECT COUNT(id_calle) FROM calles");
            $row = mysqli_fetch_array($id_calle);
            $id_calle=$row[0]+1;
            $sql="INSERT INTO  calles (id_calle,id_mun,nom_calle) values('$id_calle','$id_mun','$calle')";

            try {
                $query=mysqli_query($conexion,$sql);
            } 
            catch (Exception $e) 
            {
                echo "ERROR:{$e}";	
            }

}
else{
$id_calle=mysqli_query($conexion,"SELECT id_calle FROM calles WHERE nom_calle='$calle'");
$row = mysqli_fetch_array($id_calle);
$id_calle=$row[0];
}



try {
$query=mysqli_query($conexion,"INSERT INTO direccion (id_dir,provincia,calle) values('$id_dir','$id_prov','$id_calle')");
echo'<script type="text/javascript">
    alert("Guardado");
    window.location.href="consulta_direccion.php";
    </script>';
} 
catch (Exception $e) 
{
echo "ERROR:{$e}";	
echo'<script type="text/javascript">
    alert("Error al guardar");
    window.location.href="consulta_direccion.php";
    </script>';
}

    }

?>