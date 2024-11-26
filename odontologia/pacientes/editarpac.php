<?php
    include('../conexion.php');

//    $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];



        $query ="SELECT pc.id_pac,pc.fec_ingr, en.nom_ent, ps.nom_per,ps.sex_per,a.apellido,s.num_contrato, 
        et.des_status, pc.padec_pac, pc.alerg_pac, e.email, n_telf, nom_calle, nom_mun, nom_prov
        FROM paciente AS pc
        LEFT JOIN seguro AS s ON pc.seg_pac = s.id_seg LEFT JOIN persona AS ps ON pc.id_per = ps.id_per LEFT JOIN
        apellidos AS a ON ps.ape_per = a.id_ape LEFT JOIN datos AS dt 
        ON ps.datos = dt.id_datos LEFT JOIN email AS e ON dt.email = e.id_email LEFT JOIN entidad AS en ON s.nom_seg = en.id_ent
        LEFT JOIN estados AS et  ON pc.status = et.id_status LEFT JOIN telefono as tl on dt.dat_telf = tl.id_telf
        LEFT JOIN direccion as dr on dt.dat_dir = dr.id_dir LEFT JOIN calles as c on dr.calle = c.id_calle LEFT JOIN municipio as m
        on c.id_mun = m.id_mun LEFT JOIN provincia as pr on m.id_prov = pr.id_prov
        WHERE pc.id_pac=$id  ";

        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
                $nombre = $row['nom_per'];
                $apellido = $row['apellido'];
                $sexo = $row['sex_per'];
                $email = $row['email'];
                $fecha_ingreso = $row['fec_ingr'];
                $alergia = $row['alerg_pac'];
                $padecimientos = $row['padec_pac'];
                $telefono = $row['n_telf'];
                $calle = $row['nom_calle'];
                $munip = $row['nom_mun'];
                $provin = $row['nom_prov'];
               

               

            }        
        }
    }

    if(isset($_POST['update'])){
        $id = $_GET['id'];
        //$codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $fecha = $_POST['fecha'];
        $calle = $_POST['calle'];
        $munip = $_POST['munip'];
        $provin = $_POST['provin'];
        $alergia = $_POST['alergias'];
        $padecimientos = $_POST['padecimientos'];
        

        $query = "UPDATE paciente AS pc INNER JOIN seguro ON pc.seg_pac = seguro.id_seg INNER JOIN persona 
        ON pc.id_per = persona.id_per 
        INNER JOIN apellidos ON persona.ape_per = apellidos.id_ape INNER JOIN datos
        ON persona.datos = datos.id_datos INNER JOIN email ON datos.email = email.id_email 
        INNER JOIN telefono 
        ON datos.dat_telf = telefono.id_telf INNER JOIN direccion ON datos.dat_dir = direccion.id_dir INNER JOIN calles 
        ON direccion.calle = calles.id_calle INNER JOIN municipio ON calles.id_mun = municipio.id_mun INNER JOIN provincia ON 
        municipio.id_prov = provincia.id_prov
        SET persona.nom_per = '$nombre' , apellidos.apellido = '$apellido', email.email = '$email', pc.fec_ingr = '$fecha', telefono.n_telf = '$telefono', 
        calles.nom_calle = '$calle', municipio.nom_mun = '$munip',
        provincia.nom_prov = '$provin', pc.alerg_pac = '$alergia',pc.padec_pac = '$padecimientos' WHERE pc.id_pac=$id " ;
        
        mysqli_query($conexion, $query);
        
        header("Location: consulta_pacientes.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


</div>

<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 50%">


    <div class="card card-body">
        <form action="editarpac.php?id=<?php echo $_GET['id'];?> " method="POST">
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="nombre" class="col-form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>"
                            placeholder="Actualiza Nombre">
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="col-form-label">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                            value="<?php echo $apellido;?>" placeholder="Actualiza Apellido">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>"
                            placeholder="Actualiza Fecha">
                    </div>

                    <div class="mb-3">
                        <label for="fecha" class="col-form-label">Fecha Ingreso:</label>
                        <input type="date" class="form-control" id="fecha" name="fecha"
                            value="<?php echo $fecha_ingreso;?>" placeholder="Actualiza Fecha">
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="col-form-label">Telefono:</label>
                        <input type="telefono" class="form-control" id="telefono" name="telefono"
                            value="<?php echo $telefono;?>" placeholder="Actualiza telefono">
                    </div>

                    <div class="mb-3">
                        <label for="calle" class="col-form-label">Calle:</label>
                        <input type="text" class="form-control" id="calle" name="calle" value="<?php echo $calle;?>"
                            placeholder="Actualiza calle">
                    </div>
                </div>
                <div class="col">

                    <div class="mb-3">
                        <label for="municipio" class="col-form-label">municipio:</label>
                        <input type="text" class="form-control" id="munip" name="munip" value="<?php echo $munip;?>"
                            placeholder="Actualiza municipio">
                    </div>

                    <div class="mb-3">
                        <label for="provincia" class="col-form-label">provincia:</label>
                        <input type="text" class="form-control" id="provin" name="provin" value="<?php echo $provin;?>"
                            placeholder="Actualiza provincia">
                    </div>

                    <div class="mb-3">

                        <label for="txt_codigo" class="form-label">Alergias</label>
                        <textarea name="alergias" class="form-control" id="alergias" cols="30"
                            rows="5"><?php echo $alergia;?></textarea>

                    </div>

                    <div class="mb-3">

                        <label for="padecimientos" class="form-label">Padecimientos</label>
                        <textarea name="padecimientos" class="form-control" id="padecimientos" cols="30"
                            rows="5"><?php echo $padecimientos;?></textarea>

                    </div>
                </div>
            </div>
            <button class="btn btn-success" name="update">
                Update
            </button>
        </form>
    </div>


</div>