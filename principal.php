<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener información del usuario
$correo = $_SESSION['correo'];
$sql_usuario = "SELECT usuario, rol FROM usuarios WHERE correo = ?";
$stmt = $conexion->prepare($sql_usuario);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado_usuario = $stmt->get_result();
$datos_usuario = $resultado_usuario->fetch_assoc();
$rol = $datos_usuario['rol'] ?? 'usuario';

// Consultar productos
$sql = "SELECT id_productos, nombre, precio, stock, descripcion, imagen FROM productos";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Orientación Gráfica</title>
    <link rel="stylesheet" href="per.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Poppins:wght@400;500&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <script>
        function toggleDropdown() {
            document.getElementById("userDropdown").classList.toggle("show");
        }

        // Cerrar el dropdown si el usuario hace clic fuera de él
        window.onclick = function(event) {
            if (!event.target.matches('.user-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</head>
<body>
    <header>
        <div class="header-content">
                <img src="img/images.png"class="logo">
                <h1>Centro de Orientación Gráfica</h1>
            <div class="user-menu">
                <button onclick="toggleDropdown()" class="user-icon">
                    <i class="fas fa-user-circle"></i>
                    <?php echo htmlspecialchars($datos_usuario['usuario']); ?>
                </button>
                <div id="userDropdown" class="dropdown-content">
                    <a href="#"><i class="fas fa-user"></i> Mi Perfil</a>
                    <a href="home.html"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                </div>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="principal.php">Inicio</a></li>
                <li class="dropdown">
                    <a href="#productos" class="dropbtn">Productos <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="productos.php?categoria=camisetas">Camisetas</a>
                        <a href="productos.php?categoria=tazas">Tazas</a>
                        <a href="productos.php?categoria=gorras">Gorras</a>
                        <a href="productos.php?categoria=stickers">Stickers</a>
                        <a href="productos.php?categoria=otros">Otros Productos</a>
                    </div>
                </li>
                <li><a href="subir_imagen.php">Subir imagen</a></li>
                <?php if ($rol === 'admin'): ?>
                    <li><a href="gestionar_productos.php">Gestionar Productos</a></li>
                    <li><a href="gestionar_usuarios.php">Gestionar Usuarios</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Agregar sección de bienvenida después del header -->
    <div class="welcome-section">
        <h2>¡Bienvenido, <?php echo htmlspecialchars($datos_usuario['usuario']); ?>!</h2>
        <p class="bienvenido">Explora nuestra selección de productos y servicios.</p>
    </div>

    <div class="product-container">
    <?php 
    if ($resultado && $resultado->num_rows > 0) {
        while ($producto = $resultado->fetch_assoc()) { 
    ?>
        <div class="product">
            <div class="product-image">
                <?php if (!empty($producto['imagen'])): ?>
                    <?php
                    $ruta_imagen = $producto['imagen'];
                    // Verifica si es una URL
                    if (filter_var($ruta_imagen, FILTER_VALIDATE_URL)) {
                        $imagen_src = $ruta_imagen;
                    } 
                    // Verifica si es una ruta absoluta
                    else if (file_exists($ruta_imagen)) {
                        $imagen_src = $ruta_imagen;
                    }
                    // Verifica si es una ruta relativa
                    else if (file_exists('../' . $ruta_imagen)) {
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
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                
                <p class="price">
                    Bs. <?php echo number_format($producto['precio'], 2); ?>
                </p>
                
                <p class="stock">
                    Stock: <?php echo htmlspecialchars($producto['stock']); ?> unidades
                </p>
                
                <?php if (!empty($producto['descripcion'])): ?>
                    <p class="description">
                        <?php echo htmlspecialchars($producto['descripcion']); ?>
                    </p>
                <?php endif; ?>
                
                <a href="detalle_producto.php?id=<?php echo $producto['id_productos']; ?>" 
                   class="button">Ver Detalles</a>
                   
                <?php if ($producto['stock'] <= 0): ?>
                    <p class="out-of-stock">Sin Stock</p>
                <?php endif; ?>
            </div>
        </div>
    <?php 
        }
    } else {
        echo "<p class='no-products'>No hay productos disponibles.</p>";
    } 
    ?>
</div>

    <footer>
        <div id="contact">
            <h3>Contacto</h3>
            <p>Email: contacto@orientaciongrafica.com</p>
            <p>Teléfono: (123) 456-7890</p>
        </div>
    </footer>
</body>
</html>

<?php 
$conexion->close(); 
?>