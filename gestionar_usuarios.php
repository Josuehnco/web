<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es admin
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Manejar eliminación de usuario
if (isset($_POST['eliminar_usuario'])) {
    $id = $_POST['id_usuario'];
    $sql_eliminar = "DELETE FROM usuarios WHERE id_usuarios = ?";
    $stmt = $conexion->prepare($sql_eliminar);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $mensaje = "Usuario eliminado correctamente";
        $tipo = "success";
    } else {
        $mensaje = "Error al eliminar el usuario";
        $tipo = "error";
    }
}

// Manejar eliminación de personal
if (isset($_POST['eliminar_personal'])) {
    $id = $_POST['id_personal'];
    $sql_eliminar = "DELETE FROM personal WHERE id_personal = ?";
    $stmt = $conexion->prepare($sql_eliminar);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $mensaje = "Personal eliminado correctamente";
        $tipo = "success";
    } else {
        $mensaje = "Error al eliminar el personal";
        $tipo = "error";
    }
}

// Consultar usuarios
$sql_usuarios = "SELECT * FROM usuarios ORDER BY usuario ASC";
$resultado_usuarios = $conexion->query($sql_usuarios);

// Consultar personal
$sql_personal = "SELECT * FROM personal ORDER BY nombre ASC";
$resultado_personal = $conexion->query($sql_personal);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios y Personal</title>
    <link rel="stylesheet" href="gestionar_usuarios.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Usuarios y Personal</h1>
            <div class="header-buttons">
                <a href="añadir_usuario.php" class="btn btn-primary">Nuevo Usuario</a>
                <a href="añadir_personal.php" class="btn btn-primary">Nuevo Personal</a>
                <a href="principal.php" class="btn btn-secondary">Volver al Inicio</a>
            </div>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Sección de Usuarios -->
        <section class="usuarios-section">
            <h2>Usuarios</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($usuario = $resultado_usuarios->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                <td class="acciones">
                                    <a href="edit_user.php?id=<?php echo $usuario['id_usuarios']; ?>" 
                                       class="btn btn-editar">Editar</a>
                                    <form action="" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_usuario" 
                                               value="<?php echo $usuario['id_usuarios']; ?>">
                                        <button type="submit" name="eliminar_usuario" 
                                                class="btn btn-eliminar"
                                                onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Sección de Personal -->
        <section class="personal-section">
            <h2>Personal</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>CI</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($personal = $resultado_personal->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($personal['ci']); ?></td>
                                <td><?php echo htmlspecialchars($personal['nombre']); ?></td>
                                <td>
                                    <?php 
                                        echo htmlspecialchars($personal['apellido_p'] . ' ' . 
                                             $personal['apellido_m']); 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($personal['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($personal['gmail']); ?></td>
                                <td class="acciones">
                                    <a href="edit_personal.php?id=<?php echo $personal['id_personal']; ?>" 
                                       class="btn btn-editar">Editar</a>
                                    <form action="" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_personal" 
                                               value="<?php echo $personal['id_personal']; ?>">
                                        <button type="submit" name="eliminar_personal" 
                                                class="btn btn-eliminar"
                                                onclick="return confirm('¿Estás seguro de eliminar este personal?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>

<?php $conexion->close(); ?>