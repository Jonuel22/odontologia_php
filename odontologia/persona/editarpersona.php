<?php
    include('../conexion.php');

  //  $conexion = mysqli_connect("localhost", "root","","odontologia") or die ("Error");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $query ="SELECT p.id_per, p.nom_per, p.fec_nac, p.sex_per, 
        a.apellido,e.email, d.num_docu, t.id_tip, t.tip_docu, tl.n_telf, dr.id_dir, c.nom_calle, m.nom_mun, pr.nom_prov
        FROM persona AS p
        LEFT JOIN apellidos AS a ON p.ape_per = a.id_ape LEFT JOIN datos AS dt 
        ON p.datos = dt.id_datos LEFT JOIN email AS e ON dt.email = e.id_email LEFT JOIN documentos as d on 
        dt.dat_docu = d.id_docu LEFT JOIN  tip_documento as t on d.tip_docu = t.id_tip LEFT JOIN telefono as tl on dt.dat_telf = tl.id_telf
        LEFT JOIN direccion as dr on dt.dat_dir = dr.id_dir LEFT JOIN calles as c on dr.calle = c.id_calle LEFT JOIN municipio as m
        on c.id_mun = m.id_mun LEFT JOIN provincia as pr on m.id_prov = pr.id_prov WHERE id_per = $id";
        $result = mysqli_query($conexion, $query);

        if(mysqli_num_rows($result) == 1){
            $option = '';
            
            while($row = mysqli_fetch_array($result)){

            
                //$codigo = $row['codigo'];
                $nombre = $row['nom_per'];
                $apellido = $row['apellido'];
                $fecha_nacimiento = $row['fec_nac'];
                $sexo = $row['sex_per'];
                $email = $row['email'];
                $idtipdoc = $row['id_tip'];
                $tipdocu = $row['tip_docu'];
                $docum = $row['num_docu'];
                $telefono = $row['n_telf'];
                $calle = $row['nom_calle'];
                $munip = $row['nom_mun'];
                $provin = $row['nom_prov'];

                if($idtipdoc == 1){
                    $option = '<option value="'.$idtipdoc.'"select>'.$tipdocu.'</option>';
                }
                else if($idtipdoc == 2){
                    $option = '<option value="'.$idtipdoc.'"select>'.$tipdocu.'</option>';
                }
                else if($idtipdoc == 3){
                    $option = '<option value="'.$idtipdoc.'"select>'.$tipdocu.'</option>';
                }
                else if($idtipdoc == 4){
                    $option = '<option value="'.$idtipdoc.'"select>'.$tipdocu.'</option>';
                }
                else if($idtipdoc == 5){
                    $option = '<option value="'.$idtipdoc.'"select>'.$tipdocu.'</option>';
                }
                
            }        
        }
    }

    if(isset($_POST['update'])){
        $id = $_GET['id'];
        //$codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $sexo = $_POST['sexo'];
        $email = $_POST['email'];
        $tipdocu = $_POST['tipdocu'];
        $docum = $_POST['docu'];
        $telefono = $_POST['telefono'];
        $calle = $_POST['calle'];
        $munip = $_POST['munip'];
        $provin = $_POST['provin'];

        $query = "UPDATE persona INNER JOIN datos ON persona.datos = datos.id_datos INNER JOIN apellidos 
        ON persona.ape_per = apellidos.id_ape INNER JOIN email ON datos.email = email.id_email INNER JOIN documentos ON
        datos.dat_docu = documentos.id_docu INNER JOIN tip_documento ON documentos.tip_docu = tip_documento.id_tip INNER JOIN telefono 
        ON datos.dat_telf = telefono.id_telf INNER JOIN direccion ON datos.dat_dir = direccion.id_dir INNER JOIN calles 
        ON direccion.calle = calles.id_calle INNER JOIN municipio ON calles.id_mun = municipio.id_mun INNER JOIN provincia ON 
        municipio.id_prov = provincia.id_prov
        SET persona.nom_per = '$nombre' , apellidos.apellido = '$apellido', email.email = '$email', documentos.num_docu = '$docum',
        documentos.tip_docu = '$tipdocu', telefono.n_telf = '$telefono', calles.nom_calle = '$calle', municipio.nom_mun = '$munip',
        provincia.nom_prov = '$provin'
        WHERE persona.id_per=$id";
        
        
        mysqli_query($conexion, $query);
        
        header("Location: consulta_persona.php");
    }

?>

<?php include("../includes/header.php")?>

<?php include("../includes/footer.php")?>


<div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 50%">

    
   
<div class="card card-body">
    <form action="editarpersona.php?id=<?php echo $_GET['id'];?> " method="POST">
        <div class="row">
            <div class="col">
                <div class="mb-3">
                        <label for="nombre" class="col-form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>"  placeholder="Actualiza Nombre">
                </div>
                    <div class="mb-3">
                        <label for="apellido" class="col-form-label">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $apellido;?>"  placeholder="Actualiza Apellido">
                    </div>
                   
                    <div class="mb-3">
                        <label for="sexo" class="col-form-label">Sexo:</label>
                        <input type="text" class="form-control" id="sexo" name="sexo" value="<?php echo $sexo;?>"  placeholder="Actualiza Fecha">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>"  placeholder="Actualiza Fecha">
                    </div>

                    <div class="mb-3">
                        <label for="tipdocu" class="form-label">Tipo Documento</label>
                        <br>
   
                        <?php
                            include("../conexion.php");
                            $query_tipdocu =  mysqli_query($conexion, "SELECT * FROM tip_documento");
                            mysqli_close($conexion);
                            $result_tipdocu = mysqli_num_rows($query_tipdocu);
                        ?>

                        <select name="tipdocu" id="tipdocu" class="notItemOne">
                            <?php
                                echo $option;
                                if($result_tipdocu >0){
                                    while($tipdocu = mysqli_fetch_array($query_tipdocu)){

                            ?>
                                    <option value="<?php echo $tipdocu["id_tip"]; ?>"><?php echo $tipdocu["tip_docu"] ?></option>
                                    
                            <?php      
                                    
                                    }

                                }
                            
                            ?>

                        </select>




                    </div>
            </div>

            <div class="col">

                    <div class="mb-3">
                        <label for="documento" class="col-form-label">Documento:</label>
                        <input type="text" class="form-control" id="docu" name="docu" value="<?php echo $docum;?>"  placeholder="Actualiza Documento">
                    </div>


                    <div class="mb-3">
                        <label for="telefono" class="col-form-label">Telefono:</label>
                        <input type="telefono" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono;?>"  placeholder="Actualiza telefono">
                    </div>

                    <div class="mb-3">
                        <label for="calle" class="col-form-label">Calle:</label>
                        <input type="text" class="form-control" id="calle" name="calle" value="<?php echo $calle;?>"  placeholder="Actualiza calle">
                    </div>

                    <div class="mb-3">
                        <label for="municipio" class="col-form-label">municipio:</label>
                        <input type="text" class="form-control" id="munip" name="munip" value="<?php echo $munip;?>"  placeholder="Actualiza municipio">
                    </div>

                    <div class="mb-3">
                        <label for="provincia" class="col-form-label">provincia:</label>
                        <input type="text" class="form-control" id="provin" name="provin" value="<?php echo $provin;?>"  placeholder="Actualiza provincia">
                    </div>
                    </div>

                    </div>

                    <button class="btn btn-success" name="update">
                        Update
                    </button>
                    
            </div>
            


                    
        </div>
       
    </form>
            

      
</div>

