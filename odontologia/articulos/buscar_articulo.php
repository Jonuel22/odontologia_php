<?php
include("../conexion.php");
?>
<?php include("../includes/header.php") ?>
<?php include("../includes/footer.php") ?>

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
  $busqueda = strtolower($_REQUEST['busqueda']);
  if (empty($busqueda)) {
    echo '<script type="text/javascript">
            window.location.href="consulta_articulos.php";
            </script>';
  }
  ?>
      <center><h3>BUSQUEDA DE ARTICULOS</h3></center>
<hr class="divider">


  <button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalarticulo">Crear</button>
  <form action="buscar_articulo.php" class="buscador" method="get">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar ID/Articulo">
    <input type="submit" value="Buscar" class="btn_search">
  </form>
  <table id="tablaentidad" class="table  aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
    <thead class="table-dark">
      <tr class="text-center">
        <th>ID</th>
        <th>Articulo</th>
        <th>Tipo</th>
        <th>Unidad</th>
        <th>Costo</th>
        <th>Precio</th>
        <th>Itbis</th>
        <th>Cantidad Existente</th>
        <th>Estado</th>
        <th>Acciones</th>

      </tr>
    </thead>
    <tbody class="text-center">
      <?php

      $query = "SELECT art.id_art ID, art.nombre  Articulo,t.tip_art Tipo,u.unidad Unidad,art.precom_art Costo,art.preven_art Precio,CONCAT((art.itbis_art*100),'%') ITBIS,iv.cant_exist Cant_exist,es.des_status Estado FROM articulos art INNER JOIN inventario iv ON iv.id_art=art.id_art INNER JOIN unidades u on u.id_unidad=art.unidad INNER join tip_articulo t on t.id_tip=art.tip_art inner join estados es on es.id_status=art.status WHERE (
                        art.id_art LIKE '%$busqueda%' OR
                        art.nombre LIKE '%$busqueda%' 
                        )";



      $result_task = mysqli_query($conexion, $query);

      while ($row = mysqli_fetch_array($result_task)) { ?>
        <tr>
          <td><?php echo $row['ID'] ?></td>
          <td><?php echo $row['Articulo'] ?></td>
          <td><?php echo $row['Tipo'] ?></td>
          <td><?php echo $row['Unidad'] ?></td>
          <td style="text-align: right;"><?php echo number_format($row['Costo'],2) ?></td>
          <td style="text-align: right;"><?php echo number_format($row['Precio'],2) ?></td>
          <td><?php echo $row['ITBIS'] ?></td>
          <td style="text-align: right;"><?php echo $row['Cant_exist'] ?></td>
          <td><?php echo $row['Estado'] ?></td>
          <td>

            <a href="editararticulo.php?id=<?php echo $row['ID'] ?>" class="btn btn-secondary">
              <i class="fas fa-marker"></i>
            </a>

            <a href="eliminarart.php?id=<?php echo $row['ID'] ?>" class="btn btn-danger">
              <i class="far fa-trash-alt"></i>
            </a>
          </td>
        </tr>


      <?php } ?>
    </tbody>
  </table>

</div>




<div id="modalarticulo" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">ARTICULOS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if (isset($_SESSION['message'])) { ?>
          <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php session_unset();
        } ?>

        <form action="insertar_articulo.php" method="POST">
          <input type="hidden" name="band_cons" value="1">
        <div class="row">
    <div class="col">
   <label for="nombre" class="form-label">Nombre</label>
<input type="text" class="form-control" name="nombre" placeholder="Nombre del articulo" required="">

      <div class="mb-3">
                <label for="des_art" class="form-label">Descripcion</label>
                <textarea name="des_art" class="form-control" id="des_art" cols="20" rows="5" placeholder="Descripcion Breve"></textarea>
                
            </div>
                 
            <div class="mb-3">
                <label for="preven" class="form-label">Precio Unitario</label>
                <input step="any" type="number" class="form-control" id="preven" name="preven" placeholder="Ingrese el precio" required="" min="1">
            </div>

            <div class="mb-3">
                <label for="precom" class="form-label">Costo Unitario</label>
                <input  step="any" type="number" class="form-control" id="precom" name="precom" required=""  placeholder="Ingrese el Costo" min="1">
            </div>

<div class="mb-3">
                <label for="itbis" class="form-label">ITBIS</label>
               <select name="itbis" id="itbis" class="form-control" required="">
                 <option value="">Seleccione el ITBIS</option>
                 <option value="0">0%</option>
                 <option value="0.16">16%</option>
                  <option value="0.18">18%</option>
               </select>
            </div>
    </div>

    <div class="col">

            <div class="mb-3">
          
                <label for="unidad" class="form-label">
                 UNIDAD</label>

               <select class="form-control" name="unidad" required="">
            <option value="">Seleccione La unidad de medida</option>

<?php $sql="SELECT * FROM unidades";
$query = $conexion -> query ($sql);

          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_unidad"].'">'.$valores["unidad"].'</option>';
          }
   ?>

          </select>
            </div>
            

            <div class="mb-3">
                <label for="tip_art" class="form-label">Tipo de Articulo</label>
                
      <select class="form-control" name="tip_art">
        <option value="">Seleccione el tipo</option>
        <?php
          $query = $conexion -> query ("SELECT * FROM tip_articulo");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_tip"].'">'.$valores["tip_art"].'</option>';
          }
        ?>
      
      </select>
            
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
