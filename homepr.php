<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: principal.php");
    exit();
}

// Obtener el rol del usuario
$rol = $_SESSION['rol'] ?? 'usuario';

// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consultar los productos
$sql = "SELECT id_productos, nombre, precio, catalogo, descripcion, imagen, enlace FROM productos";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Orientacion Graficar</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="">
</head>
<body>
    <header>
        <h1>Centro de Orientacion Grafica</h1>
        <nav>
            <ul>
            <li><a href="home.html">Cerrar Sesion</a></li>
                <li><a href="#">Inicio</a></li>
                <li><a href="#books">Productos</a></li>
                <li><a href="#contact">Contacto</a></li>
                <?php if ($rol === 'admin') : ?>
                    <li><a href="otros.php">Otros</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section id="welcome">
        <h2>Bienvenidos a Centro de Orientacion Grafica</h2>
        <p>Marca </p>
    </section>

    <section id="products">
    <h2>Productos Disponibles</h2>
    <div class="product-container">
        <?php if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) { ?>
                <div class="product">
                    <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                    <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                    <p>Precio: $<?php echo htmlspecialchars($row['precio']); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($row['stock']); ?></p>
                    <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                    <a href="detalle_producto.php?id=<?php echo $row['id_productos']; ?>" class="button">Ver Detalles</a>
                </div>
        <?php }
        } else {
            echo "<p>No hay productos registrados.</p>";
        } ?>
    </div>
</section>
</body>
</html>

<?php $conexion->close(); ?>