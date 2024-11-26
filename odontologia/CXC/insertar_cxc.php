<?php 
include("../conexion.php");
$met_pag=$_POST['met_pag'];
$fecha=mysqli_query($conexion,"SELECT NOW()");
$row = mysqli_fetch_array($fecha);
$fecha=$row[0];
//$cliente=$_POST['cliente'];
$id_fac=$_POST['id_fac'];
$id_cxc=mysqli_query($conexion,"SELECT id_cxc FROM cxc WHERE cxc.id_fac='$id_fac'");
$row=mysqli_fetch_array($id_cxc);
$id_cxc=$row[0];
$pago=$_POST['pago'];

$sql="SELECT cxc.id_fac,cxc.pend Pendiente,cxc.pagado Pagado,f_c.total A_pagar,cxc.status FROM cxc inner join factura_compra f_c on f_c.id_fac=cxc.id_fac WHERE cxc.id_cxc='$id_cxc'";  

$query = mysqli_query($conexion,$sql);
$valores = mysqli_fetch_array($query);
$devuelta=0;

if($valores["Pagado"]+$pago>=$valores["Pendiente"]){

if($valores["Pagado"]+$pago>$valores["Pendiente"])
$devuelta=$pago-$valores["Pendiente"];
}
$pago=$pago-$devuelta;
$pend=$valores["Pendiente"]-$pago;

//Insertando al historial de pagos
$sql="INSERT INTO hist_cxc(id_cxc,pago,pend,fec_pag,met_pag,status) VALUES ('$id_cxc','$pago','$pend','$fecha','$met_pag[0]',1)";
try {
    $query=mysqli_query($conexion,$sql);
} catch (Exception $e) {
 //error   
}

if($pend==0){
$sql="UPDATE cxc SET status = 6 WHERE cxc.id_cxc = '$id_cxc'";
$sql2="UPDATE factura_compra SET status = 6 WHERE factura_compra.id_fac = '$id_fac'";

try {
    $query=mysqli_query($conexion,$sql);
    $query=mysqli_query($conexion,$sql2);
} catch (Exception $e) {
 //error   
}
//En la BD tengo los triggers para actualizar la cxc, asi que no es necesario que lo haga desde aca
}

if ($devuelta>0) {
	  echo'<script type="text/javascript">
    alert("PAGO REGISTRADO\nDevuelta:'.$devuelta.'");
    window.location.href="cxc.php";
    </script>';

}
else{
echo'<script type="text/javascript">
    alert("PAGO REGISTRADO");
    window.location.href="cxc.php";
    </script>';
}
?>