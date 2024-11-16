<?php
// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Verificar conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el ID del usuario desde el formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_personal'])) {
    $personal_id = $_POST['id_personal'];
    
    // Si se confirma la eliminación
    if (isset($_POST['confirmar'])) {
        $sql = "DELETE FROM personal WHERE id_personal = $personal_id";
        if ($conexion->query($sql) === TRUE) {
            echo "Personal eliminado correctamente.";
        } else {
            echo "Error al eliminar el personal: " . $conexion->error;
        }
        echo "<br><a href='otros.php'>Volver a la lista de personal</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Personal</title>
    <link rel="stylesheet" href="css\edit_añ_del.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <h1>Confirmar Eliminación</h1>
    <?php if (isset($personal_id)) : ?>
        <p>¿Estás seguro que deseas borrar al personal con ID: <?php echo $personal_id; ?>?</p>
        <form method="POST">
            <button type="submit" name="confirmar" class="button">Confirmar</button>
            <a href="otros.php" class="button">Cancelar</a>
            <input type="hidden" name="id_personal" value="<?php echo $personal_id; ?>">
        </form>
    <?php else : ?>
        <p>No se encontró el personal.</p>
    <?php endif; ?>
</body>
</html>

<?php $conexion->close(); ?>