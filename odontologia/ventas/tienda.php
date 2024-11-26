<?php include_once "encabezado.php" ?>
<?php
include_once "funciones.php";
$articulos = obtenerProductos();

?>
<div class="columns">
    <div class="column">
        <h2 class="is-size-2">Catalogo de articulos</h2>
    </div>
</div>
<?php foreach ($articulos as $articulo) { ?>
    <div class="columns">
        <div class="column is-full">
            <div class="card">  
                    <?php 

                     ?>
                <header class="card-header">
                    <p class="card-header-title is-size-4">
                        <?php echo $articulo->nombre ?>

                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <?php echo $articulo->des_art ?>
                    </div>
                    <h1 class="is-size-3">$<?php echo number_format($articulo->preven_art, 2) ?></h1>
                    <?php if (productoYaEstaEnCarrito($articulo->id_art)) { ?>
                        <form action="eliminar_del_carrito.php" method="post">
                            <input type="hidden" name="id_producto" value="<?php echo $articulo->id_art ?>">
                            <span class="button is-success">
                                <i class="fa fa-check"></i>&nbsp;En el carrito
                            </span>
                            <button class="button is-danger">
                                <i class="fa fa-trash-o"></i>&nbsp;Quitar
                            </button>
                        </form>
                    <?php } else { ?>


                        <form action="agregar_al_carrito.php" method="post" >
                            <input type="hidden" name="id_producto" value="<?php echo $articulo->id_art ?>">
                            <input type="hidden" name="concepto" value="1">
                            
                            <?php if ($articulo->cant_exist<1){ ?>
                                <h3 style="color: red">(PRODUCTO AGOTADO)</h3>
                            <?php }else{ ?>
                            

                            <button class="button is-primary" >
                                <i class="fa fa-cart-plus"></i>&nbsp;Agregar al carrito 

                            </button>
                            <input type="number" step="any" placeholder="Cantidad" min="1" name="cantidad" required="" <?php echo "max='$articulo->cant_exist'" ?>>
                        <?php } ?>
                            <H2>itbis no incluido en el precio</H2>
                        </form>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
<?php } ?>
