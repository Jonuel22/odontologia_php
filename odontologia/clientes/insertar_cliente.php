<?php 
include("../conexion.php");



$id_ent=$_POST['id_ent'];
$lim_cred=$_POST['lim_cred'];

$validar=mysqli_query($conexion,"SELECT EXISTS(SELECT idcli FROM clientes WHERE id_ent='$id_ent')");
$resp=mysqli_fetch_array($validar);


if ($resp[0]==0) {
$sql="INSERT INTO  clientes (id_ent,lim_cred,status) values('$id_ent','$lim_cred','1')";
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
alert("cliente ya registrado");
window.location.href="consulta_clientes.php";
</script>';

}
else{
echo'<script type="text/javascript">
alert("Datos Guardados");
window.location.href="clientes.php";
</script>';
}
 ?>