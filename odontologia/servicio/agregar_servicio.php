<?php 

include ("../conexion.php");
$id_servicio=mysqli_query($conexion,"SELECT COUNT(id_ser) FROM servicios");
$servicio=$_POST['servicio'];
$costo=$_POST['costo'];
$row = mysqli_fetch_array($id_servicio);

$validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_ser FROM servicios WHERE nom_ser='$servicio')");
$resp=mysqli_fetch_array($validar);
$id_servicio=mysqli_query($conexion,"SELECT COUNT(id_ser) FROM servicios");	

if ($resp[0]==0) {//Si no existe lo agrego
$row = mysqli_fetch_array($id_servicio);
$id_servicio=$row[0]+1;
$sql="INSERT INTO  servicios (id_ser,nom_ser,cost_ser,status) values('$id_servicio','$servicio','$costo','1')";
try {
$query=mysqli_query($conexion,$sql);
// $id_ser=1;
// $proc="CALL AsignarHora('$id_ser', @horaAleatoria)";
// $query=mysqli_query($conexion,$proc);
// $query=mysqli_query($conexion,"SELECT @horaAleatoria");
// $row = mysqli_fetch_array($query);
// echo $row[0];
echo'<script type="text/javascript">
    alert("Servicio Guardado");
  window.location.href="servicio.php";
    </script>';

} 
catch (Exception $e) 
{
echo "ERROR:{$e}";	
}
}

//header("location: servicio.php");
 ?>