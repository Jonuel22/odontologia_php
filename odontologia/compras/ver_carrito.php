<?php include_once "encabezado.php" ?>
<?php 
include_once "funciones.php";
include("../conexion.php");
$articulos = obtenerProductosEnCarrito();
if (count($articulos) <= 0) {
?>

    <section class="hero is-info">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Todavía no hay articulos
                </h1>
                <h2 class="subtitle">
                    Visita la tienda para agregar articulos a tu carrito
                </h2>
                <a href="tienda.php" class="button is-warning">Ver tienda</a>
            </div>
        </div>
    </section>
<?php } else { ?>

    <div class="columns">
        <div class="column">
            <form method="POST" action="terminar_compra.php">
            <h2 class="is-size-2">Mi carrito de compras</h2>
             <h4><a href="../suplidores/consulta_suplidores.php">Suplidor</a></h4>
<select class="select-css" name="entidad" required="">
    
    <option value="">Seleccione suplidor...</option>
<?php   
$sql="SELECT en.id_ent,en.nom_ent Nombre from suplidores sup inner join  entidad en on en.id_ent=sup.id_ent where sup.status=1";


                            $query = mysqli_query($conexion,$sql);
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value='.$valores["id_ent"].'>'.$valores["Nombre"].'</option>';
                            
                            }
 ?>
</select>
            <table class="table">
                <thead>
                    <tr>
                        <th>ARTICULO</th>
                        <th>DESCRIPCION</th>
                        <th>COSTO</th>
                        <th>CANTIDAD</th>
                        <th>SUB-TOTAL</th>
                        <th>ITBIS</th>
                        <th>TOTAL</th>
                        <th>Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aqui defino variables para mis operaciones-->
                    <?php

                                      
                    $total_itbis = 0;
                    $total_prod = 0;
                    $ITBIS = 0;
                    $t_subtotal=0;
                    foreach ($articulos as $articulo) {
                        $cant=$articulo->cant_art;
                        $subtotal=$articulo->precom_art*$cant;
                        $t_subtotal+=$subtotal;
                        $ITBIS=$articulo->itbis_art;
                        //$total += $articulo->precom_art;
                      //  $acum = $total;
                        $total_itbis +=$ITBIS*$subtotal;
                        $total_prod = ($articulo->precom_art * $cant)+($ITBIS*$articulo->precom_art * $cant);

                    ?>
                        <tr>
                            <td><?php echo $articulo->nombre ?></td>
                            <td><?php echo $articulo->des_art ?></td>
                            <td align="right">$<?php echo number_format($articulo->precom_art, 2) ?></td>
                            <td align="right"><?php echo $cant ?></td> 
                            <td align="right"><?php echo number_format($subtotal,2) ?></td>
                            <td align="right"><?php echo number_format($ITBIS*$subtotal,2) ?></td>
                            <td align="right"><?php echo number_format($total_prod, 2) ?></td>
                            
                            <td>
                                <form action="eliminar_del_carrito.php" method="post">
                                    <input type="hidden" name="id_producto" value="<?php echo $articulo->id_art ?>">
                                    <input type="hidden" name="redireccionar_carrito">
                                    <input type="hidden" id="bandera" value="0" name="bandera">
                                    <button class="button is-danger" onclick="elimina()">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                            </td>
                        <?php } ?>

                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td colspan="2" class="is-size-5 has-text-right">
                            <strong>Sub Total</strong>
                        </td>
                        <td colspan="2" class="is-size-5">
                            $<?php echo number_format($t_subtotal, 2) ?>
                        </td>
                        </tr>
                        <tr>
                            <td style="border: none" ></td>
                        <td style="border: none" ></td>
                        <td style="border: none" ></td>
                        <td style="border: none" ></td>
                        <td style="border: none"  colspan="2" class="is-size-5 has-text-right"><strong class="is-size-5 has-text-right">Total ITBIS</strong></td>
                        <td style="border: none"  colspan="2" class="is-size-5">
                            $<?php echo number_format($total_itbis, 2) ?>
                        </td>
</tr>
<tr>
    <td style="border: none" ></td>
                        <td style="border: none" ></td>
                        <td style="border: none" ></td>
                        <td style="border: none" ></td>
<td style="border: none"  colspan="2" class="is-size-5 has-text-right"><strong>Total General</strong></td>
                        <td style="border: none"  colspan="2" class="is-size-5">
                            $<?php echo number_format($total_itbis+$t_subtotal, 2) ?>
                        </td>
                    
</tr>
                </tfoot>

            </table>
<strong>COMPRA</strong>
            
                
            <input type="radio"  name="tip_fac[]" value="2"
             checked >AL CONTADO

             <input  type="radio"  name="tip_fac[]" value="1"
           >A CREDITO

           <br>
<br>
            <button  style="border:none;"><a  class="button is-success is-large"><i class="fa fa-check"></i>&nbsp;Terminar compra</a></button>
                
            </form>
<script>
    
function elimina(){
document.getElementById('bandera').value=1;

}    
</script>
        </div>
    </div>
<?php } ?>
