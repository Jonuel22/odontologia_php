<?php
include("../includes/header.php");
include ("../conexion.php");

$sql=mysqli_query($conexion,"SELECT COUNT(id_per) FROM persona");
$max=mysqli_fetch_array($sql);
 ?>


<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 50%">

  <h2 class="w-100 text-center mb-4">Registro de paciente</h2>

  <hr style="color: #9999" />


  <form action="insertar_paciente.php" method="POST">
    <div class="row">
      <div class="col">
        <div class="mb-3">

          <label for="txt_codigo" class="form-label">Persona</label>
          <select class="form-control" name="id_per" required="">
            <option value="">Seleccione una persona</option>
            <?php
          $query = mysqli_query($conexion,"SELECT per.id_per as id,CONCAT(per.nom_per,' ',ape.apellido) as Persona from persona per INNER JOIN apellidos ape ON per.ape_per=ape.id_ape WHERE NOT EXISTS (Select * from paciente pac where pac.id_per=per.id_per)");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option value='.$valores["id"].'>'.$valores["Persona"].'</option>';
          }
        ?>
          </select>
        </div>


        <div class="mb-3">
          <label for="txt_fecingreso" class="form-label">Fecha Ingreso</label>
          <input type="date" class="form-control" name="fec_ingreso" required>
        </div>

        <div class="mb-3">

<label for="txt_codigo" class="form-label">Alergias</label>
<textarea name="alergias" class="form-control" id="alergias" cols="30" rows="5"></textarea>

</div>

      </div>
      <div class="col">
        <div class="mb-3">
          <label for="txt_seguro" class="form-label" id="seguro">Seguro</label>
          <SELECT class="form-control" name="seguro">
            <OPTION>ARS HUMANO</OPTION>
            <OPTION>ARS UNIVERSAL</OPTION>

          </SELECT>
        </div>
        <div class="mb-3">

          <label for="txt_codigo" class="form-label">N. Contrato</label>
          <input type="number" class="form-control" name="num_contrato" placeholder="Numero de contrato" required=""
            min="0">

        </div>

        <div class="mb-3">

          <label for="padecimientos" class="form-label">Padecimientos</label>
          <textarea name="padecimientos" class="form-control" id="padecimientos" cols="30" rows="5"></textarea>

        </div>

       
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-30 text-uppercase fw-bold">Insertar</button>


  </form>

</div>


<?php include("../includes/footer.php")?>