<?php
// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ci']) && isset($_POST['nombre']) && isset($_POST['telefono']) && isset($_POST['gmail']) && isset($_POST['apellido_p']) && isset($_POST['apellido_m'])) {
    $ci = $_POST['ci'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $gmail = $_POST['gmail'];
    $apellido_p = $_POST['apellido_p'];
    $apellido_m = $_POST['apellido_m'];
    
    // Almacenar la contraseña en texto plano (NO RECOMENDADO para entornos de producción)
    $sql = "INSERT INTO personal (ci, nombre, telefono, gmail, apellido_p, apellido_m) VALUES ('$ci', '$nombre', $telefono, '$gmail', '$apellido_p', '$apellido_m')";
    if ($conexion->query($sql) === TRUE) {
        echo "Nuevo personal agregado correctamente.";
    } else {
        echo "Error al agregar el personal: " . $conexion->error;
    }
    echo "<br><a href='gestionar_usuarios.php'>Volver a la lista de usuarios y personal</a>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Personal</title>
    <link rel="stylesheet" href="edit_añad.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <h1>Agregar Nuevo Personal</h1>
    <form method="POST">
        <label for="nombre">Nombre del Personal:</label>
        <input type="text" name="nombre" required><br><br>
        
        <label for="apellido_p">Apellido Paterno:</label>
        <input type="text" name="apellido_p" required><br><br>

        <label for="apellido_m">Apellido Materno:</label>
        <input type="text" name="apellido_m" required><br><br>

        <label for="telefono">Telefono:</label>
        <input type="text" name="telefono" required><br><br>

        <label for="gmail">Gmail:</label>
        <input type="text" name="gmail" required><br><br>

        <label for="ci">CI:</label>
        <input type="text" name="ci" required><br><br>

        </select><br><br>

        <button type="submit" class="button">Agregar Personal</button>
        <a href="gestionar_usuarios.php" class="button">Cancelar</a>
    </form>
</body>
</html>

<?php $conexion->close(); ?>
