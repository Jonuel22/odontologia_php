<?php
include ("../conexion.php");
?>
<?php include("../includes/header.php")?>
<?php include("../includes/footer.php")?> 

<div class="container table-responsive container-sm mt-4 shadow-lg p-3 mb-5 bg-body rounded">
<center><h3>CONSULTA DE USUARIOS</h3></center>
<hr class="divider">
        <button id="btncrear" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser"  >Crear</button>
        <table id="tablausuarios" class="table aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
            <thead class="table-dark">
                <tr class="text-center">
                    <th scope="col">ID</th>
                    <th scope="col">Usuario</th>
                    <!-- <th scope="col">Password</th> -->
                    <th scope="col">Nivel</th>
                    <th scope="col">Estatus</th>
                    <th scope="col">Acciones</th>

                </tr>
            </thead>
            <tbody>
                    <?php 
                      //paginador
                      $sql_registe = mysqli_query($conexion, "SELECT COUNT(*) as total_registro From usuarios WHERE status = 1 ");
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

                      $query = "SELECT u.id_user, u.nom_user, u.pass_user, n.id_niv, n.nivel, et.id_status,et.des_status
                      from usuarios u INNER JOIN niveles as n ON u.nivel = n.id_niv  INNER JOIN
                      estados as et ON u.status = et.id_status where u.status = 1 ORDER BY u.id_user ASC LIMIT $desde, $por_pagina";
                      $result_task = mysqli_query($conexion, $query);

                        while($row = mysqli_fetch_array($result_task)){?>
                            <tr>
                                <td><?php echo $row['id_user'] ?></td>
                                <td><?php echo $row['nom_user'] ?></td>
                                <!-- <td><?php //echo $row['pass_user'] ?></td> -->
                                <td><?php echo $row['nivel'] ?></td>
                                <td><?php echo $row['des_status'] ?></td>
                                
                                <td>
                                  
                                    <a href="editaruser.php?id=<?php echo $row['id_user']?>" class="btn btn-secondary">
                                       
                                    <i class="fas fa-marker"></i>
                                    </a>

                                    <a href="eliminaruser.php?id=<?php echo $row['id_user']?>" class="btn btn-danger">
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
   
    


  <div id="modalUser" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">USUARIOS</h5>
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

        <form action="guardarnewuser.php" method="POST">
          <div class="mb-3">
            <label for="nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="username" placeholder="Carlos" required="">
          </div>
       
          <div class="mb-3">
            <label for="password" class="col-form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="********" required="">
          </div>

               <div class="mb-3">
            <label for="password" class="col-form-label">Confirmar Contraseña:</label>
            <input type="password" class="form-control" id="re_password" name="re_password" placeholder="********" required="">
          </div>

           <div class="select">
                    <select class="form-control" name="nivel" required="">
                        <option value="">Nivel de acceso</option>
                        <?php
                            $query = $conexion -> query ("SELECT * FROM niveles");
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option>'.$valores["nivel"].'</option>';
                            }
                        ?>

                    </select>
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

    
