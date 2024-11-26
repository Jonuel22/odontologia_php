<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $query ="SELECT sup.status status,ent.id_ent id_ent,ent.nom_ent Nombre,st.des_status Estado,sup.status FROM suplidores sup inner join entidad ent on ent.id_ent=sup.id_ent inner JOIN estados st on st.id_status=sup.status WHERE id_sup = '$id'";
        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
           // $option = '';
            $option2 = '';
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
                $id_ent=$row['id_ent'];
                $nombre = $row['Nombre'];
                $Estado = $row['Estado'];
                $id_status=$row['status'];
             
            }        
        }
    }

    if(isset($_POST['update'])){
        $id = $_GET['id'];
        //$codigo = $_POST['codigo'];
        $id_ent = $_POST['id_ent'];
                
                $status = $_POST['status'];

       $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT id_sup FROM suplidores WHERE id_ent='$id_ent')");
$resp=mysqli_fetch_array($validar);

//Esto lo uso para que no hayan suplidores con entidades repetidas
if ($resp[0]==0) {

$query = "UPDATE suplidores
        SET 
        id_ent='$id_ent',
        status = '$status' WHERE id_sup='$id'" ;
        
}
 else{

$query = "UPDATE suplidores
        
        SET  status = '$status' WHERE id_sup='$id'" ;
 }

        mysqli_query($conexion, $query);
  header("Location: consulta_suplidores.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


</div>

    <div class="container p-4">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card card-body">
                    
                    
        <form id="formulario" method="POST" action="editarsup.php?id=<?php echo $_GET['id'];?> ">

            <div class="mb-3">
                <label >Entidad: <?php echo $nombre ?></label>
               <select class="form-control" name="id_ent" >
            <option value="<?php echo $id_ent;?>">No Actualizar</option>
            <?php
          $query = mysqli_query($conexion,"SELECT id_ent as id,nom_ent as entidad from entidad");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option value='.$valores["id"].'>'.$valores["entidad"].'</option>';
          }
        ?>
          </select>
           <label for="">ESTADO: <?php echo $Estado ?></label>
<select class="form-control" name="status">
    <option value="<?php echo $id_status;?>">No Actualizar</option>
<option value="1">ACTIVO</option>
<option value="2">INACTIVO</option>
</select>
            </div>


                    <button class="btn btn-success" name="update">
                        Update
                    </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

