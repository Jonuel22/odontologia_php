<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $query ="
SELECT art.des_art Descripcion,art.nombre Articulo,t.id_tip as id_tip,t.tip_art Tipo,u.id_unidad as id_unidad,u.unidad Unidad,art.precom_art Costo,art.preven_art Precio,CONCAT((art.itbis_art*100),'%') ITBIS,es.des_status Estado,es.id_status id_status FROM articulos art INNER JOIN unidades u on u.id_unidad=art.unidad INNER join tip_articulo t on t.id_tip=art.tip_art inner join estados es on es.id_status=art.status where art.id_art=$id";

        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
            $option = '';
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
$nombre=$row['Articulo'];              
$des_art=$row['Descripcion'];
$preven=$row['Precio'];
$precom=$row['Costo'];
$itbis=$row['ITBIS'];
$id_unidad=$row['id_unidad'];
$unidad=$row['Unidad'];
$id_tip_art=$row['id_tip'];
$tip_art=$row['Tipo'];
$status=$row['Estado'];
$id_status=$row['id_status'];
$option = '<option value="'.$id_tip_art.'"select>'.$tip_art.'</option>';
$option2 = '<option value="'.$id_unidad.'"select>'.$unidad.'</option>';
 $itbis=trim($itbis,".00%");
                
            }        
        }
    }

    if(isset($_POST['update'])){
        $id = $_GET['id'];
        //$codigo = $_POST['codigo'];
$nombre=$_POST['nombre'];
$des_art=$_POST['des_art'];
$preven=$_POST['preven'];
$precom=$_POST['precom'];
$itbis=$_POST['itbis'];
$unidad=$_POST['unidad'];
$tip_art=$_POST['tip_art'];
$status=$_POST['status'];
//echo $id."--".$des_art."--".$preven."--".$precom."--".$itbis."--".$unidad."--".$tip_art."--";
        $query = "UPDATE articulos 
        SET nombre='$nombre',
            des_art = '$des_art',
            preven_art = '$preven',
            precom_art = '$precom',
            itbis_art = '$itbis', 
            unidad = '$unidad', 
            tip_art = '$tip_art',
            status=$status
        WHERE id_art=$id";
        
        
        mysqli_query($conexion, $query);
        
       header("Location: consulta_articulos.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


</div>

    <div class="container p-4 ">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card card-body">
                    <form action="editararticulo.php?id=<?php echo $_GET['id'];?> " method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="col-form-label">Articulo:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>"  placeholder="Actualiza Nombre">

                    </div>
                    <div class="mb-3">
                        <label for="des_art" class="col-form-label">Descripcion:</label>
                          <textarea name="des_art" class="form-control" id="des_art" cols="20" rows="5" ><?php echo $des_art ?></textarea>
                      
                    </div>
                   
                    <div class="mb-3">
                        <label for="preven" class="col-form-label">Precio Unitario:</label>
                        <input type="number" step="any" class="form-control" id="preven" name="preven" value="<?php echo $preven;?>"  placeholder="Actualiza Precio Unitario">
                    </div>

                    <div class="mb-3">
                        <label for="precom" class="form-label">Costo Unitario</label>
<input type="number" step="any" class="form-control" name="precom" value="<?php echo $precom ?>">
                    </div>

                    <div class="mb-3">
                        <label for="itbis" class="col-form-label">ITBIS: <?php echo $itbis."%"?></label>
                        <select name="itbis" id="itbis" class="form-control">
                          <option value="<?php echo $itbis/100;?>">No Actualizar</option>
                          <option value="0.16">16%</option>
                          <option value="0.18">18%</option>
                          <option value="0">0%</option>
                        </select>
                    </div>

<div class="mb-3">
                        <label for="unidad" class="col-form-label">UNIDAD: <?php echo $unidad; ?></label>
                        <select name="unidad" id="unidad" class="form-control">
                          <option value="<?php echo $id_unidad ?>">No Actualizar</option>
                          <?php $sql="SELECT * FROM unidades";
$query = $conexion -> query ($sql);

          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_unidad"].'">'.$valores["unidad"].'</option>';
          }
   ?>

                        </select>
                    </div>

<div class="mb-3">
                        <label for="tip_art" class="col-form-label">TIPO DE ARTICULO: <?php echo $tip_art ?></label>
                        <select name="tip_art" id="tip_art" class="form-control">
                          <option value="<?php echo $id_tip_art ?>">No Actualizar</option>
                          <?php 
 $query = $conexion -> query ("SELECT * FROM tip_articulo");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_tip"].'">'.$valores["tip_art"].'</option>';
          }
   ?>
                        </select>
                    </div>
<div class="mb-3">
                        <label for="status" class="col-form-label">ESTADO: <?php echo $status ?></label>
                        <select name="status" id="status" class="form-control">
                          <option value="<?php echo $id_status ?>">No Actualizar</option>
     <option value="1">ACTIVO</option>
     <option value="2">INACTIVO</option>
                        </select>
                    </div>
                   </div>
 <button class="btn btn-success" name="update">
                       Actualizar
                    </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

