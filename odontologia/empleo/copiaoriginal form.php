<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 60%">

  <h2 class="w-100 text-center mb-4">Solicitud de Empleo</h2>

  <hr style="color: #9999" />

  <form id="formulario" method="POST" action="publicar_empl.php" enctype="multipart/form-data" onsubmit="return confirmarEnvio()">
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
        <div class="mb-3">
          <label for="email" class="form-label">Correo Electronico</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Ejemplo@gmail.com" required="">
        </div>

        <div class="mb-3">
          <label for="int_telefono" class="form-label">Numero de Contacto</label>
          <input type="number" class="form-control" name="telefono" placeholder="8095556666"  
            required="">
        </div>

        <label for="educacion" class="form-label">Nivel de educacion</label>
        <textarea name="educacion" class="form-control" id="educacion" cols="30" rows="5" placeholder="Basica - Secundaria -Universitaria" required=""></textarea>

        <div class="mb-3">
          <label for="documento" class="form-label">Documento de identidad</label>
          <input type="text" class="form-control" id="documento" name="documento"
            placeholder="xxx-xxxxxxx-x" required="">
        </div>

      </div>

      <div class="col">

        <label for="experiencia" class="form-label">Experiencia Laboral</label>
        <textarea name="experiencia" class="form-control" id="experiencia" cols="30" rows="5" placeholder="Puesto que ha desempeñado, En caso de no tener, Poner Ninguno" required=""></textarea>

        <label for="habilidades" class="form-label">Habilidades O Cualidades</label>
        <textarea name="habilidades" class="form-control" id="habilidades" cols="30" rows="5" placeholder="Ejemplo: Buena Comunicacion,etc" required=""></textarea>

        <div class="mb-3">

          <label for="direccion" class="form-label">Ciudad</label>

          <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Calle, Ciudad" required="">
         
        </div>

        <!--Puestos A Evaluar-->
        <label for="doccument" class="form-label">Area a Desempeña
        <i class="bi bi-info-circle text-primary" role="button" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Información adicional sobre el puesto"></i>
        </label>
        <div class="mb-3 input-group">

          <select class="form-control" name="puesto_disponible">
            <!-- Opción vacía -->
            <option value="0">Seleccionar..</option>
            <?php
              $query = "SELECT puesto_id, nombre_puesto FROM puestos_disponibles";
              $result = $conexion->query($query);
              while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['puesto_id'] . "'>" . $row['nombre_puesto'] . "</option>";
              }

            ?>

          </select>
          
        </div>

        <label for="archivo">Documentos de Soporte</label>
        <input type="file" name="archivo" >

      </div>
    </div>
    <button type="submit" value="Registrar" name="registrar" class="btn btn-primary w-100 text-uppercase fw-bold">Insertar</button>
  </form>
  <script>
    function confirmarEnvio() {
      // Mostrar una ventana de confirmación al usuario
      return confirm("¿Estás seguro de que deseas enviar el formulario?");
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializar popover de Bootstrap
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</div>