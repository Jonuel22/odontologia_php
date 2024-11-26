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
<?php
        $busqueda = strtolower( $_REQUEST['busqueda']);
        if(empty($busqueda)){
            echo'<script type="text/javascript">
            window.location.href="consulta_entidades.php";
            </script>';
        }
    ?>
        <center><h3>BUSQUEDA DE ENTIDADES</h3></center>
<hr class="divider">


  <button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal"
    data-bs-target="#modalentidad">Crear</button>
  <form action="buscar_entidad.php" class="buscador" method="get">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar ID/Entidad">
    <input type="submit" value="Buscar" class="btn_search">
  </form>
  <table id="tablaentidad" class="table aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
    <thead class="table-dark">
      <tr class="text-center">
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Documento</th>
        <th>Tipo de documento</th>
        <th>Telefono</th>
        <th>Direccion</th>
        <th>Estado</th>
        <th>Acciones</th>

      </tr>
    </thead>
    <tbody class="text-center">
      <?php 

                        $query = "SELECT en.id_ent, en.nom_ent, 
                        e.email, d.num_docu, t.tip_docu, tl.n_telf, dr.id_dir, c.nom_calle, m.nom_mun
                        FROM entidad AS en INNER JOIN datos AS dt 
                        ON en.datos = dt.id_datos INNER JOIN email AS e ON dt.email = e.id_email INNER JOIN documentos as d on 
                        dt.dat_docu = d.id_docu INNER JOIN  tip_documento as t on d.tip_docu = t.id_tip INNER JOIN telefono as tl on dt.dat_telf = tl.id_telf
                        INNER JOIN direccion as dr on dt.dat_dir = dr.id_dir INNER JOIN calles as c on dr.calle = c.id_calle INNER JOIN municipio as m
                        on c.id_mun = m.id_mun WHERE (
                        en.id_ent LIKE '%$busqueda%' OR
                        en.nom_ent LIKE '%$busqueda%' 
                        )";


                       
                        $result_task = mysqli_query($conexion, $query);

                        while($row = mysqli_fetch_array($result_task)){?>
      <tr>
        <td>
          <?php echo $row['id_ent'] ?>
        </td>
        <td>
          <?php echo $row['nom_ent'] ?>
        </td>
        <td>
          <?php echo $row['email'] ?>
        </td>
        <td>
          <?php echo $row['num_docu'] ?>
        </td>
        <td>
          <?php echo $row['tip_docu'] ?>
        </td>
        <td>
          <?php echo $row['n_telf'] ?>
        </td>
        <td>
          <?php echo $row['nom_calle'] ?>
        </td>
        <td>
          <?php echo $row['nom_mun'] ?>
        </td>

        <td>

          <a href="editarentidad.php?id=<?php echo $row['id_ent']?>" class="btn btn-secondary">
            <i class="fas fa-marker"></i>
          </a>

          <a href="eliminarentidad.php?id=<?php echo $row['id_ent']?>" class="btn btn-danger">
            <i class="far fa-trash-alt"></i>
          </a>

        </td>
      </tr>


      <?php }?>
    </tbody>
  </table>

</div>




<div id="modalentidad" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Entidades</h5>
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

        <form action="guardarempl.php" method="POST">
          <div class="mb-3">
            <label for="codigo" class="col-form-label">Codigo:</label>
            <input type="number" class="form-control" id="codigo" name="codigo" required="" placeholder="215">
          </div>
          <div class="mb-3">
            <label for="nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required="" placeholder="Jose">
          </div>
          <div class="mb-3">
            <label for="apellido" class="col-form-label">Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required="" placeholder="Rodriguez">
          </div>
          <div class="mb-3">
            <label for="telefono" class="col-form-label">Telefono:</label>
            <input type="number" class="form-control" id="telefono" name="telefono" required=""
              placeholder="8099253021">
          </div>
          <div class="mb-3">
            <label for="sueldo" class="col-form-label">Sueldo:</label>
            <input type="number" class="form-control" id="sueldo" name="sueldo" required="" placeholder="15000">
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