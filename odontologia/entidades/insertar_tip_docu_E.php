<?php 
include ("../conexion.php");
$tip_docu=$_POST['tip_docu'];
$validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_tip FROM tip_documento WHERE tip_docu='$tip_docu')");
$resp=mysqli_fetch_array($validar);
$id_tip=mysqli_query($conexion,"SELECT COUNT(id_tip) FROM tip_documento");	
$row = mysqli_fetch_array($id_tip);
$id_tip=$row[0];
if ($resp[0]==0) {//Si no existe lo agrego
$id_tip=$row[0]+1;
$sql="INSERT INTO  tip_documento (id_tip,tip_docu) values('$id_tip','$tip_docu')";

try {
$query=mysqli_query($conexion,$sql);
echo'<script type="text/javascript">
    alert("REGISTRADO");
        window.location.href="entidad.php";
    </script>';
} 
catch (Exception $e) 
{
echo "ERROR:{$e}";	
}


}
else{
$row = mysqli_fetch_array($id_tip);
$id_tip=$row[0];
echo'<script type="text/javascript">
    alert("YA EL DOCUMENTO EXISTE");
            window.location.href="entidad.php";
    </script>';
}
//header("location: entidad.php");
 ?>