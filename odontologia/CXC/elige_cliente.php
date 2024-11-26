<?php include("../includes/header.php");
include("../conexion.php");
    
$sql="SELECT en.id_ent,en.nom_ent Nombre from clientes cli inner join  entidad en on en.id_ent=cli.id_ent inner join factura_compra fac on 
fac.entidad=cli.id_ent inner join cxc on cxc.id_fac=fac.id_fac
where cli.status=1 and cxc.pend>0 GROUP BY cli.id_ent";
 ?>
<head><title>CXC</title></head>

<body>
        <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 30%">

        <h2 class="w-100 text-left mb-4">Cuentas Por Cobrar</h2>

        <hr style="color: #9999" />

        <form action="cxc.php" name="formulario" method="POST" >

  <div class="row">
    <div class="col">
      <div class="mb-3">
                 <select  name="cliente" id="cliente" class="form-control">
                                         <option value="">Selecciona un cliente...</option>
                                         <?php

                            $query = mysqli_query($conexion,$sql);
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value='.$valores["id_ent"].'>'.$valores["Nombre"].'</option>';
                            }
                        ?>
                                    </select>
            </div>
<button type="submit" class="btn btn-secondary w-100 text-uppercase fw-bold">Ver CXC...</button>
    </div>
</FORM>
<?php include("../includes/footer.php");