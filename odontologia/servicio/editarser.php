<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $query ="SELECT s.id_ser, s.nom_ser, s.cost_ser,et.id_status, et.des_status FROM servicios AS s
        LEFT JOIN estados AS et  ON s.status = et.id_status WHERE id_ser = $id";
        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
            $option2= '';
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
                $nombre = $row['nom_ser'];
                $costo = $row['cost_ser'];
                $idstatus = $row['id_status'];
                $estatus = $row['des_status'];

                /*---------------------estados--------------------------------------*/
                if($idstatus == 1){
                    $option2 = '<option value="'.$idstatus.'"select>'.$estatus.'</option>';
                }
                else if($idstatus == 2){
                    $option2 = '<option value="'.$idstatus.'"select>'.$estatus.'</option>';
                }
                else if($idstatus == 3){
                    $option2 = '<option value="'.$idstatus.'"select>'.$estatus.'</option>';
                }
                else if($idstatus == 4){
                    $option2 = '<option value="'.$idstatus.'"select>'.$estatus.'</option>';
                }
                else if($idstatus == 5){
                    $option2 = '<option value="'.$idstatus.'"select>'.$estatus.'</option>';
                }

                


            }        
        }
    }

    if(isset($_POST['update'])){
        $id = $_GET['id'];
        //$codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $costo = $_POST['costo'];
        $estatus = $_POST['estado'];
        

        $query = "UPDATE servicios,  estados   
        SET servicios.nom_ser = '$nombre' , servicios.cost_ser = '$costo', servicios.status = '$estatus' WHERE
        servicios.status = estados.id_status 
        and servicios.id_ser=$id" ;
        
        mysqli_query($conexion, $query);
        
        header("Location: consulta_servicio.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 50%">


   
       
                <div class="card card-body">
                    <form action="editarser.php?id=<?php echo $_GET['id'];?> " method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="col-form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>"  placeholder="Actualiza Nombre">
                    </div>
                    
                    <div class="mb-3">
                        <label for="costo" class="col-form-label">Costo:</label>
                        <input type="text" class="form-control" id="costo" name="costo" value="<?php echo $costo;?>"  placeholder="Actualiza Fecha">
                    </div>

                    

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <br>
   
                        <?php
                            include("../conexion.php");
                            $query_estado =  mysqli_query($conexion, "SELECT * FROM estados");
                            mysqli_close($conexion);
                            $result_estado = mysqli_num_rows($query_estado);
                        ?>

                        <select name="estado" id="estado" class="notItemOne">
                            <?php
                                echo $option2;
                                if($result_estado > 0){
                                    while($estatus = mysqli_fetch_array($query_estado)){

                            ?>
                                    <option value="<?php echo $estatus["id_status"]; ?>"><?php echo $estatus["des_status"] ?></option>
                                    
                            <?php      
                                    
                                    }

                                }
                            
                            ?>

                        </select>




                    </div>
                   

                   
                    <button class="btn btn-success" name="update">
                        Update
                    </button>
                    </form>
                </div>
</div>

