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
            window.location.href="consulta_persona.php";
            </script>';
        }
    ?>
    <center><h3>BUSQUEDA DE PERSONAS</h3></center>
<hr class="divider">

        <button id="crear" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalpersona" >Crear</button>
        <form action="buscar_persona.php" class="buscador" method="get" >
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
                <input type="submit" value="Buscar" class="btn_search">
        </form>
        <table id="tablapersona" class="table table-sm aling-middle table-hover table-secundary mt-2 table-bordered table-striped">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Sexo</th>
                    <th>E-Mail</th>
                    <th>Documento</th>
                    <th>Tipo de Documento</th>
                    <th>Telefono</th>
                    <th>Direccion</th>
                    <th>municipio</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody class="text-center">
                    <?php 


                        //paginador
                        /*$sql_registe = mysqli_query($conexion, "SELECT COUNT(*) AS total_registro FROM empleados
                        WHERE 
                        (
                        id_empl LIKE '%$busqueda%'
                        $cargo 
                        )
                        AND
                        status=1;");

                        $result_register = mysqli_fetch_array($sql_registe);
                        $total_registro = $result_register['total_registro'];*/

                        

                        $query = "SELECT p.id_per, p.nom_per, p.fec_nac, p.sex_per, 
                        a.apellido,e.email, d.num_docu, t.tip_docu, tl.n_telf, dr.id_dir, c.nom_calle, m.nom_mun
                        FROM persona AS p
                        LEFT JOIN apellidos AS a ON p.ape_per = a.id_ape LEFT JOIN datos AS dt 
                        ON p.datos = dt.id_datos LEFT JOIN email AS e ON dt.email = e.id_email LEFT JOIN documentos as d on 
                        dt.dat_docu = d.id_docu LEFT JOIN  tip_documento as t on d.tip_docu = t.id_tip LEFT JOIN telefono as tl on dt.dat_telf = tl.id_telf
                        LEFT JOIN direccion as dr on dt.dat_dir = dr.id_dir LEFT JOIN calles as c on dr.calle = c.id_calle LEFT JOIN municipio as m
                        on c.id_mun = m.id_mun WHERE (
                        p.id_per LIKE '%$busqueda%' OR
                        p.nom_per LIKE '%$busqueda%' 
                        )";
                        $result_task = mysqli_query($conexion, $query);

                        while($row = mysqli_fetch_array($result_task)){?>
                            <tr>
                                <td><?php echo $row['id_per'] ?></td>
                                <td><?php echo $row['nom_per'] ?></td>
                                <td><?php echo $row['apellido'] ?></td>
                                <td><?php echo $row['fec_nac'] ?></td>
                                <td><?php echo $row['sex_per'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['num_docu'] ?></td>
                                <td><?php echo $row['tip_docu'] ?></td>
                                <td><?php echo $row['n_telf'] ?></td>
                                <td><?php echo $row['nom_calle'] ?></td>
                                <td><?php echo $row['nom_mun'] ?></td>
                                <td>
                                  
                                    <a href="editarpersona.php?id=<?php echo $row['id_per']?>" class="btn btn-secondary">
                                        <i class="fas fa-marker"></i>
                                    </a>

                                    <a href="eliminarpersona.php?id=<?php echo $row['id_per']?>" class="btn btn-danger">
                                        <i class="far fa-trash-alt"></i>
                                    </a>

                                </td>
                            </tr>
                    
                        
                        <?php }?>
            </tbody>
        </table>

    </div>
   
    


  <div id="modalpersona" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">PERSONA</h5>
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

           
        <form action="guardarpersona.php" method="POST">
          <div class="row">

          <div class="col">
            <div class="mb-3">
              <label for="txt_nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre">
            </div>
            <div class="mb-3">
              <label for="txt_nombre" class="form-label">Apellido</label>
              <input type="text" class="form-control" name="apellido" placeholder="Ingrese el apellido">
            </div>
            <label for="txt_nombre" class="form-label">Sexo</label>
            <select class="form-control" name="sexo">
              <option>M</option>
              <option>F</option>
            </select>

            <div class="mb-3">
              <label for="txt_correo" class="form-label">Correo</label>
              <input type="email" class="form-control" id="txt_correo" name="correo" placeholder="Ejemplo@gmail.com"
                required="">
            </div>

            <div class="mb-3">
              <label for="documento" class="form-label">Documento</label>
              <input type="text" class="form-control" id="documento" name="documento"
                 placeholder="402-9999999-6" required="">
            </div>

          </div>
          <div class="col">

            <div class="mb-3">
              <label for="txt_nombre" class="form-label">Fecha de nacimiento</label>
              <input type="date" class="form-control" name="fec_nac" required="">
            </div>
            <div class="mb-3">

              <label for="txt_direccion" class="form-label">
                <a style="color:black" class="navbar-brand" for="txt_direccion"
                  href="../direccion/direccion.php">Direccion</a>
              </label>

              <select class="form-control" name="direccion" >
            
<?php $sql="SELECT dr.id_dir as ID,CONCAT(pa.nom_pais,': ',p.nom_prov,', ',m.nom_mun,', ',c.nom_calle) Direccion FROM direccion dr LEFT JOIN calles as c on dr.calle = c.id_calle LEFT JOIN municipio as m on c.id_mun = m.id_mun inner join provincia p on m.id_prov= p.id_prov inner join pais pa on p.pais=pa.id_pais";
$query = $conexion -> query ($sql);

          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["ID"].'">'.$valores["Direccion"].'</option>';
          }
   ?>

          </select>
         
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
              
            </div>

            <div class="mb-3">
              <label for="int_telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="telefono" placeholder="8095556666"
                required="">
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