<?php require_once dirname(__DIR__) . '/config/database.php';
session_start(); 
da
if (!isset($_SESSION['user_id'])) {
    header("Location: inicio_de_sesion.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/BAUHAUS2025/css/arqui.ind.mob.css">
    <title>Pagina admin</title>
</head>
<body>

 <header class="top">
  <h1><a class="logo" href="/bauhaus2025/">BAUHAUS</a></h1>
  <nav>
    <a href="/bauhaus2025/subpaginas/arquitectura.html">Arquitectura</a>
    <a href="/bauhaus2025/subpaginas/industrial.php">Industrial</a>
    <a href="/bauhaus2025/subpaginas/mobiliario.html">Mobiliario</a>
    <a href="/bauhaus2025/subpaginas/galeria.html">Galería</a>
    <a href="/bauhaus2025/subpaginas/inicio_de_sesion.php">Inicio de sesión</a>
  </nav>
</header>

    <h2>Pagina accesible a los administradores</h2>
    <main>
    </main>
    <footer>
        <p>Contacto: info@bauhaus.com | Teléfono: +54 123456789</p>
        <p>Dirección: Calle de la Creatividad, 123, Weimar, Alemania</p>
        <p>&copy; 2024 Bauhaus Diseño. </p>
    </footer>
</body>
</html>