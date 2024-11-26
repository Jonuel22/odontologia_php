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


    </style>



<div class="container table-responsive container-sm mt-4 shadow-lg p-3 mb-5 bg-body rounded">
    <?php
        $busqueda = strtolower( $_REQUEST['busqueda']);
        if(empty($busqueda)){
            echo'<script type="text/javascript">
            window.location.href="consulta_suplidores.php";
            </script>';
        }
    ?>
<center><h3 >CONSULTA DE SUPLIDORES</h3></center>
<hr class="divider">
        <button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalsuplidor" >Crear</button>
        <form action="buscar_suplidores.php" class="buscador" method="get" >
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
                <input type="submit" value="Buscar" class="btn_search">
        </form>
                <table id="tablasup" class="table mt-2 table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th>suplidor</th>
                    <th>Correo</th>
                    <th>Direccion</th>
                 <th>N. Documento</th>   
                    <th>Estado</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody>
                    <?php 

                        $query = "
SELECT d.num_docu Documento,st.des_status Estado, ent.id_ent,d.num_docu, t.tip_docu, tl.n_telf, dr.id_dir, CONCAT(c.nom_calle,', ', m.nom_mun) Direccion
                         ,sup.id_sup ID,ent.nom_ent Nombre,email.email Email FROM suplidores sup inner join entidad ent on sup.id_ent=ent.id_ent LEFT JOIN datos AS dt 
                        ON ent.datos = dt.id_datos LEFT JOIN email AS e ON dt.email = e.id_email LEFT JOIN documentos as d on 
                        dt.dat_docu = d.id_docu LEFT JOIN  tip_documento as t on d.tip_docu = t.id_tip LEFT JOIN telefono as tl on dt.dat_telf = tl.id_telf
                        LEFT JOIN direccion as dr on dt.dat_dir = dr.id_dir LEFT JOIN calles as c on dr.calle = c.id_calle LEFT JOIN municipio as m
                        on c.id_mun = m.id_mun inner join estados st on sup.status=st.id_status inner join email on dt.email=email.id_email WHERE sup.id_sup LIKE '%$busqueda%' OR ent.nom_ent LIKE '%$busqueda%'";


                        $result_task = mysqli_query($conexion, $query);

                        while($row = mysqli_fetch_array($result_task)){?>
                            <tr>
                                <td><?php echo $row['ID'] ?></td>
                                <td><?php echo $row['Nombre'] ?></td>
                                <td><?php echo $row['Email'] ?></td>
                                <td><?php echo $row['Direccion'] ?></td>
                                <td><?php echo $row['Documento'] ?></td>
                                <td><?php echo $row['Estado'] ?></td>
                                <td>
                                  
                                    <a href="editarsup.php?id=<?php echo $row['ID']?>" class="btn btn-secondary">
                                        <i class="fas fa-marker"></i>
                                    </a>

                                    <a href="eliminasup.php?id=<?php echo $row['ID']?>" class="btn btn-danger">
                                        <i class="far fa-trash-alt"></i>
                                    </a>

                                </td>
                            </tr>
                    
                        
                        <?php }?>
            </tbody>
        </table>

    </div>
   
    

  <div id="modalsuplidor" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">suplidor</h5>
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


        <form name="form_cons" method="POST" action="insertar_suplidor.php">
<input type="hidden" name="band_cons" value="1">
            <div class="mb-3">
               <select class="form-control" name="id_ent">
            <option value="">Seleccione una entidad</option>
            <?php
          $query = mysqli_query($conexion,"SELECT id_ent as id,nom_ent as entidad from entidad");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option value='.$valores["id"].'>'.$valores["entidad"].'</option>';
          }
        ?>
          </select>

            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="guardar" value="save">Guardar</button>
          </div>
        </form>
    </div>
  </div>

  
</div>

