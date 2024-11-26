<?php
include_once "funciones.php";
include ("../conexion.php");
$bandera=$_POST["bandera"];
if($bandera==1){ //Esto lo uso para saber si el cliente quiere eliminar algo del carrito
if (!isset($_POST["id_producto"])) {
    exit("No hay id_producto");
}

quitarProductoDelCarrito($_POST["id_producto"]);
# Saber si redireccionamos a tienda o al carrito, esto es porque
# llamamos a este archivo desde la tienda y desde el carrito
if (isset($_POST["redireccionar_carrito"])) {
    header("Location: ver_carrito.php");
} else {
    header("Location: tienda.php");
}
return;
}

$articulos = obtenerProductosEnCarrito();
$bd = obtenerConexion();
$fecha=mysqli_query($conexion,"SELECT NOW()");
$row = mysqli_fetch_array($fecha);
$fecha=$row[0];
$entidad=$_POST["entidad"];
//Primero inserto los datos generales de la factura

//Obteniendo articulos del carrito
  $t_g=0;
  $total_itbis = 0;
                    $total_prod = 0;
                    $ITBIS = 0;
                    $t_subtotal=0;
$cant=0;
foreach ($articulos as $articulo) {
                        $cant=$articulo->cant_art;
                        $subtotal=$articulo->precom_art*$cant;
                        $t_subtotal+=$subtotal;
                        $ITBIS=$articulo->itbis_art;
                        $total_itbis +=$ITBIS*$subtotal;
                    }
$t_g=($t_subtotal+$total_itbis);
$tip_fac=$_POST["tip_fac"];
$status=6;
if($tip_fac[0]==1)
$status=3;

$sql="
INSERT INTO factura_compra (fec_fac,tip_fac,subtotal,itbis,total,concepto,entidad,status) 
VALUES 
('$fecha','$tip_fac[0]','$t_subtotal','$total_itbis','$t_g',2,'$entidad','$status')";

try {

	$query=mysqli_query($conexion,$sql);

} catch (Exception $e) {

}

$id_fac=mysqli_query($conexion,"SELECT MAX(id_fac) FROM factura_compra");
$row = mysqli_fetch_array($id_fac);
$id_fac=$row[0];

//Luego inserto el detalle de la factura
foreach ($articulos as $articulo) {
						$id_art=$articulo->id_art;
                        $cant=$articulo->cant_art;
                        $subtotal=$articulo->precom_art*$cant;
                        $ITBIS=$articulo->itbis_art;
                        $total_itbis =$ITBIS*$subtotal;
                        $total = $subtotal+$total_itbis;

                    $sql="INSERT INTO detalle_fact_com(id_fac,id_art,cantidad,subtotal,itbis,total) VALUES ('$id_fac','$id_art','$cant','$subtotal','$total_itbis','$total')";
                   try {
                   	$query=mysqli_query($conexion,$sql);
                   } catch (Exception $e) {
                   	//error
                   }

//Actualizando stock de inventario
$sql2="UPDATE inventario 
SET
inventario.cant_com=inventario.cant_com+'$cant',
inventario.cant_exist=inventario.cant_com-inventario.cant_ven
WHERE inventario.id_art='$id_art';";
                   try {
                   	$query2=mysqli_query($conexion,$sql2);
                   } catch (Exception $e) {
                   	//error
                   }

    }
//Y por ultimo vacio el carrito

$sql="DELETE FROM carrito_usuarios WHERE concepto=2";
try {
	$query=mysqli_query($conexion,$sql);
 echo'<script type="text/javascript">
    alert("COMPRA REGISTRADA");
 window.location.href="../facturas/reportefac.php";
    </script>';


} 
catch (Exception $e) {
	 echo'<script type="text/javascript">
    alert("ERROR");
  window.location.href="ver_carrito.php";
    </script>';
}
