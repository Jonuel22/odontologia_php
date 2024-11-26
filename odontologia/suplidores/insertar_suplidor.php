<?php 
include("../conexion.php");



$id_ent=$_POST['id_ent'];


$validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_sup FROM suplidores WHERE id_ent='$id_ent')");
$resp=mysqli_fetch_array($validar);


if ($resp[0]==0) {
$sql="INSERT INTO  suplidores (id_ent,status) values('$id_ent','1')";
try {
$query=mysqli_query($conexion,$sql);

} 
catch (Exception $e) 
{
echo "ERROR:{$e}";	
}

}
if(isset($_POST['band_cons']))
{
echo'<script type="text/javascript">
alert("Suplidor Registrado");
window.location.href="consulta_suplidores.php";
</script>';

}
else{
echo'<script type="text/javascript">
alert("Datos Guardados");
window.location.href="suplidores.php";
</script>';
}
 ?>