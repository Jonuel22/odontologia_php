<?php 
/*$user='root';
    $pass='';
    $server='localhost';
    $db='odontologia'; 
    try {
$conexion= new mysqli($server,$user,$pass,$db);
    } 
    catch (Exception $e) {
        echo "Error: {$e->getMessage()}";
    }*/

    include("../conexion.php");
$fecha=mysqli_query($conexion,"SELECT CURDATE()");
$row = mysqli_fetch_array($fecha);
$fecha=$row[0];
$id_fac=mysqli_query($conexion,"SELECT COUNT(id_fac) FROM factura");
$row = mysqli_fetch_array($id_fac);
$id_fac=$row[0]+1;
$pac=$_POST['pac_id'];
$cita=$_POST['cita'];
$pago=$_POST['pago'];
$sql="SELECT ser.cost_ser FROM servicios ser INNER JOIN evento e ON e.servicio=ser.id_ser WHERE e.id='$cita'";     
$query = mysqli_query($conexion,$sql);
$valida_p = mysqli_fetch_array($query);
$devuelta=0;
if($valida_p[0]<=$pago){
echo "<br>";
// echo "se pago to";

$sql2 = "UPDATE evento SET status_pago = 6 WHERE evento.id = '$cita'"; //Pongo la cita como PAGADA
try {
	$query2 = mysqli_query($conexion,$sql2);
} catch (Exception $e) {

echo "ERROR:{".$e."}";
	
}

}



$sql="SELECT EXISTS(SELECT id_fac FROM factura WHERE id_cita='$cita')"; //Validando existencia de avances de pago en esa cita
$query = mysqli_query($conexion,$sql);
$valida_cit = mysqli_fetch_array($query);

if($valida_cit[0]==0){

	if($valida_p[0]<=$pago){
$devuelta=$pago-$valida_p[0];
$pago=$valida_p[0];
}
//Hay que insertar una nueva factura
$id_fac=$id_fac+1;
$sql="INSERT INTO factura (id_fac,fec_fac,id_cita,id_pac,total_pag) VALUES ('$id_fac','$fecha','$cita','$pac','$pago')";
try {
	$query = mysqli_query($conexion,$sql);
} catch (Exception $e) {

echo "ERROR:{".$e."}";
	
}

}
else{
//Hay que hacer un "update"
$sql="SELECT SUM(fac.total_pag) total_pag FROM factura fac INNER JOIN evento e ON fac.id_cita=e.id WHERE e.paciente='$pac' AND fac.id_cita='$cita'"; //Sacando el total de avances que se le ha hecho a la cita
$query = mysqli_query($conexion,$sql);
$row = mysqli_fetch_array($query);
$t_avance=$row[0];
if (($t_avance+$pago)>$valida_p[0]) {
$devuelta=$t_avance+($pago-$valida_p[0]);
$pago=$valida_p[0]-$t_avance;
}

if($t_avance+$pago==$valida_p[0]){
$sql2 = "UPDATE evento SET status_pago = 6 WHERE evento.id = '$cita'"; //Pongo la cita como PAGADA
try {
	$query2 = mysqli_query($conexion,$sql2);
} catch (Exception $e) {

echo "ERROR:{".$e."}";
	
}
}
$id_fac=$id_fac+1;
$sql="INSERT INTO factura (id_fac,fec_fac,id_cita,id_pac,total_pag) VALUES ('$id_fac','$fecha','$cita','$pac','$pago')";
try {
	$query = mysqli_query($conexion,$sql);

} catch (Exception $e) {

echo "ERROR:{".$e."}";
	
}


}
if ($devuelta>0) {
	  echo'<script type="text/javascript">
    alert("FACTURA REGISTRADA\nDevuelta:'.$devuelta.'");
    window.location.href="elige_paciente.php";
    </script>';

}
else{
echo'<script type="text/javascript">
    alert("FACTURA REGISTRADA");
    window.location.href="elige_paciente.php";
    </script>';
}
?>