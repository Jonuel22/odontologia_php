<?php include ("../conexion.php");?>

<?php ob_start(); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Factura Compra</title>
</head>

<body>

<style>    
    section{
    padding-top: 0px;
    width: 940px;   
    background-color: white; 
    border: white;
    margin: 0 auto;
    }

  table#space {
  border-collapse: separate;
  border-spacing: 10px 5px;
  }

</style>

<?php 

$total_final = 0;

$id_fac=mysqli_query($conexion,"SELECT MAX(id_fac) FROM factura_compra");
$row = mysqli_fetch_array($id_fac);
$id_fac=$row[0];

$fec_fac=mysqli_query($conexion,"SELECT (fec_fac) FROM factura_compra where id_fac='$id_fac'");
$row1 = mysqli_fetch_array($fec_fac);
$fec_fac=$row1[0];

$concepto=mysqli_query($conexion,"SELECT con.concepto from concepto_fact con inner join factura_compra fc on  fc.concepto=con.id where id_fac='$id_fac'");
$row1 = mysqli_fetch_array($concepto);
$concepto=$row1[0];

$tip_fac=mysqli_query($conexion,"SELECT (tip_fac) FROM factura_compra where id_fac='$id_fac'");
$row1 = mysqli_fetch_array($tip_fac);
$tip_fac=$row1[0];

$entidad=mysqli_query($conexion,"SELECT ent.nom_ent from entidad ent inner join factura_compra fc on  fc.entidad=ent.id_ent WHERE fc.id_fac='$id_fac'");
$row1 = mysqli_fetch_array($entidad);
$entidad=$row1[0];

$metodo=mysqli_query($conexion,"SELECT (tip_fac) FROM tip_factura where id_tip='$tip_fac'");
$row1 = mysqli_fetch_array($metodo);
$metodo=$row1[0];

if($concepto=="VENTA")
  $ent="CLIENTE";
else $ent="SUPLIDOR";

?>
   <center>
   <section id="container" >
    <div class="title_page">
     <h1><i class="fas fa-cube"></i>Factura</h1>
    </div>

    <div class="datos_cliente">
        <div class="action_cliente">
            <h4>Clínica Odontológica Blanca Sonrisa (BS)</h4>
           <p> Calle Boy Scout No. 83, Plaza Jasansa, Santiago, Rep. Dom. </p> 
           <p><b>Teléfono: </b>829-660-9165<b> Correo: </b>Clinicabs809@gmail.com</p>
        </div>
    </div>
    <p align="right"> <b>Fecha: </b><?php echo $fec_fac ?> </p>
    <hr size="8px" color="black" />
    <div align="left"> 
  <p><b><?php echo $ent ?>:</b><?php echo $entidad."<br><br><b>CONCEPTO:</b>".$concepto ?></p>    
    </div>
<div align="right">
  
<p>
<p align="left"> <b>TIPO DE FACTURA: </b> <?php echo $metodo ?> </p><b align="right">FACTURA: </b>FAC-<?php echo $id_fac ?>
 </p> 
</div>

<hr size="8px" color="black" />



   <div class="columns">

<table WIDTH="700" class="table"> 

<thead>
                <tr class="text-center">
                    <th align="center">ARTICULO</th>
                    <th align="right">CANTIDAD</th>
                    <th align="right">SUB-TOTAL</th>
                    <th align="right">ITBIS</th>
                    <th align="right">TOTAL</th>


                </tr>
            </thead>

<?php 
$query="SELECT art.nombre ,cantidad,subtotal,itbis,total FROM detalle_fact_com as dc INNER JOIN articulos as art ON dc.id_art=art.id_art WHERE dc.id_fac='$id_fac'";

                                $result_task = mysqli_query($conexion, $query);
                                while($row = mysqli_fetch_array($result_task)){?>



                            <tr>
                                <td align="center" ><?php echo $row['nombre'] ?></td>
                                <td align="right" ><?php echo number_format($row['cantidad'],2) ?></td>
                                <td align="right" >$<?php echo number_format($row['subtotal'],2) ?></td>
                                <td align="right" >$<?php echo number_format($row['itbis'],2) ?></td>
                                <td align="right" >$<?php echo number_format($row['total'],2) ?></td>


                                <?php 
                                $acum = $row ['total'];
                                $total = intval($acum);
                                $total_final = $total_final+$total;
                                 ?>
                            </tr>


                    
                            
                        
                        <?php }?>
                
                    </div>
                    
                    </table>

                    <hr size="8px" color="black" />

<p align="right"> <b>TOTAL ACUMULADO: </b>$<?php echo number_format($total_final,2) ?> </p>               





<p align="left"> <b>______________________ </b> </p>
<p align="left"> <b>Firma del dependiente: </b> </p>

<hr size="8px" color="black" />


<div class="linea">

&nbsp;&nbsp;&nbsp;&nbsp;

<br>



</div>


</section>
   </center>


</body>
</html>

<?php 
$factura=ob_get_clean();




require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($factura);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

//si le pones true el pdf se descarga directamente
$dompdf->stream("Factura.pdf", array("Attachment" => false));


?>

<?php include("../includes/footer.php")?> 