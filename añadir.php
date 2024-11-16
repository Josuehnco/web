<?php
// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['contraseña']) && isset($_POST['id_cliente']) && isset($_POST['rol'])) {
    $nombre_usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $id_cliente = $_POST['id_cliente'];
    $rol = $_POST['rol'];
    
    // Almacenar la contraseña en texto plano (NO RECOMENDADO para entornos de producción)
    $sql = "INSERT INTO usuarios (usuario, contraseña, id_cliente, rol) VALUES ('$nombre_usuario', '$contraseña', $id_cliente, '$rol')";
    if ($conexion->query($sql) === TRUE) {
        echo "Nuevo usuario agregado correctamente.";
    } else {
        echo "Error al agregar el usuario: " . $conexion->error;
    }
    echo "<br><a href='otros.php'>Volver a la lista de usuarios</a>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="css/edit_añ_del.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <h1>Agregar Nuevo Usuario</h1>
    <form method="POST">
        <label for="usuario">Nombre de Usuario:</label>
        <input type="text" name="usuario" required><br><br>
        
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required><br><br>

        <label for="id_cliente">ID Cliente:</label>
        <input type="number" name="id_cliente" required><br><br>

        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="admin">Admin</option>
            <option value="usuario">Usuario</option>
        </select><br><br>

        <button type="submit" class="button">Agregar Usuario</button>
        <a href="otros.php" class="button">Cancelar</a>
    </form>
</body>
</html>

<?php $conexion->close(); ?>

