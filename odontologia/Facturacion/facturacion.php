 <?php include("../includes/header.php");
include("../conexion.php");

$pac=$_POST["paciente"];
$sql = "SELECT pac.id_pac as id,CONCAT(per.nom_per,' ',ape.apellido) as Pacientes from persona per INNER JOIN paciente pac ON pac.id_per=per.id_per inner join apellidos as ape ON ape.id_ape=per.ape_per WHERE pac.id_pac='$pac';";
 ?>
<head><title>Facturacion</title></head>
<style>
   td{

    text-align: right;
}
</style>
<body>
	    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: auto">

        <h2 class="w-100 text-left mb-4">Facturacion</h2>

        <hr style="color: #9999" />

        <form name="formulario" method="POST" action="insertar_factura.php">
      
        	<?php echo 	"<input type='hidden' name='pac_id' value=".$pac.">" ?>
  <div class="row">
    <div class="col">
      <div class="mb-3">
      	<?php 
      	$query = mysqli_query($conexion,$sql);
      	$valores = mysqli_fetch_array($query);
      	echo " <label class='form-label'>
                  <a style='color:black' class='navbar-brand' ' href='elige_paciente.php'>Paciente:</a>".$valores["Pacientes"]."
                </label>
";

      	?>            </div>

    </div>

    <div class="col">

          
    </div>

   <div id="div_facturacion">
   <div class="col-7 col-md-3">
     
               <select class="form-control" name="cita" required>

<option value="">Seleccione la Cita a pagar</option>
<?php 
$sql="SELECT e.status as Estado,e.id as ID,e.title as Cita, ser.nom_ser as Servicio,ser.cost_ser Total FROM evento e INNER JOIN servicios ser ON e.servicio=ser.id_ser WHERE e.paciente='$pac' AND e.status_pago!=6 and e.status!=4";

                            $query = mysqli_query($conexion,$sql);
                            $i=0;
                 while ($valores = mysqli_fetch_array($query) ){
 echo '<option value='.$valores["ID"].'>'.$valores["Cita"].'</option>';
                 }
 ?>
</select>   
   
   </div>
     
  <table class="table table-striped table-bordered" id="t_factura" name="t_factura">
  	<thead class="">
  	<tr>
  		<th>#</th>
  		<th>CITA</th>
  		<th>SERVICIO</th>
  		<th>TOTAL</th>
  		<th>PAGADO</th>
  		<th>PENDIENTE</th>
  	</tr>	
  	</thead>
  	
  	<tbody>
  		      <?php
$bandera=0;
$sql="SELECT e.status as Estado,e.id as ID,e.title as Cita, ser.nom_ser as Servicio,ser.cost_ser Total FROM evento e INNER JOIN servicios ser ON e.servicio=ser.id_ser WHERE e.paciente='$pac' and e.status!=6 and e.status!=4";

                            $query = mysqli_query($conexion,$sql);
                            $i=0;
                 while ($valores = mysqli_fetch_array($query) ) {
              	

//echo "<input type='hidden' value=".$valores['ID'] ." name='cita[]'' >";
//echo "<input type='hidden' id='total' value=".$valores["Total"] ." name='total[]'' >";

$id=$valores["ID"];       
$query_fact=mysqli_query($conexion,"SELECT id_cita
  from factura as f
  where EXISTS(SELECT * from evento e where f.id_pac='$pac' AND f.id_cita='$id')");
$valores_fact=mysqli_fetch_array($query_fact); 
//Si devuelve 0 la cita no tiene ninguna factura hecha aun, si devuelve 1 significa que hay alguna factura y hay algun avance de pago.

IF(is_null($valores_fact) OR $valores_fact[0]==0){

$i++;

echo '
                                <tr>
                                <td >'.$i.'</td >
                               <td>'.$valores['Cita'].'</td>
                <td>'.$valores["Servicio"].'</td>
                <td >'.number_format($valores["Total"],2).'</td>
                <td>0.00</td> 
                <td>'.number_format($valores["Total"],2).'</td>
                </tr>
     ';
  
}
else{
  $bandera++;
}

                            


}//Fin del primer while

if ($bandera>0) {//Si bandera es mayor que 0 significa que el paciente tiene citas en facturacion avanzadas


$sql2="SELECT e.status as Estado, SUM(fac.total_pag) as Pagado,e.id as ID,e.title as Cita, ser.nom_ser as Servicio,ser.cost_ser Total FROM factura fac INNER JOIN evento e ON fac.id_cita=e.id INNER JOIN servicios ser ON e.servicio=ser.id_ser  WHERE e.paciente='$pac' and e.status_pago!=6 and e.status!=4 GROUP BY e.id";
$query2 = mysqli_query($conexion,$sql2);

   while ($valores2 = mysqli_fetch_array($query2) ) {
                
$i++;


echo '
                                <tr>
                                <td >'.$i.'</td >
                               <td>'.$valores2['Cita'].'</td>
                <td>'.$valores2["Servicio"].'</td>
                <td >'.number_format($valores2["Total"],2).'</td>
                <td>'.number_format($valores2["Pagado"],2).'</td> 
                <td>'.number_format(($valores2["Total"]-$valores2["Pagado"]),2).'</td>
                </tr>
     ';




                            }


}//Fin del while



                        ?>

  	</tbody>

  </table>

   </div>                               
<div class="col-7 col-md-3">

<label for="color" class="form-label">Pago</label>
<input class="form-control" id="pago" required type="number" name="pago" min="1">

</div>

  </div>
  <div class="col-7 col-md-3" style="float: right;">  
    <button type="submit" class="btn btn-primary" name="guardar">Confirmar Pagos</button>
      </div>
                                  
</form>

<br>

    </div>
 



</body>
<?php include("../includes/footer.php"); ?>