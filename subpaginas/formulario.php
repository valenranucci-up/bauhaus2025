<?php require_once dirname(__DIR__) . '/config/database.php';
$nombre = $_POST['fullname']; 
$email = $_POST['email'];
$telefono = $_POST['phone'];
$mensaje = $_POST['message'];

echo "<h2>Datos del formulario</h2>";
echo "<p>Nombre: $nombre</p>";
echo "<p>Email: $email</p>";
echo "<p>Teléfono: $telefono</p>";
echo "<p>Mensaje: $mensaje</p>";
?>
