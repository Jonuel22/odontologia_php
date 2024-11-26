<?php
include ("../conexion.php");
$sql=mysqli_query($conexion,"SELECT COUNT(id_dir) FROM direccion");
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
<center><h3>CONSULTA DE ENTIDADES</h3></center>
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
      //paginador
      $sql_registe = mysqli_query($conexion, "SELECT COUNT(*) as total_registro From entidad WHERE status = 1 ");
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

                        $query = "SELECT st.des_status Estado, en.id_ent, en.nom_ent, 
                        e.email, d.num_docu, t.tip_docu, tl.n_telf, dr.id_dir, CONCAT(c.nom_calle,', ',m.nom_mun) Direccion
                        FROM entidad AS en INNER JOIN datos AS dt 
                        ON en.datos = dt.id_datos INNER JOIN email AS e ON dt.email = e.id_email INNER JOIN documentos as d on 
                        dt.dat_docu = d.id_docu INNER JOIN  tip_documento as t on d.tip_docu = t.id_tip INNER JOIN telefono as tl on dt.dat_telf = tl.id_telf
                        INNER JOIN direccion as dr on dt.dat_dir = dr.id_dir INNER JOIN calles as c on dr.calle = c.id_calle INNER JOIN municipio as m
                        on c.id_mun = m.id_mun inner join estados st on en.status=st.id_status ORDER BY en.id_ent ASC LIMIT $desde, $por_pagina";


                       
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
          <?php echo $row['Direccion'] ?>
        </td>
        <td>
          <?php echo $row['Estado'] ?>
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
  <nav aria-label="Page navigation example">
          <ul class="pagination">
          <li class="page-item <?php echo $pagina <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="?pagina=<?php echo $pagina-1;?>">Anterior</a></li>


          <?php              
          for($i=1; $i<=$total_paginas; $i++):?>
            <li class="page-item <?php echo $pagina == $i ? 'active' : '' ?>"><a class="page-link" href="?pagina=<?php echo $i;?>"><?php echo $i?></a></li>
          <?php endfor ?>

          <li class="page-item <?php echo $pagina >= $total_paginas ? 'disabled' : '' ?>">
          <a class="page-link"  href="?pagina=<?php echo $pagina+1;?>">Siguiente</a></li>
            
                        
          </ul>

        </nav>

</div>




<div id="modalentidad" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
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

        <form action="guardarentidad.php" method="POST">
          <div class="mb-3">
            <label for="txt_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre" required="">
          </div>

          <div class="mb-3">
            <label for="txt_correo" class="form-label">Correo</label>
            <input type="text" class="form-control" id="txt_correo" name="correo" placeholder="Ingrese el correo"
              required="">
          </div>

          <div class="mb-3">
            <label for="int_telefono" class="form-label">Documento</label>
            <input type="text" class="form-control" id="documento" name="documento"
              placeholder="Ingrese el numero de documento" required="">
          </div>

        

  

          <div class="mb-3">

          <label for="txt_direccion" class="form-label">
            <a style="color:black" class="navbar-brand" for="txt_direccion"
              href="../direccion/direccion.php">Direccion</a>
          </label>

          <input type="number" class="form-control" name="direccion" placeholder="Ingrese la dirrecion" required=""
            min="1" <?php echo "max=" .$max[0];?>>
          </div>


          <div class="mb-3">
          <label for="doccument" class="form-label">Tipo de Documento</label>
          <select class="form-control" name="tip_docu">
            <?php
          $query = $conexion -> query ("SELECT * FROM tip_documento");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_tip"].'">'.$valores["tip_docu"].'</option>';
          }
          ?>

          </select>
          

          <div class="mb-3">
          <label for="int_telefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" name="telefono" placeholder="Ingrese el numero telefónico"
            required="">
          </div>



          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="guardar" value="save">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>