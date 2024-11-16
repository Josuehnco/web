<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit();
}

// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (isset($_GET['id'])) {
        $id_productos = $_GET['id'];
        
        $sql = "SELECT nombre, descripcion, imagen, precio, stock FROM productos WHERE id_productos = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_productos);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $producto = $resultado->fetch_assoc();
        } else {
            die("Producto no encontrado.");
        }
    } else {
        echo "No se ha proporcionado un ID de producto.";
        exit();
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="per.css">
</head>
<body>
    <header>
        <h1>Detalles del Producto</h1>
        <nav>
            <ul>
                <li><a href="principal.php">Inicio</a></li>
                <li><a href="principal.php#productos">Productos</a></li>
                <li><a href="#contact">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <section id="product-details">
        <div class="product-image">
            <?php if (!empty($producto['imagen'])): ?>
                <?php
                $ruta_imagen = $producto['imagen'];
                if (filter_var($ruta_imagen, FILTER_VALIDATE_URL)) {
                    $imagen_src = $ruta_imagen;
                } elseif (file_exists($ruta_imagen)) {
                    $imagen_src = $ruta_imagen;
                } elseif (file_exists('../' . $ruta_imagen)) {
                    $imagen_src = '../' . $ruta_imagen;
                } else {
                    $imagen_src = 'placeholder.jpg';
                }
                ?>
                <img src="<?php echo htmlspecialchars($imagen_src); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                     onerror="this.src='placeholder.jpg';">
            <?php else: ?>
                <img src="placeholder.jpg" alt="Imagen no disponible">
            <?php endif; ?>
        </div>
        
        <div class="product-info">
            <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
            <p class="description"><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
            <p class="price"><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
            <p class="stock"><strong>Stock:</strong> <?php echo htmlspecialchars($producto['stock']); ?> unidades</p>
            <a href="principal.php" class="button">Volver a Productos</a>
        </div>
    </section>

    <footer>
        <div id="contact">
            <h3>Contacto</h3>
            <p>Email: contacto@orientaciongrafica.com</p>
            <p>Teléfono: (123) 456-7890</p>
        </div>
    </footer>
</body>
</html>

<?php $conexion->close(); ?>
