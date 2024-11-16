<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Aquí puedes procesar los datos del formulario, como guardarlos en una base de datos o enviarlos por correo.
    echo "Gracias, $name. Hemos recibido tu mensaje: '$message'. Nos pondremos en contacto contigo a través del correo: $email.";
}
?>