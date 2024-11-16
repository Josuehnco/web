<?php
// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost","josue","1234","centro de estampados", "3306");


// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['password']) && isset($_POST['correo'])) {
    $usuario = mysqli_real_escape_string ($conexion,$_POST['usuario']);
    $password = mysqli_real_escape_string ($conexion,$_POST['password']);
    $correo = mysqli_real_escape_string ($conexion,$_POST['correo']);

    // Almacenar la contraseña en texto plano (NO RECOMENDADO para entornos de producción)
    $sql = "INSERT INTO usuarios (usuario, password, correo) VALUES ('$usuario', '$password', '$correo')";
    if ($conexion->query($sql) === TRUE) {
        echo "Nuevo usuario agregado correctamente.";
    } else {
        echo "Error al agregar el usuario: " . $conexion->error;
    }
    echo "<br><a href='principal.php'>Volver a la lista de usuarios</a>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="registrars.css"> <!-- Enlace al archivo CSS -->
<body>
<div class="cudr">
    <h1 class ="unase">Únase a Centro de Orientacion Serigrafica</h1>
    <form method="POST">
        <label for="usuario">Nombre de Usuario:</label>
        <input type="text" name="usuario" required><br><br>

        <label for="correo">correo:</label>
        <input type="email" name="correo" required><br><br>
        
        <label for="password">password:</label>
        <input type="password" name="password" required><br><br>
        
     

        </select><br><br>

        <button type="submit" class="button">Agregar Usuario</button><br><br>
        <a href="home.html" class="button">Cancelar</a>

        <h1 class="sesion">¿Ya tiene una cuenta?</h1>
        <a href="validar.php" class="button">Inicia sesion</a>
        <h2 class="terminos" >Al registrarse en Centro de estampados y serigrafia, acepta nuestros Términos y condiciones, nuestra Política de privacidad y nuestra Política de cookies.</h2>
    </form>
   </div>
  
</body>
</html>

<?php $conexion->close(); ?>

