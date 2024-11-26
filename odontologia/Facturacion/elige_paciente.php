<?php include("../includes/header.php");
include("../conexion.php");
    
$sql = "SELECT pac.id_pac as id,CONCAT(per.nom_per,' ',ape.apellido) as Pacientes from persona per INNER JOIN paciente pac ON pac.id_per=per.id_per inner join apellidos as ape ON ape.id_ape=per.ape_per inner join evento e on e.paciente=pac.id_pac where e.status_pago!=6 && e.status!=4
GROUP BY e.paciente
;";
 ?>
<head><title>Facturacion</title></head>

<body>
        <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 30%">

        <h2 class="w-100 text-left mb-4">Facturacion</h2>

        <hr style="color: #9999" />

        <form action="facturacion.php" name="formulario" method="POST" >

  <div class="row">
    <div class="col">
      <div class="mb-3">
                 <select  name="paciente" id="paciente" class="form-control">
                                         <option value="">Selecciona un paciente</option>
                                         <?php

                            $query = mysqli_query($conexion,$sql);
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value='.$valores["id"].'>'.$valores["Pacientes"].'</option>';
                            }
                        ?>
                                    </select>
            </div>
<button type="submit" class="btn btn-secondary w-100 text-uppercase fw-bold">Ir a facturacion</button>
    </div>
</FORM>
<?php include("../includes/footer.php");