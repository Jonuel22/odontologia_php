    <?php include("../includes/header.php")?>
<title>Servicios</title>
    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 60%">

        <h2 class="w-100 text-center mb-4">Registro de Servicios</h2>

        <hr style="color: #9999" />

<form action="agregar_servicio.php" method="POST">
          <div class="mb-3">
            <label for="servicio" class="col-form-label">Servicio:</label>
            <input type="text" class="form-control"  name="servicio" required="" placeholder="Agregue el servicio">
          </div>  
          <div class="mb-3">
            <label for="costo" class="col-form-label">Costo:</label>
            <input type="number" class="form-control" min="1" name="costo" required="">
          </div> 
          <input class="btn btn-primary w-100 text-uppercase fw-bold" type="submit" value="Registrar Servicio" name="btn_registrar" class="btn"> 
      </div>
    </form>

 

    </div>
<?php include("../includes/footer.php")?> 