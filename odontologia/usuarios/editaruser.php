<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $query = "SELECT u.id_user, u.nom_user, u.pass_user, n.id_niv, n.nivel, et.id_status,et.des_status
        from usuarios u INNER JOIN niveles as n ON u.nivel = n.id_niv  INNER JOIN
        estados as et ON u.status = et.id_status WHERE id_user = $id";
        $result = mysqli_query($conexion, $query);
        if(mysqli_num_rows($result) == 1){
            $option = '';
            $option2 = '';
            while($row = mysqli_fetch_array($result)){
                $nombre = $row['nom_user'];
                $password = $row['pass_user'];
                $idnivel = $row['id_niv'];
                $nivel = $row['nivel'];
                $idstatus = $row['id_status'];
                $estatus = $row['des_status'];

                if($idnivel == 1){
                    $option = '<option value="'.$idnivel.'"select>'.$nivel.'</option>';
                }
                else if($idnivel == 2){
                    $option = '<option value="'.$idnivel.'"select>'.$nivel.'</option>';
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
        $nombre = $_POST['nombre'];
        $password = $_POST['password'];
        $nivel = $_POST['nivel'];
        $estatus = $_POST['estado'];

      $query = "UPDATE usuarios, niveles, estados 
      set  usuarios.nom_user  = '$nombre', usuarios.pass_user ='$password', usuarios.nivel = '$nivel', usuarios.status='$estatus' 
      WHERE usuarios.nivel = niveles.id_niv and usuarios.status = estados.id_status and  id_user = $id";
      mysqli_query($conexion, $query);
      $_SESSION['message'] = 'Actualizacion completa';
      $_SESSION['message_type'] = 'warning';
      header("Location: consultauser.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 50%">

    
        <div class="row">
            
                <div class="card card-body">
                    <form action="editaruser.php?id=<?php echo $_GET['id'];?> " method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="col-form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>"  placeholder="Actualiza Nombre">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="col-form-label">Password:</label>
                        <input type="text" class="form-control" id="password" name="password" value="<?php echo $password;?>"  placeholder="Actualiza Password">
                    </div>
                    <div class="mb-3">
                        <label for="niveles" class="form-label">Nivel Usuario</label>
                        <br>
   
                        <?php
                            include("../conexion.php");
                            $query_nivel =  mysqli_query($conexion, "SELECT * FROM niveles");
                            mysqli_close($conexion);
                            $result_nivel = mysqli_num_rows($query_nivel);
                        ?>

                        <select name="nivel" id="nivel" class="notItemOne">
                            <?php
                                echo $option;
                                if($result_nivel >0){
                                    while($nivel = mysqli_fetch_array($query_nivel)){

                            ?>
                                    <option value="<?php echo $nivel["id_niv"]; ?>"><?php echo $nivel["nivel"] ?></option>
                                    
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
                    <button class="btn btn-success" name="update">
                        Update
                    </button>
                    </form>
                </div>
            

        </div>
   
</div>

