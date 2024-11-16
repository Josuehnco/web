<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Obtener el ID del usuario
$correo = $_SESSION['correo'];
$sql_usuarios = "SELECT id_usuarios FROM usuarios WHERE correo = ?";
$stmt_usuario = $conexion->prepare($sql_usuarios);
$stmt_usuario->bind_param("s", $correo);
$stmt_usuario->execute();
$resultado_usuario = $stmt_usuario->get_result();
$usuario = $resultado_usuario->fetch_assoc();
$id_usuarios = $usuario['id_usuarios'];

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen'];
        $descripcion = $_POST['descripcion'];
        $tamano = $_POST['tamano'];
        
        // Validar campos
        if (empty($descripcion) || empty($tamano)) {
            $mensaje = "Por favor, completa todos los campos.";
            $tipo = "error";
        } else {
            $nombre_archivo = $archivo['name'];
            $tipo_archivo = $archivo['type'];
            $temp_archivo = $archivo['tmp_name'];
            
            $permitidos = array('image/jpeg', 'image/png', 'image/gif');
            if (!in_array($tipo_archivo, $permitidos)) {
                $mensaje = "Solo se permiten archivos JPG, PNG y GIF.";
                $tipo = "error";
            } else {
                $directorio_destino = "uploads/estampados/";
                if (!file_exists($directorio_destino)) {
                    mkdir($directorio_destino, 0777, true);
                }
                
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                $nombre_unico = uniqid() . "." . $extension;
                $ruta_destino = $directorio_destino . $nombre_unico;
                
                if (move_uploaded_file($temp_archivo, $ruta_destino)) {
                    $sql = "INSERT INTO pedidos_estampados (id_usuarios, imagen, descripcion, tamano, fecha_subida) 
                            VALUES (?, ?, ?, ?, NOW())";
                    
                    $stmt = $conexion->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("isss", $id_usuarios, $ruta_destino, $descripcion, $tamano);
                        
                        if ($stmt->execute()) {
                            $mensaje = "¡Pedido enviado correctamente! Nos pondremos en contacto contigo por WhatsApp.";
                            $tipo = "success";
                        } else {
                            $mensaje = "Error al guardar el pedido.";
                            $tipo = "error";
                        }
                    }
                }
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
    <title>Subir Imagen para Estampado</title>
    <link rel="stylesheet" href="subirimg.css">
</head>
<body>
    <div class="upload-container">
        <h2>Suba la imagen que desea que estampemos</h2>
        
        <?php if (isset($mensaje)): ?>
            <div class="message <?php echo $tipo; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="upload-area" onclick="document.getElementById('imagen').click();">
                <p>Haz clic aquí para seleccionar tu imagen</p>
                <input type="file" id="imagen" name="imagen" accept="image/*" 
                       onchange="previewImage(this);" required>
            </div>
            
            <img id="preview" src="#" alt="Vista previa">
            
            <div class="form-group">
                <label for="descripcion">Descripción de cómo quieres el estampado:</label>
                <textarea id="descripcion" name="descripcion" rows="4" 
                          placeholder="Describe los colores, detalles o modificaciones que deseas..." required></textarea>
            </div>

            <div class="form-group">
                <label for="tamano">Tamaño deseado:</label>
                <input type="text" id="tamano" name="tamano" 
                       placeholder="Ej: 20cm x 30cm" required>
            </div>

            <button type="submit" class="submit-btn">Enviar Pedido</button>
            <a href="principal.php">Volver a Pantalla principal</a>
        </form>
    </div>

    <a href="https://wa.me/591XXXXXXXX" class="whatsapp-button" target="_blank">
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.784 23.456l4.5-1.442C7.236 23.306 9.546 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm-1.5 5.25c-.75 0-1.5.75-1.5 1.5s.75 1.5 1.5 1.5 1.5-.75 1.5-1.5-.75-1.5-1.5-1.5zm3 13.5c-2.25 0-4.5-.75-6-2.25l-3 1.5 1.5-3C4.5 13.5 3.75 11.25 3.75 9c0-4.5 3.75-8.25 8.25-8.25s8.25 3.75 8.25 8.25-3.75 8.25-8.25 8.25z'/%3E%3C/svg%3E" 
             class="whatsapp-icon" alt="WhatsApp">
        Consultar por WhatsApp
    </a>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>