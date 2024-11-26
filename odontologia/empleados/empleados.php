<?php 
include("../includes/header.php");
include ("../conexion.php");


$sql=mysqli_query($conexion,"SELECT COUNT(id_per) FROM persona");
$max=mysqli_fetch_array($sql);
?>

    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6">

        <h2 class="w-100 text-center mb-4">Registro de empleado</h2>

        <hr style="color: #9999" />

        <form id="formulario" method="POST" action="insertar_empleado.php">

        <div class="mb-3">

<label for="txt_codigo" class="form-label">Persona</label>
<select class="form-control" name="id_per" required="">
  <option value="">Seleccione una persona</option>
  <?php
$query = mysqli_query($conexion,"SELECT per.id_per as id,CONCAT(per.nom_per,' ',ape.apellido) as Persona from persona per INNER JOIN apellidos ape ON per.ape_per=ape.id_ape");
while ($valores = mysqli_fetch_array($query)) {
  echo '<option value='.$valores["id"].'>'.$valores["Persona"].'</option>';
}
?>
</select>
</div>

            <label for="cargo" class="form-label">Cargo a otorgar</label>
            <div class="mb-3 input-group">
 
   
              <select class="form-control" name="cargo">
              <?php
                $query = $conexion -> query ("SELECT * FROM cargos");
                while ($valores = mysqli_fetch_array($query)) {
                echo '<option>'.$valores["cargo"].'</option>';
                } 
              ?>
        
              </select>
              <br>
              <button id="btncrear" type="button" class="btn btn-secondary" style="width: 20%"  data-bs-toggle="modal" data-bs-target="#modalCargo">Añadir</button>
            </div>
            <button type="submit" class="btn btn-primary w-100 text-uppercase fw-bold">Insertar</button>

        </form>

    </div>


<!---->
<div id="modalCargo" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">AGREGAR CARGO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if (isset($_SESSION['message'])) { ?>
            <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible fade show" role="alert">
              <?= $_SESSION['message']?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php session_unset(); } ?>

        <form action="insertar_cargo.php" method="POST">
          <div class="mb-3">
            <label for="cargo" class="col-form-label">Cargo:</label>
            <input type="text" class="form-control"  name="cargo" required="" placeholder="Agregue el cargo">
          </div>  
          <div class="mb-3">
            <label for="sueldo" class="col-form-label">Sueldo:</label>
            <input type="number" class="form-control" min="1" name="sueldo" required="">
          </div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="guardar" value="save">Guardar</button>
      </div>
    </form>
    <script src="app.js"></script>

</html>
<?php include("../includes/footer.php")?> 