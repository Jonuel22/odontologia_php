<?php include("includes/header.php");
$user='root';
    $pass='';
    $server='localhost';
    $db='odontologia'; 
    try {
$con= new mysqli($server,$user,$pass,$db);
    } 
    catch (Exception $e) {
        echo "Error: {$e->getMessage()}";
    }
$sql = "SELECT pac.id_pac as id,CONCAT(per.nom_per,' ',ape.apellido) as Pacientes from persona per INNER JOIN paciente pac ON pac.id_per=per.id_per inner join apellidos as ape ON ape.id_ape=per.ape_per where pac.status!=2;";
date_default_timezone_set('America/Santo_Domingo');
$sql2="SELECT doc.id_empl as id,CONCAT(per.nom_per,' ',ape.apellido) as Doctores from persona per INNER JOIN empleados doc ON doc.id_per=per.id_per  inner join apellidos as ape ON ape.id_ape=per.ape_per WHERE doc.cargo=1 and doc.status!=2";
?>

<body>
    <div class="container">
        <div id="calendar"></div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="titulo">Registro de Eventos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formulario" autocomplete="off">
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <!--  <input type="hidden" id="id" name="id"> --> 
                                    <input id="title" type="text" class="form-control" name="title">
                                    <label for="title">Descripcion</label>
                                </div>

                            </div>
                            <div class="col-md-12">
                                
 <div class="form-floating mb-3">
                                    <!-- <input type="hidden" id="id" name="id" value='Conchale'> -->
                                
                                    <select name="paciente" id="paciente" class="form-control" required="" >
                                         <option value="">Seleccione un paciente</option>
                                         <?php
                            $query = mysqli_query($con,$sql);
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value='.$valores["id"].'>'.$valores["Pacientes"].'</option>';
                            }
                        ?>

                                    </select>
                                    <label for="paciente">Paciente</label>
                                </div>
                                <div class="form-floating mb-3">                                    
                                     <select name="servicio" id="servicio" class="form-control" required="" >
                                         <option value="">Servicio Solicitado</option>
                                         <?php

                            $query = mysqli_query($con,"SELECT * FROM servicios");
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value='.$valores["id_ser"].'>'.$valores["nom_ser"].'</option>';
                            }
                        ?>
                      </select>
                      <label for="servicio">Servicio</label>         
                    
                    </div>
                    <div id="tandas" style="display: inline;">
<input type="radio" id="tanda" name="tanda" value="4" checked>
<label for="todo">Todo</label>

<input type="radio" id="tanda" name="tanda" value="1">
<label for="matituno">Matutino</label>

<input type="radio" id="tanda" name="tanda" value="2">
<label for="vespertino">Vespertino</label>

<input type="radio" id="tanda" name="tanda" value="3">
<label for="nocturno">Nocturno</label>
<br>

                    </div>    
                    
                    <div class="form-floating mb-3">
                                    <!-- <input type="hidden" id="id" name="id"> -->
                                
                                    <select name="doctor" id="doctor" class="form-control" required="" >
                                         <option value="">Seleccione un Doctor</option>
                                         <?php
                            $query = mysqli_query($con,$sql2);
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value='.$valores["id"].'>'.$valores["Doctores"].'</option>';
                            }
                        ?>

                                    </select>
                                    <label for="title">Doctor</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="start" type="date" name="start" min="<?php echo date('Y-m-d');?>" required>
                                    <label for="" class="form-label">Fecha</label>
                                </div>
<div class="form-floating mb-3"> 
                                    <input class="form-control" id="hora" type="time" name="hora" required value= "<?php echo date('H:i'); ?>">
                                    <label for="" class="form-label">Hora</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="color" type="color" name="color">
                                    <label for="color" class="form-label">Color</label>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
                        <button type="submit" class="btn btn-primary" id="btnAccion">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="<?php echo base_url; ?>Assets/js/main.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/es.js"></script>
    <script>
        const base_url = '<?php echo base_url; ?>';
    </script>
    <script src="<?php echo base_url; ?>Assets/js/sweetalert2.all.min.js"></script>
    <script src="Assets/js/app.js"></script>
</body>

</html>