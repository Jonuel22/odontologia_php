<?php
include_once "funciones.php";
$bandera=$_POST["bandera"];
//echo $bandera;
if($bandera==1){
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


include ("../conexion.php");
$articulos = obtenerProductosEnCarrito();
$bd = obtenerConexion();
$fecha=mysqli_query($conexion,"SELECT NOW()");
$row = mysqli_fetch_array($fecha);
$fecha=$row[0];
$entidad=$_POST["entidad"];
$tip_fac=$_POST["tip_fac"];

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
                        $subtotal=$articulo->preven_art*$cant;
                        $t_subtotal+=$subtotal;
                        $ITBIS=$articulo->itbis_art;
                        $total_itbis +=$ITBIS*$subtotal;
                    }
$t_g=($t_subtotal+$total_itbis);
//$tip_fac=$_POST["tip_fac"];
$status=6;

//Debo tomar en cuenta que si la factura es a credito el total no puede ser mayor al limite de credito disponible del cliente

if($tip_fac[0]==1){
//Si la factura es a credito

$status=3;

$sql="SELECT EXISTS(SELECT entidad FROM factura_compra WHERE entidad='$entidad')";
        $query=mysqli_query($conexion,$sql);
        $resp=mysqli_fetch_array($query); //Validando si el cliente ya ha tenido una factura antes
   
   if($resp[0]==0){


        $query=mysqli_query($conexion,
        	"SELECT cli.lim_cred FROM clientes cli WHERE cli.id_ent='$entidad'");
$disponible=mysqli_fetch_array($query);;
   
   }
else{
$sql="SELECT (cli.lim_cred-SUM(cxc.pend)) Disponible FROM cxc INNER JOIN factura_compra fac on fac.id_fac=cxc.id_fac inner join clientes cli on cli.id_ent=fac.entidad WHERE cli.id_ent='$entidad' GROUP BY fac.entidad ";

        $query=mysqli_query($conexion,$sql);
$disponible=mysqli_fetch_array($query);
}
if($disponible[0]<$t_g){
  
  echo'<script type="text/javascript">
    alert("SU CREDITO NO ES SUFICIENTE");
 window.location.href="ver_carrito.php";
    </script>';
return;
}

}


$sql="
INSERT INTO factura_compra (fec_fac,tip_fac,subtotal,itbis,total,concepto,entidad,status) 
VALUES 
('$fecha','$tip_fac[0]','$t_subtotal','$total_itbis','$t_g',1,'$entidad','$status')";

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
                        $subtotal=$articulo->preven_art*$cant;
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
inventario.cant_ven=inventario.cant_ven+'$cant',
inventario.cant_exist=inventario.cant_exist-'$cant'
WHERE inventario.id_art='$id_art';";
                   try {
                   $query2=mysqli_query($conexion,$sql2);
                   } catch (Exception $e) {
                   	//error
                   }

    }
//Y por ultimo vacio el carrito

$sql="DELETE FROM carrito_usuarios WHERE concepto=1";
try {
	$query=mysqli_query($conexion,$sql);
 echo'<script type="text/javascript">
    alert("VENTA REGISTRADA");
 window.location.href="../facturas/reportefac.php";
    </script>';

} 
catch (Exception $e) {
	 echo'<script type="text/javascript">
    alert("ERROR");
window.location.href="ver_carrito.php";
    </script>';
}
