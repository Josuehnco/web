<?php
// Configuración de la conexión a la base de datos
$conexion = mysqli_connect("localhost", "josue", "1234", "centro de estampados", "3306");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Array con los usuarios y sus contraseñas originales en texto plano
$usuarios = [
    ['id' => 1, 'contraseña' => 'password123'],
    ['id' => 2, 'contraseña' => 'password707'],
    ['id' => 3, 'contraseña' => 'password789'],
    ['id' => 4, 'contraseña' => 'password101'],
    ['id' => 6, 'contraseña' => 'password303'],
    ['id' => 7, 'contraseña' => 'password404'],
    ['id' => 8, 'contraseña' => 'password505'],
    ['id' => 9, 'contraseña' => 'password606'],
    ['id' => 10, 'contraseña' => 'password707'],
    ['id' => 11, 'contraseña' => 'password111']
];

// Actualizar las contraseñas en texto plano en la base de datos
foreach ($usuarios as $usuario) {
    $contraseña_plana = $usuario['contraseña'];
    $sql = "UPDATE usuarios SET contraseña = '$contraseña_plana' WHERE id_usuarios = " . $usuario['id'];
    if ($conexion->query($sql) === TRUE) {
        echo "Contraseña del usuario " . $usuario['id'] . " actualizada correctamente.<br>";
    } else {
        echo "Error al actualizar la contraseña del usuario " . $usuario['id'] . ": " . $conexion->error . "<br>";
    }
}

$conexion->close();