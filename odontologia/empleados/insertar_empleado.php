<?php 
include("../conexion.php");


$id_empl=mysqli_query($conexion,"SELECT COUNT(id_empl) FROM empleados");
$id_per=$_POST['id_per'];
$cargo=$_POST['cargo'];


$id_cargo=mysqli_query($conexion,"SELECT id_cargo FROM cargos WHERE cargo='$cargo'");
$row = mysqli_fetch_array($id_cargo);
$id_cargo=$row[0];

$validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_empl FROM empleados WHERE id_per='$id_per' AND cargo='$id_cargo')");
$resp=mysqli_fetch_array($validar);



if ($resp[0]==0) {
$row = mysqli_fetch_array($id_empl);
$id_empl=$row[0]+1;

$sql="INSERT INTO  empleados (id_empl,id_per,cargo,status) values('$id_empl','$id_per','$id_cargo','1')";

try {
$query=mysqli_query($conexion,$sql);


} 
catch (Exception $e) 
{
echo "ERROR:{$e}";	
}

}
echo'<script type="text/javascript">
alert("Datos Guardados");
window.location.href="empleados.php";
</script>';
 ?>