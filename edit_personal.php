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

// Obtener el ID del personal
$id_personal = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_personal) {
    header("Location: gestionar_usuarios.php");
    exit();
}

// Obtener datos del personal
$stmt = $conexion->prepare("SELECT * FROM personal WHERE id_personal = ?");
$stmt->bind_param("i", $id_personal);
$stmt->execute();
$resultado = $stmt->get_result();
$personal = $resultado->fetch_assoc();

if (!$personal) {
    header("Location: gestionar_usuarios.php");
    exit();
}

$mensaje = '';
$tipo = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y limpiar datos
    $ci = $conexion->real_escape_string($_POST['ci']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido_p = $conexion->real_escape_string($_POST['apellido_p']);
    $apellido_m = $conexion->real_escape_string($_POST['apellido_m']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $gmail = $conexion->real_escape_string($_POST['gmail']);

    // Validar correo
    if (!filter_var($gmail, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo electrónico inválido";
        $tipo = "error";
    } else {
        // Verificar si el correo existe (excluyendo el personal actual)
        $check_email = $conexion->prepare("SELECT id_personal FROM personal WHERE gmail = ? AND id_personal != ?");
        $check_email->bind_param("si", $gmail, $id_personal);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if ($result->num_rows > 0) {
            $mensaje = "Este correo ya está registrado por otro personal";
            $tipo = "error";
        } else {
            // Actualizar datos
            $sql = "UPDATE personal SET 
                   ci = ?, 
                   nombre = ?, 
                   apellido_p = ?, 
                   apellido_m = ?, 
                   telefono = ?, 
                   gmail = ? 
                   WHERE id_personal = ?";
            
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssssi", 
                $ci, 
                $nombre, 
                $apellido_p, 
                $apellido_m, 
                $telefono, 
                $gmail, 
                $id_personal
            );
            
            if ($stmt->execute()) {
                $mensaje = "Personal actualizado correctamente";
                $tipo = "success";
                
                // Actualizar datos mostrados
                $personal = [
                    'ci' => $ci,
                    'nombre' => $nombre,
                    'apellido_p' => $apellido_p,
                    'apellido_m' => $apellido_m,
                    'telefono' => $telefono,
                    'gmail' => $gmail
                ];
            } else {
                $mensaje = "Error al actualizar el personal: " . $conexion->error;
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
    <title>Editar Personal</title>
    <link rel="stylesheet" href="edit_añad.css">
</head>
<body>
    <div class="container">
        <h1>Editar Personal</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-personal">
            <div class="form-group">
                <label for="ci">CI:</label>
                <input type="text" id="ci" name="ci" required 
                       value="<?php echo htmlspecialchars($personal['ci']); ?>">
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required 
                       value="<?php echo htmlspecialchars($personal['nombre']); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="apellido_p">Apellido Paterno:</label>
                    <input type="text" id="apellido_p" name="apellido_p" required 
                           value="<?php echo htmlspecialchars($personal['apellido_p']); ?>">
                </div>

                <div class="form-group">
                    <label for="apellido_m">Apellido Materno:</label>
                    <input type="text" id="apellido_m" name="apellido_m" required 
                           value="<?php echo htmlspecialchars($personal['apellido_m']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required 
                       value="<?php echo htmlspecialchars($personal['telefono']); ?>">
            </div>

            <div class="form-group">
                <label for="gmail">Correo Electrónico:</label>
                <input type="email" id="gmail" name="gmail" required 
                       value="<?php echo htmlspecialchars($personal['gmail']); ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="gestionar_usuarios.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php $conexion->close(); ?>