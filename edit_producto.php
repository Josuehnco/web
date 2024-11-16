<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es admin
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Obtener el ID del producto
$id_producto = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_producto) {
    header("Location: gestionar_productos.php");
    exit();
}

// Obtener datos del producto
$sql = "SELECT * FROM productos WHERE id_productos = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

// Obtener lista de proveedores
$sql_proveedores = "SELECT idprov, nombre FROM proveedores ORDER BY nombre";
$resultado_proveedores = $conexion->query($sql_proveedores);

// Procesar el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_proveedores = $_POST['id_proveedores'];
    $enlace = $_POST['enlace'];
    
    // Manejo de la imagen
    $ruta_imagen = $producto['imagen']; // Mantener imagen actual por defecto
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        
        // Verificar tipo de archivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imagen['type'], $tipos_permitidos)) {
            $mensaje = "Tipo de archivo no permitido. Solo se permiten JPG, PNG y GIF.";
            $tipo = "error";
        } else {
            // Usar la ruta original del archivo o copiar a libreria_imagenes
            $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
            $nombre_archivo = uniqid() . '.' . $extension;
            $ruta_destino = '../libreria_imagenes/' . $nombre_archivo;
            
            if (move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
                // Eliminar imagen anterior si existe y está en nuestra carpeta
                if (!empty($producto['imagen']) && strpos($producto['imagen'], 'libreria_imagenes/') === 0) {
                    $ruta_anterior = '../' . $producto['imagen'];
                    if (file_exists($ruta_anterior)) {
                        unlink($ruta_anterior);
                    }
                }
                $ruta_imagen = 'libreria_imagenes/' . $nombre_archivo;
            } else {
                $mensaje = "Error al mover el archivo";
                $tipo = "error";
            }
        }
    }
    
    // Actualizar en la base de datos
    $sql_actualizar = "UPDATE productos SET 
                      nombre = ?, 
                      descripcion = ?, 
                      imagen = ?, 
                      enlace = ?, 
                      precio = ?, 
                      stock = ?, 
                      id_proveedores = ? 
                      WHERE id_productos = ?";
                      
    $stmt = $conexion->prepare($sql_actualizar);
    $stmt->bind_param("ssssdiis", 
        $nombre, 
        $descripcion, 
        $ruta_imagen, 
        $enlace, 
        $precio, 
        $stock, 
        $id_proveedores, 
        $id_producto
    );
    
    if ($stmt->execute()) {
        $mensaje = "Producto actualizado correctamente";
        $tipo = "success";
        
        // Actualizar los datos del producto en la página
        $sql = "SELECT * FROM productos WHERE id_productos = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();
    } else {
        $mensaje = "Error al actualizar el producto";
        $tipo = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="editar_producto.css">
</head>
<body>
    <div class="container">
        <h1>Editar Producto</h1>
        
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" 
                       value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php 
                    echo htmlspecialchars($producto['descripcion']); 
                ?></textarea>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen actual:</label>
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" 
                         alt="Imagen del producto" class="imagen-preview">
                <?php endif; ?>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </div>

            <div class="form-group">
                <label for="enlace">Enlace:</label>
                <input type="url" id="enlace" name="enlace" 
                       value="<?php echo htmlspecialchars($producto['enlace']); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" id="precio" name="precio" step="0.01" 
                           value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" 
                           value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="id_proveedores">Proveedor:</label>
                <select id="id_proveedores" name="id_proveedores" required>
                    <?php while ($proveedor = $resultado_proveedores->fetch_assoc()): ?>
                        <option value="<?php echo $proveedor['idprov']; ?>"
                            <?php echo ($proveedor['idprov'] == $producto['id_proveedores']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($proveedor['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="gestionar_productos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // Preview de imagen
        document.getElementById('imagen').onchange = function(e) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.imagen-preview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const newPreview = document.createElement('img');
                    newPreview.src = e.target.result;
                    newPreview.classList.add('imagen-preview');
                    document.querySelector('.form-group').appendChild(newPreview);
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        };
    </script>
</body>
</html>

<?php $conexion->close(); ?>