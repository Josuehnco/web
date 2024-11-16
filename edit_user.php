<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es admin
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener ID del usuario a editar
$id_usuario = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_usuario) {
    header("Location: gestionar_usuarios.php");
    exit();
}

// Obtener datos del usuario
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuarios = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    header("Location: gestionar_usuarios.php");
    exit();
}

$mensaje = '';
$tipo = '';

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_usuario = $conexion->real_escape_string($_POST['usuario']);
    $nuevo_correo = $conexion->real_escape_string($_POST['correo']);
    $nuevo_rol = $conexion->real_escape_string($_POST['rol']);
    $nueva_password = $conexion->real_escape_string($_POST['password']);

    // Validar correo
    if (!filter_var($nuevo_correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo electrónico inválido";
        $tipo = "error";
    } else {
        // Verificar si el correo existe (excluyendo el usuario actual)
        $check_email = $conexion->prepare("SELECT id_usuarios FROM usuarios WHERE correo = ? AND id_usuarios != ?");
        $check_email->bind_param("si", $nuevo_correo, $id_usuario);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if ($result->num_rows > 0) {
            $mensaje = "Este correo ya está registrado por otro usuario";
            $tipo = "error";
        } else {
            // Preparar la consulta base
            if (!empty($nueva_password)) {
                // Si se proporcionó una nueva contraseña
                $sql = "UPDATE usuarios SET usuario = ?, password = ?, correo = ?, rol = ? WHERE id_usuarios = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("ssssi", $nuevo_usuario, $nueva_password, $nuevo_correo, $nuevo_rol, $id_usuario);
            } else {
                // Si no se cambió la contraseña
                $sql = "UPDATE usuarios SET usuario = ?, correo = ?, rol = ? WHERE id_usuarios = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sssi", $nuevo_usuario, $nuevo_correo, $nuevo_rol, $id_usuario);
            }
            
            if ($stmt->execute()) {
                $mensaje = "Usuario actualizado correctamente";
                $tipo = "success";
                
                // Actualizar los datos mostrados
                $usuario['usuario'] = $nuevo_usuario;
                $usuario['correo'] = $nuevo_correo;
                $usuario['rol'] = $nuevo_rol;
            } else {
                $mensaje = "Error al actualizar el usuario: " . $conexion->error;
                $tipo = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="edit_añad.css">
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-usuario">
            <div class="form-group">
                <label for="usuario">Nombre de Usuario:</label>
                <input type="text" id="usuario" name="usuario" required 
                       minlength="4" maxlength="50"
                       value="<?php echo htmlspecialchars($usuario['usuario']); ?>">
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña: (Dejar en blanco para mantener la actual)</label>
                <input type="password" id="password" name="password" 
                       minlength="6">
                <small>Mínimo 6 caracteres</small>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required
                       value="<?php echo htmlspecialchars($usuario['correo']); ?>">
            </div>

            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="usuario" <?php echo $usuario['rol'] === 'usuario' ? 'selected' : ''; ?>>
                        Usuario
                    </option>
                    <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>
                        Administrador
                    </option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="gestionar_usuarios.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            if (password !== '' && password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
            }
        });
    </script>
</body>
</html>

<?php $conexion->close(); ?>