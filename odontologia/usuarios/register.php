<?php
include ("../conexion.php");
?>
<?php include("../includes/header.php")?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Clinica BS</title>
	<link rel="stylesheet" href="styluser.css">	
	
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css'>

</head>

<body>


		<div class="container" id="container">
			
			<div class="form-container sign-in-container">
			<form action="insertar_usuario.php" class="formulario" id="form" method="POST">
					<h1>REGISTRARSE</h1>
					<div class="social-container">
						<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
						<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
						<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
					</div>
					<span>Llenar los campos solicitados</span>
					<input type="text" id="username" name="username" placeholder="Usuario" />
					<input type="password" id="password" name="password" placeholder="Password"/>
					<input type="password" id="password" name="re_password" placeholder="Confirmar contraseña" />
					<div class="select">
						<select class="form-control" name="nivel">
							<option value="selected disable">Nivel de acceso</option>
							<?php
								$query = $conexion -> query ("SELECT * FROM niveles");
								while ($valores = mysqli_fetch_array($query)) {
									echo '<option>'.$valores["nivel"].'</option>';
								}
							?>
	
						</select>
					</div>
					<br>
					<button type="submit" value="Registrar" name="registrar" id="btn"
                            class="login-form-btn">REGISTRAR</button>
				</form>
			</div>
			<div class="overlay-container">
				<div class="overlay">
					<div class="overlay-panel overlay-right">
						<h1>NUEVO USUARIO</h1>
						<p>Recuerda Llenar los campos pedidos, para poder agregar un nuevo usuario al Sistema</p>
					</div>
				</div>
			</div>
		</div>


</body>

</html>