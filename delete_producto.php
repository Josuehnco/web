<?php
// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_productos'])) {
    $product_id = $_POST['id_productos'];
    
    if (isset($_POST['confirmar'])) {
        $sql = "DELETE FROM productos WHERE id_productos = $product_id";
        if ($conexion->query($sql) === TRUE) {
            echo "Producto eliminado correctamente.";
        } else {
            echo "Error al eliminar el producto: " . $conexion->error;
        }
        echo "<br><a href='otros.php' class='button'>Volver a las opciones de productos</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Producto</title>
    <link rel="stylesheet" href="sss/productos.css">
</head>
<body>
    <h1>Confirmar Eliminación</h1>
    <?php if (isset($product_id)) : ?>
        <p>¿Estás seguro que deseas borrar el producto con ID: <?php echo $product_id; ?>?</p>
        <form method="POST">
            <button type="submit" name="confirmar" class="button">Confirmar</button>
            <a href="otros.php" class="button">Cancelar</a>
            <input type="hidden" name="id_productos" value="<?php echo $product_id; ?>">
        </form>
    <?php else : ?>
        <p>No se encontró el producto.</p>
    <?php endif; ?>
</body>
</html>

<?php $conexion->close(); ?>

