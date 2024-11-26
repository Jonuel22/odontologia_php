<?php 
include("../includes/header.php");
include ("../conexion.php");


//$sql=mysqli_query($conexion,"SELECT COUNT(id_ent) FROM entidades");
//$max=mysqli_fetch_array($sql);
?>

    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6">

        <h2 class="w-100 text-center mb-4">Registro de cliente</h2>

        <hr style="color: #9999" />

        <form id="formulario" method="POST" action="insertar_cliente.php">

            <div class="mb-3">
               <select class="form-control" name="id_ent">
            <option value="">Seleccione una entidad</option>
            <?php
          $query = mysqli_query($conexion,"SELECT id_ent as id,nom_ent as entidad from entidad");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option value='.$valores["id"].'>'.$valores["entidad"].'</option>';
           }
        ?>
          </select>
<label for="lim_cred">Limite De Credito</label>
          <input type="number" class="form-control" name="lim_cred" step="any" id="lim_cred" min="0" required="">
            </div>

            <button type="submit" class="btn btn-primary w-100 text-upentcase fw-bold">Insertar</button>

        </form>

    </div>


</html>
<?php include("../includes/footer.php")?> 