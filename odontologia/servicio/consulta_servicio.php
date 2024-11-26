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
        .buscador{
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

.buscador .btn_search{
    background: #1faac8 ;
    color: #fff;
    padding: 0 20px;
    border: 0;
    cursor: pointer;
    margin-left: 10px;
}

td{
text-align: right;

}
    </style>


  

<div class="container table-responsive container-sm mt-4 shadow-lg p-3 mb-5 bg-body rounded">
<center><h3>CONSULTA DE SERVICIOS</h3></center>
<hr class="divider">
   

        <button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalservicio" >Crear</button>
        <form action="buscar_servicio.php" class="buscador" method="get" >
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar ID/Servicio">
                <input type="submit" value="Buscar" class="btn_search">
        </form>
        <table id="tablaservicios" class="table aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Costo</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody class="text-center">
                    <?php 
                     //paginador
                     $sql_registe = mysqli_query($conexion, "SELECT COUNT(*) as total_registro From servicios WHERE status = 1 ");
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

                        $query = "SELECT s.id_ser, s.nom_ser, s.cost_ser, et.des_status FROM servicios AS s
                        LEFT JOIN estados AS et  ON s.status = et.id_status ORDER BY s.id_ser ASC LIMIT $desde, $por_pagina";


                       
                        $result_task = mysqli_query($conexion, $query);

                        while($row = mysqli_fetch_array($result_task)){?>
                            <tr>
                                <td><?php echo $row['id_ser'] ?></td>
                                <td><?php echo $row['nom_ser'] ?></td>
                                <td><?php echo $row['cost_ser'] ?></td>
                                <td><?php echo $row['des_status'] ?></td>
                                <td>
                                  
                                    <a href="editarser.php?id=<?php echo $row['id_ser']?>" class="btn btn-secondary">
                                        <i class="fas fa-marker"></i>
                                    </a>

                                    <a href="eliminarser.php?id=<?php echo $row['id_ser']?>" class="btn btn-danger">
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
   
    


  <div id="modalservicio" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">SERVICIOS</h5>
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

        <form action="guardarser.php" method="POST">
          <div class="mb-3">
            <label for="servicio" class="col-form-label">Servicio:</label>
            <input type="text" class="form-control" id="servicio" name="servicio" required="" placeholder="Servicio">
          </div>
        
          <div class="mb-3">
            <label for="costo" class="col-form-label">Costo Del Servicio:</label>
            <input type="number" class="form-control" min="1" id="costo" name="costo" required="" placeholder="15000">
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

