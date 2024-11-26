<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $query ="SELECT cli.status status,ent.id_ent id_ent,ent.nom_ent Nombre,cli.lim_cred Credito,st.des_status Estado,cli.status FROM clientes cli inner join entidad ent on ent.id_ent=cli.id_ent inner JOIN estados st on st.id_status=cli.status WHERE idcli = '$id'";
        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
           // $option = '';
            $option2 = '';
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
                $id_ent=$row['id_ent'];
                $nombre = $row['Nombre'];
                $lim_cred = $row['Credito'];
                $Estado = $row['Estado'];
                $id_status=$row['status'];
             
            }        
        }
    }

    if(isset($_POST['update'])){
        $id = $_GET['id'];
        //$codigo = $_POST['codigo'];
        $id_ent = $_POST['id_ent'];
                $lim_cred = $_POST['lim_cred'];
                $status = $_POST['status'];

       $validar=mysqli_query($conexion,"SELECT EXISTS(SELECT idcli FROM clientes WHERE id_ent='$id_ent')");
$resp=mysqli_fetch_array($validar);

//Esto lo uso para que no hayan clientes con entidades repetidas
if ($resp[0]==0) {
echo $lim_cred;
$query = "UPDATE clientes
        SET 
        id_ent='$id_ent',
        lim_cred = '$lim_cred', status = '$status' WHERE idcli='$id'" ;
        
}
 else{

$query = "UPDATE clientes
        
        SET lim_cred = '$lim_cred', status = '$status' WHERE idcli='$id'" ;
 }

        mysqli_query($conexion, $query);
  header("Location: consulta_clientes.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 50%">



    <div class="card card-body">


        <form id="formulario" method="POST" action="editarcli.php?id=<?php echo $_GET['id'];?> ">
            
            <div class="mb-3">
                <label>Entidad:
                    <?php echo $nombre ?>
                </label>
                <select class="form-control" name="id_ent">
                    <option value="<?php echo $id_ent;?>">No Actualizar</option>
                    <?php
          $query = mysqli_query($conexion,"SELECT id_ent as id,nom_ent as entidad from entidad");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option value='.$valores["id"].'>'.$valores["entidad"].'</option>';
          }
            ?>
                </select>
                <br>
                <label for="lim_cred">Limite De Credito</label>
                <input value="<?php echo $lim_cred;?>" type="number" class="form-control" name="lim_cred" step="any"
                    id="lim_cred" min="0">
                <br>
                <label for="">ESTADO:
                    <?php echo $Estado ?>
                </label>
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
