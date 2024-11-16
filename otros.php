<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios y Productos</title>
    <link rel="stylesheet" href="css/otros.css"> <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="styles.css"> <!-- Enlace a estilos adicionales -->
</head>
<body>
    <h1>Lista de Usuarios</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Opciones</th>
        </tr>
        <?php
        // Configuración de la conexión a la base de datos
        $conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");
        
        // Verificar conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Consulta para obtener todos los usuarios
        $sql = "SELECT id_usuarios, usuario FROM usuarios";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            // Mostrar datos de cada fila
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id_usuarios'] . "</td>
                        <td>" . htmlspecialchars($row['usuario']) . "</td>
                        <td>
                            <form action='edit_user.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id_usuarios' value='" . $row['id_usuarios'] . "'>
                                <button type='submit' class='button'>Editar</button>
                            </form>
                            <form action='delete_user.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id_usuarios' value='" . $row['id_usuarios'] . "'>
                                <button type='submit' class='button'>Borrar</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay usuarios registrados.</td></tr>";
        }

        // Cerrar conexión
        $conexion->close();
        ?>
    </table>

    <div class="button-container">
        <a href="añadir.php" class="button">Añadir Nuevo Usuario</a>
    </div>

    <h1>Lista de Productos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Opciones</th>
        </tr>
        <?php
        // Nueva conexión para productos
        $conexion = mysqli_connect("localhost", "josue", "1234", "libreria_josu", "3306");
        
        // Verificar conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Consulta para obtener todos los productos
        $sql = "SELECT id_productos, nombre FROM productos";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            // Mostrar datos de cada fila
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id_productos'] . "</td>
                        <td>" . htmlspecialchars($row['nombre']) . "</td>
                        <td>
                            <form action='edit_producto.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id_productos' value='" . $row['id_productos'] . "'>
                                <button type='submit' class='button'>Editar</button>
                            </form>
                            <form action='delete_producto.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id_productos' value='" . $row['id_productos'] . "'>
                                <button type='submit' class='button'>Borrar</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay productos registrados.</td></tr>";
        }

        // Cerrar conexión
        $conexion->close();
        ?>
    </table>

    <div class="button-container">
        <a href="añadir_productos.php" class="button">Añadir Nuevo Producto</a>
    </div>
 
    <h1>Lista de Personal</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Opciones</th>
        </tr>
        <?php
        // Nueva conexión para productos
        $conexion = mysqli_connect("localhost", "josue", "1234", "libreria_josu", "3306");
        
        // Verificar conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Consulta para obtener todos los productos
        $sql = "SELECT id_personal, nombre FROM personal";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            // Mostrar datos de cada fila
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id_personal'] . "</td>
                        <td>" . htmlspecialchars($row['nombre']) . "</td>
                        <td>
                            <form action='edit_personal.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id_personal' value='" . $row['id_personal'] . "'>
                                <button type='submit' class='button'>Editar</button>
                            </form>
                            <form action='delete_personal.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id_personal' value='" . $row['id_personal'] . "'>
                                <button type='submit' class='button'>Borrar</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay personales registrados.</td></tr>";
        }

        // Cerrar conexión
        $conexion->close();
        ?>
    </table>

    <div class="button-container">
        <a href="añadir_personal.php" class="button">Añadir Nuevo Personal</a>
        <a href="home.php" class="button">Volver a Inicio</a>
    </div>

    
</body>
</html>

</html>

