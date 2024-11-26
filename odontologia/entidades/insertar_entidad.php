<?php 
include ("../conexion.php");
$id=mysqli_query($conexion,"SELECT COUNT(id_ent) FROM entidad");
$nombre=$_POST['nombre'];
$documento= $_POST['documento'];
$tip_docu= $_POST['tip_docu'];
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


//ENTIDAD-------------------------------------------
$sql="INSERT INTO  entidad (id_ent,nom_ent,datos,status) values('$id','$nombre','$id_datos','1')";

try {
$query=mysqli_query($conexion,$sql);
  echo'<script type="text/javascript">
    alert("REGISTRADO");
    window.location.href="entidad.php";
    </script>';
} 
catch (Exception $e) 
{
	echo'<script type="text/javascript">
    alert("ERROR AL REGISTRAR");
    window.location.href="entidad.php";
    </script>';
echo "ERROR:{$e}";	
}
//header("location: entidad.php");
 ?>
