<?php
include ("../conexion.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Clinica BS</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
	<link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet">
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css'>
	<link rel="stylesheet" type="text/css" href="fuentes/iconic/css/material-design-iconic-font.min.css">
</head>

<body>



		<div class="container" id="container">
			<div class="form-container sign-up-container">
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
			<div class="form-container sign-in-container">
				<form action="validarlog.php" id="form" method="post">
					<h1>LOGIN</h1>
					<div class="social-container">
						<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
						<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
						<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
					</div>
					<span>Usa tu propia cuenta</span>
					<input type="text" id="username" name="username" placeholder="Usuario" />
					<input type="password" id="password" name="password" placeholder="Password" />
					<a href="#">Olvidaste la contraseña?</a>
					<button type="submit" value="Iniciar" name="inicio" id="btn" class="login-form-btn">Login</button>
				</form>
			</div>
			<div class="overlay-container">
				<div class="overlay">
					<div class="overlay-panel overlay-left">
						<h1>Ingresar con tu cuenta!</h1>
						<p>Para acceder por favor de ingresar con tu cuenta</p>
						<button class="ghost" id="signIn">LOGIN</button>
					</div>
					<div class="overlay-panel overlay-right">
						<h1>NUEVO USUARIO</h1>
						<p>Ingresa tus datos para poder registrarte por primera vez</p>
						<button class="ghost" id="signUp">REGISTRO</button>
					</div>
				</div>
			</div>
		</div>


	<script src="script.js" charset="utf-8"></script>
</body>

</html>