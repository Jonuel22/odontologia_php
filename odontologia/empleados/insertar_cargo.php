<?php 
include ("../conexion.php");
$id_cargo=mysqli_query($conexion,"SELECT COUNT(id_cargo) FROM cargos");
$cargo=$_POST['cargo'];
$sueldo=$_POST['sueldo'];
$row = mysqli_fetch_array($id_cargo);

$validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_cargo FROM cargos WHERE cargo='$cargo')");
$resp=mysqli_fetch_array($validar);
$id_cargo=mysqli_query($conexion,"SELECT COUNT(id_cargo) FROM cargos");	

if ($resp[0]==0) {//Si no existe lo agrego
$row = mysqli_fetch_array($id_cargo);
$id_cargo=$row[0]+1;
$sql="INSERT INTO  cargos (id_cargo,cargo,sueldo) values('$id_cargo','$cargo','$sueldo')";
try {
$query=mysqli_query($conexion,$sql);
} 
catch (Exception $e) 
{
echo "ERROR:{$e}";	
}
}

header("location: empleados.php");

 ?>