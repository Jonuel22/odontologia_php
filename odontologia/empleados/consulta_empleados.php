<?php
include ("../conexion.php");
$sql=mysqli_query($conexion,"SELECT MAX(id_per) FROM persona");
$max=mysqli_fetch_array($sql);
?>
<?php include("../includes/header.php")?>
<?php include("../includes/footer.php")?>


<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../CSS/styl.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
  .buscador {
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    float: right;
    background: initial;
    padding: 10px;
    border-radius: 10px;
  }

  .buscador .btn_search {
    background: #1faac8;
    color: #fff;
    padding: 0 20px;
    border: 0;
    cursor: pointer;
    margin-left: 10px;
  }
</style>




<div class="container table-responsive container-sm mt-4 shadow-lg p-3 mb-5 bg-body rounded">
  <center>
    <h3>CONSULTA DE EMPLEADOS</h3>
  </center>
  <hr class="divider">


  <button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal"
    data-bs-target="#modalEmpleado">Crear</button>
  <form action="buscar_empleado.php" class="buscador" method="get">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar ID/Cargo">
    <input type="submit" value="Buscar" class="btn_search">
  </form>
  <table id="tablaempleados" class="table  aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
    <thead class="table-dark">
      <tr class="text-center">
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Fecha de Nacimiento</th>
        <th>Sexo</th>
        <th>Cargo</th>
        <th>Sueldo</th>
        <th>Estado</th>
        <th>Acciones</th>

      </tr>
    </thead>
    <tbody class="text-center">
      <?php 

                    //paginador
                    $sql_registe = mysqli_query($conexion, "SELECT COUNT(*) as total_registro From empleados WHERE status = 1 ");
                    $result_register = mysqli_fetch_array($sql_registe);
                    $total_registro = $result_register['total_registro'];

                    $por_pagina = 5;

                    if(empty($_GET['pagina'])){
                      $pagina = 1;
                    }
                    else{
                      $pagina = $_GET['pagina'];
                    }

                    $desde = ($pagina-1) * $por_pagina;
                    $total_paginas = ceil($total_registro/$por_pagina);

                        $query = "SELECT ep.id_empl, ps.nom_per, ps.fec_nac, ps.sex_per, 
                        a.apellido,c.cargo,c.sueldo, et.des_status FROM empleados AS ep
                        LEFT JOIN persona AS ps ON ep.id_per = ps.id_per LEFT JOIN apellidos AS a 
                        ON ps.ape_per = a.id_ape LEFT JOIN cargos AS c ON ep.cargo = c.id_cargo
                        LEFT JOIN estados AS et  ON ep.status = et.id_status ORDER BY ep.id_empl ASC LIMIT $desde, $por_pagina";


                        /*$query = "SELECT ep.id_empl, ps.nom_per, ps.fec_nac, ps.sex_per,a.apellido,c.cargo,c.sueldo, et.des_status
                        from empleados ep INNER JOIN persona ps ON ep.id_per = ps.id_per INNER JOIN apellidos a 
                        ON  ps.aper_per = a.id_ape INNER JOIN cargos c ON ep.cargo = c.id_cargo INNER JOIN
                        estados et ON ep.status = et.id_status";*/
                        $result_task = mysqli_query($conexion, $query);

                        while($row = mysqli_fetch_array($result_task)){?>
      <tr>
        <td>
          <?php echo $row['id_empl'] ?>
        </td>
        <td>
          <?php echo $row['nom_per'] ?>
        </td>
        <td>
          <?php echo $row['apellido'] ?>
        </td>
        <td>
          <?php echo $row['fec_nac'] ?>
        </td>
        <td>
          <?php echo $row['sex_per'] ?>
        </td>
        <td>
          <?php echo $row['cargo'] ?>
        </td>
        <td style="text-align: right;">
          <?php echo number_format($row['sueldo'],2) ?>
        </td>
        <td>
          <?php echo $row['des_status'] ?>
        </td>
        <td>

          <a href="editarempl.php?id=<?php echo $row['id_empl']?>" class="btn btn-secondary">
            <i class="fas fa-marker"></i>
          </a>

          <a href="eliminarempl.php?id=<?php echo $row['id_empl']?>" class="btn btn-danger">
            <i class="far fa-trash-alt"></i>
          </a>

        </td>
      </tr>


      <?php }?>
    </tbody>
  </table>
  <nav aria-label="Page navigation example">
    <ul class="pagination">
      <li class="page-item <?php echo $pagina <= 1 ? 'disabled' : '' ?>"><a class="page-link"
          href="?pagina=<?php echo $pagina-1;?>">Anterior</a></li>


      <?php              
          for($i=1; $i<=$total_paginas; $i++):?>
      <li class="page-item <?php echo $pagina == $i ? 'active' : '' ?>"><a class="page-link"
          href="?pagina=<?php echo $i;?>">
          <?php echo $i?>
        </a></li>
      <?php endfor ?>

      <li class="page-item <?php echo $pagina >= $total_paginas ? 'disabled' : '' ?>">
        <a class="page-link" href="?pagina=<?php echo $pagina+1;?>">Siguiente</a>
      </li>


    </ul>

  </nav>

</div>




<div id="modalEmpleado" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content ">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">EMPLEADO</h5>
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

        <form id="formulario" action="guardarempleado.php" method="POST">
          
        <div class="mb-3">
            <select class="form-control" name="id_per">
              <option value="">Seleccione una persona</option>
              <?php
          $query = $conexion -> query ("SELECT per.id_per as id,CONCAT(per.nom_per,' ',ape.apellido) as Persona from persona per INNER JOIN apellidos ape ON per.ape_per=ape.id_ape");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option value='.$valores["id"].'>'.$valores["Persona"].'</option>';
          }
        ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="cargo" class="form-label">Cargo a otorgar</label>

            <select class="form-control" name="cargo">
              <?php
          $query = $conexion -> query ("SELECT * FROM cargos");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option>'.$valores["cargo"].'</option>';
          }
        ?>

            </select>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="guardar" value="save">Guardar</button>
            </div>
        </form>
      </div>
    </div>


  </div>