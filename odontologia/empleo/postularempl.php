<?php 
include ("../conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Clinica BS</title>
  <!-- Inclusión de Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Inclusión de tu archivo de estilo personalizado -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../CSS/styl.css">
</head>


<body>
  <nav class="navbar navbar-expand-lg  navbar-light mb-5" style="background-color: #e3f2fd;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../principal/bs.png" width="40" height="30" class="d-inline-block aling-top" alt="" loading="lazy">
        Clinica BS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <!-- Enlace a la página de ayuda con el ícono de pregunta -->
        <li class="nav-item">
          <a class="nav-link" href="#">Ayuda!!</a>
        </li>
      </ul>
    </div>
    </div>
  </nav>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <form id="formDatosPersonales" class="form-multipaso">
          <h2>Datos Personales</h2>
          <!-- Campos del formulario de datos personales -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="nombre" data-toggle="tooltip" data-placement="right" title="">Nombre: <span class="text-danger">*</span></label>
              <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingresa tu Nombre" required>
            </div>
            <div class="form-group col-md-6">
              <label for="apellido">Apellido: <span class="text-danger">*</span></label>
              <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ingresa tu Apellido" required>
            </div>
            <div class="form-group col-md-6">
              <label for="email">Email: <span class="text-danger">*</span></label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Ingresa tu Email" required>
            </div>

            <div class="form-group col-md-6">
              <label for="confirmarEmail">Confirmar Email: <span class="text-danger">*</span></label>
              <input type="email" id="confirmarEmail" name="confirmarEmail" class="form-control" placeholder="Confirma tu Email" required>
            </div>
          </div>

          <div class="form-row">

            <div class="form-group col-md-6">
              <label for="telefono" data-toggle="tooltip" data-placement="right" title="Telefono Personal">Teléfono: <span class="text-danger">*</span></label>
              <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ingresa tu Telefono" required>
            </div>

            <div class="form-group col-md-6">
              <label for="documento" class="form-label" data-toggle="tooltip" data-placement="right" title="Cedula">Documento de identidad: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="documento" name="documento" placeholder="xxx-xxxxxxx-x"  required="">
            </div>

            <div class="form-group col-md-6">
              <label for="direccion" class="form-label" data-toggle="tooltip" data-placement="right" title="Ciudad donde Reside">Ciudad: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Calle Y Ciudad" required="">
            </div>

            <div class="form-group col-md-6">
              <label for="fechaNacimiento">Fecha de Nacimiento: <span class="text-danger">*</span></label>
              <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="form-control" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmarCancelar">Cancelar</button>
            </div>
            <div class="form-group col-md-6 text-right">
              <button type="button" class="btn btn-primary" onclick="mostrarFormulario('formInfoAdicional')">Siguiente</button>
            </div>
          </div>
             
        </form>

        <!-- Resto del código del segundo formulario y scripts -->
        <form id="formInfoAdicional" class="form-multipaso" style="display:none;">
          <h2>Información Adicional</h2>
          <!-- Campos del formulario de información adicional -->
          <!-- Campo oculto para almacenar el ID de la persona -->
          <input type="hidden" id="persona_id" name="persona_id">
          <div class="form-row align-items-center">
            <div class="form-group col-md-6">
              <label for="NivelEst">Nivel de Estudio: <span class="text-danger">*</span></label>
              <select id="NivelEst" name="NivelEst" class="form-control" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="Secundario">Secundario</option>
                <option value="Tecnicatura">Tecnicatura</option>
                <option value="Universitario">Universitario</option>
                <option value="Posgrado">Posgrado</option>
                <option value="Master">Master</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="EstadoEst">Estado de Estudio: <span class="text-danger">*</span></label>
              <select id="EstadoEst" name="EstadoEst" class="form-control" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="En Curso">En Curso</option>
                <option value="Graduado">Graduado</option>
                <option value="Abandonado">Abandonado</option>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="EstadoEst">Area de Estudio: <span class="text-danger">*</span></label>
              <select id="EstadoEst" name="EstadoEst" class="form-control" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="En Curso">En Curso</option>
                <option value="Graduado">Graduado</option>
                <option value="Abandonado">Abandonado</option>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="NivelIng">Nivel de Ingles: <span class="text-danger">*</span></label>
              <select id="NivelIng" name="NivelIng" class="form-control" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="Avanzado">Ninguno</option>
                <option value="Avanzado">Basico</option>
                <option value="Avanzado">Avanzado</option>
                <option value="Intermedio">Intermedio</option>
                <option value="Nativo">Nativo</option>
              </select>
            </div>
          </div>

          <div class="form-row">
          

            <div class="form-group col-md-6">
              <label for="Niveloffc">Nivel de Office: <span class="text-danger">*</span></label>
              <select id="Niveloffc" name="Niveloffc" class="form-control" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="Avanzado">Ninguno</option>
                <option value="Avanzado">Basico</option>
                <option value="Avanzado">Avanzado</option>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="Niveloffc">Area de Trabajo: <span class="text-danger">*</span></label>
              <select id="Niveloffc" name="Niveloffc" class="form-control" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="Avanzado">Ninguno</option>
                <option value="Avanzado">Basico</option>
                <option value="Avanzado">Avanzado</option>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="puesto" data-toggle="tooltip" data-placement="bottom" title="Puesto en el que ha trabajado">Puesto/Titulo: <span class="text-danger">*</span></label>
              <input type="text" id="puesto" name="puesto" class="form-control" placeholder="Ingresa el Puesto" required>
            </div>
            <div class="form-group col-md-6">
              <label for="empresa" data-toggle="tooltip" data-placement="bottom" title="Lugar donde ha Trabajado">Empresa: <span class="text-danger">*</span></label>
              <input type="text" id="empresa" name="empresa" class="form-control" placeholder="Ingresa La Empresa" required>
            </div>

            <div class="form-group col-md-6">
              <label for="documento">Subir Documento:</label>
              <input type="file" id="documento" name="documento" class="form-control-file" accept=".pdf, .doc, .docx">
            </div>

          </div>

          <div class="form-group">
            <label data-toggle="tooltip" data-placement="bottom" title="Seleccionar Por lo menos una">Habilidades que posee: <span class="text-danger">*</span></label>
            <div class="form-row">
              <!-- Columnas y Datos -->
              <div class="col-md-6">
                <div class="form-check form-check-inline ">
                  <input class="form-check-input" type="checkbox" name="opciones[]" value="Opción 1">
                  <label class="form-check-label" style="white-space: nowrap;">Comunicacion</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="opciones[]" value="Opción 2">
                  <label class="form-check-label" style="white-space: nowrap;">Trabajo en equipo</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="opciones[]" value="Opción 3">
                  <label class="form-check-label" style="white-space: nowrap;">Liderazgo</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="opciones[]" value="Opción 4">
                  <label class="form-check-label" style="white-space: nowrap;">Orientación al cliente</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="opciones[]" value="Opción 4">
                  <label class="form-check-label" style="white-space: nowrap;">Adaptabilidad</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="opciones[]" value="Opción 4">
                  <label class="form-check-label" style="white-space: nowrap;">Resolución de problemas</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="comentario" data-toggle="tooltip" data-placement="bottom" title="Datos Adicionales que desee agregar">Descripcion Adicional:</label>
              <textarea id="comentario" name="comentario" class="form-control" rows="4"></textarea>
            </div>

            <div class="form-group col-md-6">
              <button type="button" class="btn btn-primary" onclick="mostrarFormulario('formDatosPersonales')">Anterior</button>
            </div>
            <div class="form-group col-md-6 text-right">
              <button type="button" class="btn btn-success" onclick="enviarFormularios()">Enviar</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Ventana modal de confirmación para cancelar -->
  <div class="modal fade" id="confirmarCancelar" tabindex="-1" role="dialog" aria-labelledby="confirmarCancelarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmarCancelarLabel">Confirmar Cancelación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ¿Estás seguro de que quieres cancelar?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-danger" onclick="cancelarFormulario()">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts y enlaces a bibliotecas -->
  <!-- Inclusión de jQuery y Popper.js -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <!-- Inclusión de Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <!-- Inclusión de tu archivo de script personalizado -->
  <script src="script.js"></script>

  <!-- Agrega los enlaces a Bootstrap Icons (BI) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">

  <script>
    // Esta función se ejecutará al cargar la página
    window.onload = function() {
      // Reiniciar todos los campos de ambos formularios
      document.getElementById("formDatosPersonales").reset();
      document.getElementById("formInfoAdicional").reset();
    };
  </script>


  <!-- Inicializa los tooltips -->
  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
  <!-- Script para los tooltips -->
  <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


  <script>
    function enviarFormularios() {
      // Validación del formulario 1 (Información personal)
      // ...

      // Envío de datos del formulario 1 al servidor y obtención del ID de la persona
      const formDataPersonal = new FormData(document.getElementById('formDatosPersonales'));

      fetch('procesar_info_personal.php', {
        method: 'POST',
        body: formDataPersonal,
      })
      .then(response => response.json())
      .then(data => {
        // Asignar el ID de la persona al campo oculto en el segundo formulario
        document.getElementById('persona_id').value = data.id;

        // Validación del formulario 2 (Detalles adicionales)
        // ...

        // Envío de datos del formulario 2 al servidor
        const formDataDetalles = new FormData(document.getElementById('formInfoAdicional'));

        fetch('procesar_info_adicional.php', {
          method: 'POST',
          body: formDataDetalles,
        });
      });
    }
  </script>

</body>
</html>
