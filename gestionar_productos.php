<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es admin
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Manejar eliminación de producto
if (isset($_POST['eliminar'])) {
    $id = $_POST['id_producto'];
    $sql_eliminar = "DELETE FROM productos WHERE id_productos = ?";
    $stmt = $conexion->prepare($sql_eliminar);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $mensaje = "Producto eliminado correctamente";
        $tipo = "success";
    } else {
        $mensaje = "Error al eliminar el producto";
        $tipo = "error";
    }
}

// Consultar todos los productos con información del proveedor
$sql = "SELECT p.*, pr.nombre as proveedor_nombre 
        FROM productos p 
        LEFT JOIN proveedores pr ON p.id_proveedores = pr.idprov 
        ORDER BY p.nombre ASC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="gestionar.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Productos</h1>
            <div class="header-buttons">
                <a href="añadir_productos.php" class="btn btn-primary">Nuevo Producto</a>
                <a href="principal.php" class="btn btn-secondary">Volver al Inicio</a>
            </div>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="productos-grid">
            <?php while ($producto = $resultado->fetch_assoc()): ?>
                <div class="producto-card">
                    <div class="producto-imagen">
                        <?php if (!empty($producto['imagen'])): ?>
                            <img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <?php else: ?>
                            <div class="no-imagen">Sin imagen</div>
                        <?php endif; ?>
                    </div>

                    <div class="producto-info">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        
                        <div class="info-grupo">
                            <label>Precio:</label>
                            <span>Bs.<?php echo number_format($producto['precio'], 2); ?></span>
                        </div>

                        <div class="info-grupo">
                            <label>Stock:</label>
                            <span><?php echo htmlspecialchars($producto['stock']); ?> unidades</span>
                        </div>

                        <div class="info-grupo">
                            <label>Proveedor:</label>
                            <span><?php echo htmlspecialchars($producto['proveedor_nombre'] ?? 'No asignado'); ?></span>
                        </div>

                        <?php if (!empty($producto['descripcion'])): ?>
                            <div class="info-grupo">
                                <label>Descripción:</label>
                                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($producto['enlace'])): ?>
                            <div class="info-grupo">
                                <label>Enlace:</label>
                                <a href="<?php echo htmlspecialchars($producto['enlace']); ?>" 
                                   target="_blank" class="enlace-producto">Ver producto</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="producto-acciones">
                        <a href="edit_producto.php?id=<?php echo $producto['id_productos']; ?>" 
                           class="btn btn-editar">Editar</a>
                        <form action="" method="POST" class="form-eliminar">
                            <input type="hidden" name="id_producto" 
                                   value="<?php echo $producto['id_productos']; ?>">
                            <button type="submit" name="eliminar" class="btn btn-eliminar"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php $conexion->close(); ?>