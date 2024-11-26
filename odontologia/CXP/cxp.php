 <?php include("../includes/header.php");
include("../conexion.php");
 ?>
<head><title>CXP</title></head>
<style >
  
td{
  text-align: right;
}

</style>
<body>
	    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: auto">

        <h2 class="w-100 text-left mb-4">Cuentas Por Pagar</h2>

        <hr style="color: #9999" />

        <form name="formulario" method="POST" action="insertar_cxp.php">
      
        	
  <div class="row">
    <div class="col">

    </div>

    <div class="col">

          
    </div>

   <div id="div_facturacion">
   <div class="col-7 col-md-3">
     
               <select class="form-control" name="id_fac" required>

<option value="">Seleccione la factura</option>
<?php 
$sql="SELECT * FROM cxp  WHERE cxp.status=3";

                            $query = mysqli_query($conexion,$sql);
                            $i=0;
                 while ($valores = mysqli_fetch_array($query) ){
 echo '<option value='.$valores["id_fac"].'>'."FAC-".$valores["id_fac"].'</option>';
                 }
 ?>
</select>   
   
   </div>
     
  <table class="table table-striped table-bordered">
  	<thead class="">
  	<tr>
  		<th style="text-align: left;">#</th>
  		<th>Factura</th>
  		<th>TOTAL A PAGAR</th>
  		<th>AVANCE</th>
  		<th>PENDIENTE</th>
  	</tr>	
  	</thead>
  	
  	<tbody>
  		      <?php
$sql="SELECT cxp.id_fac,cxp.pend Pendiente,cxp.pagado Pagado,f_c.total A_pagar,cxp.status FROM cxp inner join factura_compra f_c on f_c.id_fac=cxp.id_fac WHERE cxp.status=3";

                            $query = mysqli_query($conexion,$sql);
                            $i=0;
                 while ($valores = mysqli_fetch_array($query) ) {
$i++;
echo '
                                <tr>
                                <td style="text-align: left;">'.$i.'</td >
                               <td>'."FAC-".$valores['id_fac'].'</td>
                <td>'.number_format($valores["A_pagar"],2).'</td>
                <td>'.number_format($valores["Pagado"],2).'</td> 
                <td>'.number_format($valores["Pendiente"],2).'</td>
                </tr>
     ';

}//Fin del primer while

                        ?>

  	</tbody>

  </table>

   </div>                               
<div class="col-7 col-md-3">

<label for="color" class="form-label">Pago</label>
<input class="form-control" id="pago" required type="number" name="pago" min="1">

</div>

  </div>
  <div class="col-7 col-md-3" >  
    <button type="submit" class="btn btn-primary" name="guardar">Confirmar Pagos</button>
      </div>
                                  
</form>

<br>

    </div>
</body>
<?php include("../includes/footer.php"); ?>