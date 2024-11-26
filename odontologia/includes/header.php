<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/73f8e85efa.js" crossorigin="anonymous"></script>
  <title>Clinica BS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="../CSS/styl.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg  navbar-light mb-5" style="background-color: #e3f2fd;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../principal/bs.png" width="40" height="30" class="d-inline-block aling-top" alt="" loading="lazy">
        Sistema Odontologico</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../principal/menu.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Mantenimientos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="../persona/persona.php">Personas</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../entidades/entidad.php">Entidades</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../empleados/empleados.php">Empleados</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../pacientes/pacientes.php">Pacientes</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../articulos/articulos.php">Articulos</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../clientes/clientes.php">Clientes</a></li>
              <li>
                <hr class="dropdown-divider">
                
                 <li><a class="dropdown-item" href="../suplidores/suplidores.php">Suplidores</a></li>
                 <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../usuarios/register.php">Usuarios</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
             
              <li><a class="dropdown-item" href="../direccion/direccion.php">Direcciones</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
             
              <li><a class="dropdown-item" href="../servicio/servicio.php">Servicios</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Procesos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../Facturacion/elige_paciente.php">Facturacion</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../Odontograma/view">Odontograma</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../compras/index.php">Compras</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../ventas">Ventas</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../CXP/cxp.php">CuentasXPagar</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../CXC/cxc.php">CuentasXCobrar</a></li>
            </ul>

          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Citas
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../eventos">Nueva Cita</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Reportes
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../Facturacion/reportecaja.php">Cierre de Caja</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../empleados/reporteempleado.php">Empleados</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../articulos/reportearticulos.php">Articulos</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../compras/reportecompras.php">Compras</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../servicio/reporteservicio.php">Servicios</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Consultas
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../empleados/consulta_empleados.php">Empleados</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../empleo/consulta_candidatos.php">Candidatos</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../pacientes/consulta_pacientes.php">Pacientes</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../direccion/consulta_direccion.php">Direcciones</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../usuarios/consultauser.php">Usuarios</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../persona/consulta_persona.php">Personas</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../clientes/consulta_clientes.php">Clientes</a></li>
              <li>
              	<hr class="dropdown-divider">
                 <li><a class="dropdown-item" href="../suplidores/consulta_suplidores.php">Suplidores</a></li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../servicio/consulta_servicio.php">Servicios</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../articulos/consulta_articulos.php">Articulos</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../entidades/consulta_entidades.php">Entidades</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled">Clinica BS</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Bienvenido! <?php echo $_SESSION['cliente'];?> </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="../bienvenida/cerrar_session.php">Cerrar Sesion</a></li>
              </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../includes/Ejecutar_procedure_reponer.php">Reponer Artículos</a>
          </li>
          
        </ul>
      </div>
    </div>
  </nav>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>