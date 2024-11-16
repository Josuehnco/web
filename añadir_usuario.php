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

$mensaje = '';
$tipo = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y limpiar datos
    $usuario = $conexion->real_escape_string($_POST['usuario']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Ahora usando hash seguro
    $correo = $conexion->real_escape_string($_POST['correo']);
    $rol = $conexion->real_escape_string($_POST['rol']);
    
    // Validar correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo electrónico inválido";
        $tipo = "danger";
    } else {
        // Verificar si el correo ya existe
        $check_email = $conexion->prepare("SELECT id_usuarios FROM usuarios WHERE correo = ?");
        $check_email->bind_param("s", $correo);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if ($result->num_rows > 0) {
            $mensaje = "Este correo ya está registrado";
            $tipo = "danger";
        } else {
            // Insertar nuevo usuario
            $sql = "INSERT INTO usuarios (usuario, password, correo, rol) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssss", $usuario, $password, $correo, $rol);
            
            if ($stmt->execute()) {
                $mensaje = "Usuario agregado correctamente";
                $tipo = "success";
            } else {
                $mensaje = "Error al agregar el usuario: " . $conexion->error;
                $tipo = "danger";
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
    <title>Agregar Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <style>
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-person-plus-fill me-2"></i>Agregar Nuevo Usuario
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <?php if ($mensaje): ?>
                            <div class="alert alert-<?php echo $tipo; ?> alert-dismissible fade show" role="alert">
                                <?php echo $mensaje; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">
                                    <i class="bi bi-person me-2"></i>Nombre de Usuario
                                </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       required minlength="4" maxlength="50">
                                <div class="invalid-feedback">
                                    El nombre de usuario debe tener entre 4 y 50 caracteres
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key me-2"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" 
                                           name="password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword()">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control" id="correo" 
                                       name="correo" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese un correo electrónico válido
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="rol" class="form-label">
                                    <i class="bi bi-shield-lock me-2"></i>Rol
                                </label>
                                <select class="form-select" id="rol" name="rol" required>
                                    <option value="">Seleccione un rol</option>
                                    <option value="usuario">Usuario</option>
                                    <option value="admin">Administrador</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione un rol
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="gestionar_usuarios.php" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-arrow-left me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus-fill me-2"></i>Agregar Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Validación del formulario
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.querySelector('.bi-eye, .bi-eye-slash');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
    </script>
</body>
</html>

<?php $conexion->close(); ?>