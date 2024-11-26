<?php 	
include ("../conexion.php");
$nombre=$_POST['nombre'];
$des_art=$_POST['des_art'];
$preven=$_POST['preven'];
$precom=$_POST['precom'];
$itbis=$_POST['itbis'];
$unidad=$_POST['unidad'];
$tip_art=$_POST['tip_art'];

$sql="INSERT INTO articulos (
nombre,
des_art,
precom_art,
preven_art,
itbis_art,
unidad,
tip_art,
status
) 
VALUES ('$nombre','$des_art','$precom','$preven','$itbis','$unidad','$tip_art','1')";

try {
	$query=mysqli_query($conexion,$sql);
if(isset($_POST['band_cons'])){
	echo'<script type="text/javascript">
    alert("ARTICULO REGISTRADO");
   window.location.href="consulta_articulos.php";
    </script>';
}
else{
	echo'<script type="text/javascript">
    alert("ARTICULO REGISTRADO");
   window.location.href="articulos.php";
    </script>';

}
} catch (Exception $e) {

	echo'<script type="text/javascript">
    alert("ERROR AL REGISTRAR");
    window.location.href="articulos.php";
    </script>';
}
 ?>