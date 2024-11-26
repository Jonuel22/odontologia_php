 <?php include("../includes/header.php");
include("../conexion.php");
 if (!isset($_POST["cliente"])) {
echo'<script type="text/javascript">
window.location.href="elige_cliente.php";
</script>';

 }
else{
  $cliente=$_POST["cliente"];

 ?>

<head><title>CXC</title></head>
<style >
  
td{
  text-align: right;
}

</style>
<body>
	    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: auto">

  <h2 class="w-100 text-left mb-4">Cuentas Por Cobrar</h2>

        <hr style="color: #9999" />

        <form name="formulario" method="POST" action="insertar_cxc.php">
      
  <div class="row">
    <div class="col">

    </div>

    <div class="col">

          
    </div>

   <div id="div_facturacion">
   <div class="col-7 col-md-3">
     

  <?php $sql="SELECT nom_ent from entidad where id_ent='$cliente'";
  $query=mysqli_query($conexion,$sql);
$fila = mysqli_fetch_array($query)  ;
echo "<h5>Cliente: ".$fila[0]."</h5>";
   ?>             

               <select class="form-control" name="id_fac" required>

<option value="">Seleccione la factura a pagar...</option>
<?php 
$sql="SELECT fac.id_fac id_fac FROM cxc inner join factura_compra fac on cxc.id_fac=fac.id_fac inner join clientes cli on cli.id_ent=fac.entidad where fac.entidad='$cliente'  AND cxc.status!=6";

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
$sql="SELECT fac.id_fac,cxc.pend Pendiente,cxc.pagado Pagado,FAC.total A_pagar FROM cxc inner join factura_compra fac on cxc.id_fac=fac.id_fac inner join clientes cli on cli.id_ent=fac.entidad where fac.entidad='$cliente' AND cxc.status=3;";



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
<br>
<div id="met_pago">   
<label for="tip_pag"><strong>METODO DE PAGO</strong></label>
<br>    
        <input type="radio"  name="met_pag[]" value="1"
             checked >EN EFECTIVO
<br>
             <input type="radio" name="met_pag[]" value="2"
           >CON TARJETA DEBITO/CREDITO
</div>

</div>

  </div>
  <div class="col-7 col-md-3" >  
    <button type="submit" class="btn btn-primary" name="guardar">Confirmar Pagos</button>
      </div>
                                  
</form>

<br>

    </div>
</body>
<?php include("../includes/footer.php"); }?>