<?php include("../includes/header.php")?>
<title>Direcciones</title>
    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 60%">

        <h2 class="w-100 text-center mb-4">Registro de Direcciones</h2>

        <hr style="color: #9999" />

        <form action="insertar_direccion.php" id="formulario" method="POST" >
  <div class="row">
    <div class="col">
      <div class="mb-3">
                <label for="txt_nombre" class="form-label">Pais</label>
                <input type="text" class="form-control"  name="pais" placeholder="Ingrese el pais" required="">
            </div>
             <label for="txt_nombre" class="form-label">Provincia</label>
      <input type="text" class="form-control"  name="provincia" placeholder="Ingrese la provincia" required="">
            <div class="mb-3">
                <label for="txt_correo" class="form-label">Municipio</label>
                <input type="text" class="form-control"  name="municipio" placeholder="Ingrese el municipio" required="">
            </div>

            <div class="mb-3">
                <label for="int_telefono" class="form-label">Calle</label>
                <input type="text" class="form-control" name="calle" placeholder="Ingrese el nombre de la calle" required="">
            </div>
    </div>
  </div>

       <input class="btn btn-primary w-100 text-uppercase fw-bold" type="submit" value="Registrar Direccion" name="btn_registrar" class="btn">
     

        </form>

    </div>
<?php include("../includes/footer.php")?> 