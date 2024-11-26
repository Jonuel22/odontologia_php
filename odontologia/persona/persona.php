<?php 
include("../includes/header.php");
include ("../conexion.php");

?>

<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 60%">

  <h2 class="w-100 text-center mb-4">Registro de persona</h2>

  <hr style="color: #9999" />

  <form id="formulario" method="POST" action="insertar_persona.php">

    <div class="row">
      <div class="col">
        <div class="mb-3">
          <label for="txt_nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre" required="">
        </div>
        <div class="mb-3">
          <label for="txt_nombre" class="form-label">Apellido</label>
          <input type="text" class="form-control" name="apellido" placeholder="Ingrese el apellido" required="">
        </div>
        <label for="txt_nombre" class="form-label">Sexo</label>
        <select class="form-control" name="sexo">
          <option>M</option>
          <option>F</option>
        </select>

        <div class="mb-3">
          <label for="txt_correo" class="form-label">Correo</label>
          <input type="email" class="form-control" id="txt_correo" name="correo" placeholder="Ejemplo@gmail.com"
            required="">
        </div>

        <div class="mb-3">
          <label for="int_telefono" class="form-label">Documento</label>
          <input type="text" class="form-control" id="documento" name="documento"
            placeholder="402-9999999-6" required="">
        </div>

      </div>

      <div class="col">

        <div class="mb-3">
          <label for="txt_nombre" class="form-label">Fecha de nacimiento</label>
          <input type="date" class="form-control" name="fec_nac" required="">
        </div>
        <div class="mb-3">

          <label for="txt_direccion" class="form-label">
            <a style="color:black" class="navbar-brand" for="txt_direccion"
              href="../direccion/direccion.php">Direccion</a>
          </label>

           <select class="form-control" name="direccion" >
            
<?php $sql="SELECT dr.id_dir as ID,CONCAT(pa.nom_pais,': ',p.nom_prov,', ',m.nom_mun,', ',c.nom_calle) Direccion FROM direccion dr LEFT JOIN calles as c on dr.calle = c.id_calle LEFT JOIN municipio as m on c.id_mun = m.id_mun inner join provincia p on m.id_prov= p.id_prov inner join pais pa on p.pais=pa.id_pais";
$query = $conexion -> query ($sql);

          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["ID"].'">'.$valores["Direccion"].'</option>';
          }
   ?>

          </select>
         
        </div>

        <label for="doccument" class="form-label">Tipo de Documento</label>
        <div class="mb-3 input-group">
          <select class="form-control" name="tip_docu">
            <?php
          $query = $conexion -> query ("SELECT * FROM tip_documento");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_tip"].'">'.$valores["tip_docu"].'</option>';
          }
        ?>

          </select>
          <button id="btncrear" type="button" class="btn btn-secondary" style="width: 20%" data-bs-toggle="modal"
            data-bs-target="#modalTipoDocumento">Añadir</button>
        </div>

        <div class="mb-3">
          <label for="int_telefono" class="form-label">Teléfono</label>
          <input type="number" class="form-control" name="telefono" placeholder="8095556666"  
            required="">
        </div>

      </div>
    </div>
    <button type="submit" value="Registrar" name="registrar"
      class="btn btn-primary w-100 text-uppercase fw-bold">Insertar</button>
  </form>
</div>




<!--FORMULARIO APARTE-->
<div id="modalTipoDocumento" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">TIPO DE DOCUMENTO</h5>
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

        <form action="insertar_tip_docu.php" method="POST">
          <div class="mb-3">
            <label for="codigo" class="col-form-label">Tipo de documento:</label>
            <input type="text" class="form-control" name="tip_docu" required=""
              placeholder="Agregue el tipo de documento">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="guardar" value="guardar">Guardar</button>
      </div>
      </form>
      <?php include("../includes/footer.php")?>