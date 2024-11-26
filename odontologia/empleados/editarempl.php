<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $query ="SELECT ep.id_empl, ps.nom_per, ps.fec_nac, ps.sex_per,a.id_ape,a.apellido,c.cargo Cargo, c.id_cargo,c.sueldo, et.id_status,et.des_status
        from empleados ep INNER JOIN persona as ps ON ep.id_per = ps.id_per INNER JOIN apellidos AS a 
        ON ps.ape_per = a.id_ape  INNER JOIN cargos as c ON ep.cargo = c.id_cargo INNER JOIN
        estados as et ON ep.status = et.id_status WHERE id_empl = $id";
        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
           // $option = '';
            $option2 = '';
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
                $nombre = $row['nom_per'];
                $apellido = $row['apellido'];
                $fecha_nacimiento = $row['fec_nac'];
                $sexo = $row['sex_per'];
                $idcargo = $row['id_cargo'];
$query=mysqli_query($conexion,"SELECT cargo FROM cargos WHERE id_cargo='$idcargo'");
$query=mysqli_fetch_array($query);
             $cargo = $query[0];
                //$cargo = $row['cargo'];
                $sueldo = $row['sueldo'];
                $idstatus = $row['id_status'];
                $estatus = $row['des_status'];
             

               if($idcargo == 1){
                    $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
                }
                else if($idcargo == 2){
                    $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
                }
                else if($idcargo == 3){
                    $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
                }
                else if($idcargo == 4){
                    $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
                }
                else if($idcargo == 5){
                    $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
                }

                    
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
        $apellido = $_POST['apellido'];
        $sexo = $_POST['sexo'];
        $cargo = $_POST['cargo'];
        $sueldo = $_POST['sueldo'];
        $estatus = $_POST['estado'];
        

        $query = "UPDATE empleados, persona, apellidos, cargos, estados   
        SET persona.nom_per = '$nombre' , apellidos.apellido = '$apellido', empleados.cargo = '$cargo' , cargos.sueldo = '$sueldo',
        empleados.status = '$estatus' WHERE empleados.id_per = persona.id_per AND persona.ape_per = apellidos.id_ape 
        and empleados.cargo = cargos.id_cargo 
        and empleados.status = estados.id_status 
        and empleados.id_empl=$id" ;
        
        mysqli_query($conexion, $query);
        
        header("Location: consulta_empleados.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 50%">

  
        <div class="row">
           
                <div class="card card-body">
                    <form action="editarempl.php?id=<?php echo $_GET['id'];?> " method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="col-form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>"  placeholder="Actualiza Nombre">
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="col-form-label">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $apellido;?>"  placeholder="Actualiza Apellido">
                    </div>
                   
                    <label for="txt_nombre" class="form-label">Sexo</label>
                    <select class="form-control" name="sexo">
                    <option>M</option>
                    <option>F</option>
                    </select>
                    <br>

                    <div class="mb-3">
                        <label for="cargo" class="form-label">Cargo a otorgar</label>
                        <br>
   
                        <?php
                            include("../conexion.php");
                            $query_cargo =  mysqli_query($conexion, "SELECT * FROM cargos");
                            mysqli_close($conexion);
                            $result_cargo = mysqli_num_rows($query_cargo);
                        ?>

                        <select name="cargo" id="cargo" class="notItemOne">
                            <?php
                                echo $option;
                                if($result_cargo >0){
                                    while($cargo = mysqli_fetch_array($query_cargo)){

                            ?>
                                    <option value="<?php echo $cargo["id_cargo"]; ?>"><?php echo $cargo["cargo"] ?></option>
                                    
                            <?php      
                                    
                                    }

                                }
                            
                            ?>

                        </select>




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
                   

                    <div class="mb-3">
                        <label for="sueldo" class="col-form-label">Sueldo:</label>
                        <input type="number" class="form-control" id="sueldo" name="sueldo" value="<?php echo $sueldo;?>"  placeholder="Actualiza Sueldo">
                    </div>
                    <button class="btn btn-success" name="update">
                        Update
                    </button>
                    </form>
                </div>
            

        </div>
</div> 

