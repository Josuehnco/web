<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configuración de la conexión a la base de datos
    $conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    if (isset($_POST['correo']) && isset($_POST['password'])) {
        $correo = $_POST['correo'];
        $password = $_POST['password'];

        // Consulta para verificar el usuario
        $sql = "SELECT id_usuarios, password, rol FROM usuarios WHERE correo = '$correo'";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            
            if ($password === $fila['password']) {
                $_SESSION['correo'] = $correo;
                $_SESSION['rol'] = $fila['rol'];
                header("Location: principal.php");
                exit();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error'] = "Usuario no encontrado.";
        }
    }
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="cudr">
        <h1>Centro de Orientación Gráfica</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <?php if(isset($_SESSION['error'])): ?>
                <div id="error-message">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <button type="submit">Iniciar sesión</button>
            <p>¿No tienes una cuenta? <a href="registrarse.php">Regístrate aquí</a></p>
            <a href="home.html" class="Cancelar">Cancelar<a/>
        </form>
    </div>
</body>
</html>