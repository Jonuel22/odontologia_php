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
  body{
    background-color: #f7f6f6;
           
          


        }
        table thead{
            background-color: #0a4f70;
            color: white;
        }

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


    </style>


<div class="container table-responsive container-sm mt-4 shadow-lg p-3 mb-5 bg-body rounded">
    <?php
        $busqueda = strtolower( $_REQUEST['busqueda']);
        if(empty($busqueda)){
            echo'<script type="text/javascript">
            window.location.href="consulta_direccion.php";
            </script>';
        }
    ?>
<center><h3>BUSQUEDA DE DIRECIION</h3></center>
<hr class="divider">
<button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modaldireccion" >Crear</button>
        <form action="buscar_direccion.php" class="buscador" method="get" >
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar ID/Calle/Municipio">
                <input type="submit" value="Buscar" class="btn_search">
        </form>
        <table id="tabladirecciones" class="table aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
        <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Calle</th>
                    <th>Municipio</th>
                    <th>Provincia</th>
                    <th>Pais</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody class="text-center">
                    <?php 
                    /*//paginador
                    $sql_registe = mysqli_query($conexion, "SELECT COUNT(*) as total_registro From direccion WHERE status = 1 ");
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
                     $total_paginas = ceil($total_registro/$por_pagina);*/

 


                        $query = "SELECT d.id_dir, c.nom_calle, m.nom_mun, p.nom_prov, pa.nom_pais FROM direccion AS d
                        INNER JOIN calles AS c  ON d.calle = c.id_calle INNER JOIN municipio AS m ON c.id_mun= m.id_mun
                        INNER JOIN provincia AS p ON d.provincia = p.id_prov INNER JOIN pais AS pa ON p.pais = pa.id_pais INNER JOIN
                        estados et ON d.status = et.id_status WHERE (
                          d.id_dir LIKE '%$busqueda%' OR
                          c.nom_calle LIKE '%$busqueda%' OR m.nom_mun LIKE '%$busqueda%'
                        ) AND id_status=1";


                       
                        $result_task = mysqli_query($conexion, $query);

                        while($row = mysqli_fetch_array($result_task)){?>
                            <tr>
                                <td><?php echo $row['id_dir'] ?></td>
                                <td><?php echo $row['nom_calle'] ?></td>
                                <td><?php echo $row['nom_mun'] ?></td>
                                <td><?php echo $row['nom_prov'] ?></td>
                                <td><?php echo $row['nom_pais'] ?></td>
                                <td>
                                  
                                    <a href="editardir.php?id=<?php echo $row['id_dir']?>" class="btn btn-secondary">
                                       <i class="fas fa-marker"></i>
                                    </a>

                                    <a href="eliminardir.php?id=<?php echo $row['id_dir']?>" class="btn btn-danger">
                                       <i class="far fa-trash-alt"></i>
                                    </a>

                                </td>
                            </tr>
                    
                        
                        <?php }?>
            </tbody>
        </table>
        <!--<nav aria-label="Page navigation example">
          <ul class="pagination">
          <li class="page-item <?php echo $pagina <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="?pagina=<?php echo $pagina-1;?>">Anterior</a></li>


          <?php              
          for($i=1; $i<=$total_paginas; $i++):?>
            <li class="page-item <?php echo $pagina == $i ? 'active' : '' ?>"><a class="page-link" href="?pagina=<?php echo $i;?>"><?php echo $i?></a></li>
          <?php endfor ?>

          <li class="page-item <?php echo $pagina >= $total_paginas ? 'disabled' : '' ?>">
          <a class="page-link"  href="?pagina=<?php echo $pagina+1;?>">Siguiente</a></li>
            
                        
          </ul>

        </nav>-->

    </div>
   
    


  <div id="modaldireccion" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Dirreccion</h5>
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

        <form action="guardardir.php" method="POST">
          <div class="mb-3">
            <label for="pais" class="col-form-label">Pais:</label>
            <input type="text" class="form-control" id="pais" name="pais" required="" placeholder="Pais">
          </div>
        
          <label for="txt_nombre" class="form-label">Provincia</label>
      <input type="text" class="form-control"  name="provincia" placeholder="Ingrese la provincia" required="">
            <div class="mb-3">
                <label for="txt_correo" class="form-label">Municipio</label>
                <input type="text" class="form-control"  name="municipio" placeholder="Ingrese el municipio" required="">
            </div>

          <div class="mb-3">
            <label for="calle" class="col-form-label">Calle:</label>
            <input type="text" class="form-control" id="calle" name="calle" required="" placeholder="Calle">
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

