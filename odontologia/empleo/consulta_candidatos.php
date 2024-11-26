<?php
include ("../conexion.php");
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
    <h3>CONSULTA DE CANDIDATOS</h3>
  </center>
  <hr class="divider">

  <button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal"
    data-bs-target="#modalpaciente">Mejores Candidatos</button>
  <form action="buscar_paciente.php" class="buscador" method="get">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar Candidato">
    <input type="submit" value="Buscar" class="btn_search">
  </form>
  <table id="tablapaciente" class="table aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
    <thead class="table-dark">
      <tr class="text-center">
        <th>Registro No</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>Telefono</th>
        <th>Documento de Identidad</th>
        <th>Fecha de Nacimiento</th>
        <th>Direccion</th>
        <th>Area de Estudio</th>
        <th>Documento CV</th>
        <th>Acciones</th>

      </tr>
    </thead>
    <tbody class="text-center">
      <?php 

        //paginador
                    $sql_registe = mysqli_query($conexion, "SELECT COUNT(*) as total_registro From paciente WHERE status = 1 ");
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

                        //$query = "SELECT pc.id_pac,pc.fec_ingr, e.nom_ent, ps.nom_per,ps.sex_per,a.apellido,s.num_contrato, 
                        //et.des_status, pc.padec_pac, pc.alerg_pac
                        //FROM paciente AS pc
                        //LEFT JOIN seguro AS s ON pc.seg_pac = s.id_seg LEFT JOIN persona AS ps ON pc.id_per = ps.id_per LEFT JOIN
                        //apellidos AS a ON ps.ape_per = a.id_ape LEFT JOIN entidad AS e ON s.nom_seg = e.id_ent
                        //LEFT JOIN estados AS et  ON pc.status = et.id_status ORDER BY pc.id_pac ASC LIMIT $desde, $por_pagina";


                        //$result_task = mysqli_query($conexion, $query);

        //while($row = mysqli_fetch_array($result_task))
        {?>
      
        
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




<div id="modalpaciente" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">PACIENTE</h5>
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

        <form action="guardarpac.php" method="POST">
          <div class="row">
            <div class="col">
              <div class="mb-3">

                <label for="txt_codigo" class="form-label">Persona</label>
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
                <label for="txt_fecingreso" class="form-label">Fecha Ingreso</label>
                <input type="date" class="form-control" id="fec_ingreso" name="fec_ingreso" required>
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
                <input type="number" class="form-control" name="num_contrato" placeholder="Numero de contrato" required
                  min="0">

              </div>

              <div class="mb-3">

                <label for="padecimientos" class="form-label">Padecimientos</label>
                <textarea name="padecimientos" class="form-control" id="padecimientos" cols="30" rows="5"></textarea>

              </div>
            </div>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="guardar" value="save">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>