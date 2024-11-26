<?php 
include("../includes/header.php");
include ("../conexion.php");
?>

    <div class="container bg-white rounded shadow p-4 col-xl-4 col-lg-6" style="width: 60%">

        <h2 class="w-100 text-center mb-4">Registro de articulos</h2>

        <hr style="color: #9999" />

        <form id="formulario" method="POST" action="insertar_articulo.php">

  <div class="row">
    <div class="col">
   <label for="nombre" class="form-label">Nombre</label>
<input type="text" class="form-control" name="nombre" placeholder="Nombre del articulo" required="">

      <div class="mb-3">
                <label for="des_art" class="form-label">Descripcion</label>
                <textarea name="des_art" class="form-control" id="des_art" cols="20" rows="5" placeholder="Descripcion Breve"></textarea>
                
            </div>
                 
            <div class="mb-3">
                <label for="preven" class="form-label">Precio Unitario</label>
                <input step="any" type="number" class="form-control" id="preven" name="preven" placeholder="Ingrese el precio" required="" min="1">
            </div>

            <div class="mb-3">
                <label for="precom" class="form-label">Costo Unitario</label>
                <input  step="any" type="number" class="form-control" id="precom" name="precom" required=""  placeholder="Ingrese el Costo" min="1">
            </div>

<div class="mb-3">
                <label for="itbis" class="form-label">ITBIS</label>
               <select name="itbis" id="itbis" class="form-control" required="">
                 <option value="">Seleccione el ITBIS</option>
                 <option value="0">0%</option>
                 <option value="0.16">16%</option>
                  <option value="0.18">18%</option>
               </select>
            </div>
    </div>

    <div class="col">

            <div class="mb-3">
          
                <label for="unidad" class="form-label">
                 UNIDAD</label>

               <select class="form-control" name="unidad" required="">
            <option value="">Seleccione La unidad de medida</option>

<?php $sql="SELECT * FROM unidades";
$query = $conexion -> query ($sql);

          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_unidad"].'">'.$valores["unidad"].'</option>';
          }
   ?>

          </select>
            </div>
            

            <div class="mb-3">
                <label for="tip_art" class="form-label">Tipo de Articulo</label>
                
      <select class="form-control" name="tip_art">
        <option value="">Seleccione el tipo</option>
        <?php
          $query = $conexion -> query ("SELECT * FROM tip_articulo");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option  value="'.$valores["id_tip"].'">'.$valores["tip_art"].'</option>';
          }
        ?>
      
      </select>
            
            </div>

    </div>
  </div>
      <button type="submit" class="btn btn-primary w-100 text-uppercase fw-bold">Insertar</button>
</form>
    </div>
<?php include("../includes/footer.php")?> 