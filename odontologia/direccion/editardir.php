<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $query ="SELECT d.id_dir, c.nom_calle, m.nom_mun, p.nom_prov, pa.nom_pais FROM direccion AS d
        INNER JOIN calles AS c  ON d.calle = c.id_calle INNER JOIN municipio AS m ON c.id_mun= m.id_mun
        INNER JOIN provincia AS p ON m.id_prov = p.id_prov INNER JOIN pais AS pa ON p.pais = pa.id_pais WHERE id_dir = $id";
        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
                $calle = $row['nom_calle'];
                $municipio = $row['nom_mun'];
                $provincia = $row['nom_prov'];
                $pais = $row['nom_pais'];
                

            }        
        }
    }

    if(isset($_POST['update'])){
        $id = $_GET['id'];
        //$codigo = $_POST['codigo'];
        $calle = $_POST['calle'];
        $municipio = $_POST['mun'];
        $provincia = $_POST['prov'];
        $pais = $_POST['pais'];
        
        

        $query = "UPDATE direccion, calles, municipio, provincia, pais   
        SET calles.nom_calle = '$calle' , municipio.nom_mun = '$municipio', provincia.nom_prov = '$provincia' , 
        pais.nom_pais = '$pais'
         WHERE direccion.calle = calles.id_calle AND calles.id_mun = municipio.id_mun
        and municipio.id_prov = provincia.id_prov
        and provincia.pais = pais.id_pais
        and direccion.id_dir=$id" ;
        
        mysqli_query($conexion, $query);
        
        header("Location: consulta_direccion.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


</div>

    <div class="container p-4">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card card-body">
                    <form action="editardir.php?id=<?php echo $_GET['id'];?> " method="POST">
                    <div class="mb-3">
            <label for="pais" class="col-form-label">Pais:</label>
            <input type="text" class="form-control" id="pais" name="pais" value="<?php echo $pais;?>" required="" placeholder="Pais">
          </div>
        
          <div class="mb-3">
            <label for="prov" class="col-form-label">Provincia:</label>
            <input type="text" class="form-control" id="prov" name="prov" value="<?php echo $provincia;?>" required="" placeholder="Provincia">
          </div>

          <div class="mb-3">
            <label for="mun" class="col-form-label">Municipio:</label>
            <input type="text" class="form-control" id="mun" name="mun" value="<?php echo $municipio;?>" required="" placeholder="Municipio">
          </div>

          <div class="mb-3">
            <label for="calle" class="col-form-label">Calle:</label>
            <input type="text" class="form-control" id="calle" name="calle" value="<?php echo $calle;?>" required="" placeholder="Calle">
          </div>
                    <button class="btn btn-success" name="update">
                        Update
                    </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

