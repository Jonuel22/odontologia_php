<?php 
include("../conexion.php");

$fecha=mysqli_query($conexion,"SELECT NOW()");
$row = mysqli_fetch_array($fecha);
$fecha=$row[0];
$id_fac=$_POST['id_fac'];
$id_cxp=mysqli_query($conexion,"SELECT id_cxp FROM cxp WHERE cxp.id_fac='$id_fac'");
$row=mysqli_fetch_array($id_cxp);
$id_cxp=$row[0];
$pago=$_POST['pago'];

$sql="SELECT cxp.id_fac,cxp.pend Pendiente,cxp.pagado Pagado,f_c.total A_pagar,cxp.status FROM cxp inner join factura_compra f_c on f_c.id_fac=cxp.id_fac WHERE cxp.id_cxp='$id_cxp'";  

$query = mysqli_query($conexion,$sql);
$valores = mysqli_fetch_array($query);
$devuelta=0;

/*
Debo sumar
Pagado+pago 
SI esa suma >= A_pagar; significa que debe haber una devuelta en caso de ser mayor, y en caso de ser igual la devuelta sera 0 y la cxp de la tabla cxp pasara a ser de status 6(PAGADO) al igual que la factura de factura_compra.
Se insertara en la tabla de hist_cxp con sus atributos. 
*/
if($valores["Pagado"]+$pago>=$valores["Pendiente"]){
//echo "se pago to";
if($valores["Pagado"]+$pago>$valores["Pendiente"])
$devuelta=$pago-$valores["Pendiente"];
}
$pago=$pago-$devuelta;
$pend=$valores["Pendiente"]-$pago;

//Insertando al historial de pagos
$sql="INSERT INTO hist_cxp(id_cxp,pago,pend,fec_pag,status) VALUES ('$id_cxp','$pago','$pend','$fecha',1)";
try {
    $query=mysqli_query($conexion,$sql);
} catch (Exception $e) {
 //error   
}

if($pend==0){
$sql="UPDATE cxp SET status = 6 WHERE cxp.id_cxp = '$id_cxp'";
$sql2="UPDATE factura_compra SET status = 6 WHERE factura_compra.id_fac = '$id_fac'";

try {
    $query=mysqli_query($conexion,$sql);
    $query=mysqli_query($conexion,$sql2);
} catch (Exception $e) {
 //error   
}
//En la BD tengo los triggers para actualizar la CXP, asi que no es necesario que lo haga desde aca
}

if ($devuelta>0) {
	  echo'<script type="text/javascript">
    alert("PAGO REGISTRADO\nDevuelta:'.$devuelta.'");
    window.location.href="cxp.php";
    </script>';

}
else{
echo'<script type="text/javascript">
    alert("PAGO REGISTRADO");
    window.location.href="cxp.php";
    </script>';
}
?>