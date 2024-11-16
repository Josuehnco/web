<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es admin
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Obtener lista de proveedores para el select
$sql_proveedores = "SELECT idprov, nombre FROM proveedores ORDER BY nombre";
$resultado_proveedores = $conexion->query($sql_proveedores);

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $id_proveedores = intval($_POST['id_proveedores']);
    $enlace = $conexion->real_escape_string($_POST['enlace']);
    
    // Manejo de la imagen
    $ruta_imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        
        // Verificar tipo de archivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imagen['type'], $tipos_permitidos)) {
            $mensaje = "Tipo de archivo no permitido. Solo se permiten JPG, PNG y GIF.";
            $tipo = "error";
        } else {
            // Usar la ruta original del archivo
            $ruta_imagen = $imagen['tmp_name'];
            
            // O copiar a una ubicación específica
            $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
            $nombre_archivo = uniqid() . '.' . $extension;
            $ruta_destino = '../libreria_imagenes/' . $nombre_archivo;
            
            if (move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
                $ruta_imagen = 'libreria_imagenes/' . $nombre_archivo;
            } else {
                $mensaje = "Error al mover el archivo";
                $tipo = "error";
            }
        }
    }
    
    if (!isset($mensaje)) {
        // Insertar en la base de datos
        $sql = "INSERT INTO productos (nombre, descripcion, imagen, enlace, precio, stock, id_proveedores) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssdii", 
            $nombre, 
            $descripcion, 
            $ruta_imagen, 
            $enlace, 
            $precio, 
            $stock, 
            $id_proveedores
        );
        
        if ($stmt->execute()) {
            $mensaje = "Producto añadido correctamente";
            $tipo = "success";
            // Opcional: redirigir después de añadir
            // header("Location: gestionar_productos.php");
            // exit();
        } else {
            $mensaje = "Error al añadir el producto: " . $conexion->error;
            $tipo = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
    <link rel="stylesheet" href="editar_producto.css">
</head>
<body>
    <div class="container">
        <h1>Añadir Nuevo Producto</h1>
        
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
                <div id="imagen-preview"></div>
            </div>

            <div class="form-group">
                <label for="enlace">Enlace:</label>
                <input type="url" id="enlace" name="enlace">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" id="precio" name="precio" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" required>
                </div>
            </div>

            <div class="form-group">
                <label for="id_proveedores">Proveedor:</label>
                <select id="id_proveedores" name="id_proveedores" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php while ($proveedor = $resultado_proveedores->fetch_assoc()): ?>
                        <option value="<?php echo $proveedor['idprov']; ?>">
                            <?php echo htmlspecialchars($proveedor['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Añadir Producto</button>
                <a href="gestionar_productos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // Preview de imagen
        document.getElementById('imagen').onchange = function(e) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagen-preview');
                preview.innerHTML = `<img src="${e.target.result}" class="imagen-preview">`;
            }
            reader.readAsDataURL(e.target.files[0]);
        };
    </script>
</body>
</html>

<?php $conexion->close(); ?>