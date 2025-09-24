<?php
require_once dirname(__DIR__) . '/config/database.php';

$registrado = isset($_GET['registrado']) && $_GET['registrado'] == '1';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inicio de sesión</title>

  <link rel="stylesheet" href="/bauhaus2025/css/index.css">
  <link rel="stylesheet" href="/bauhaus2025/css/auth.css">
</head>

<body class="auth">

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

<main class="auth-main">
  <section class="auth-wrap">
    <div class="auth-card">
      <h2>Iniciar sesión</h2>

      <?php if ($registrado): ?>
        <p class="alert success">¡Registro exitoso! Ahora podés iniciar sesión.</p>
      <?php endif; ?>

      <form class="auth-form" method="post" action="/bauhaus2025/subpaginas/inicio_de_sesion.php">
        <div class="field">
          <label for="correo">Email</label>
          <input class="input" type="email" id="correo" name="correo" placeholder="tu@email.com" required>
        </div>

        <div class="field">
          <label for="password">Contraseña</label>
          <input class="input" type="password" id="password" name="password" placeholder="••••••••" required>
        </div>

        <button class="btn btn--primary w-100" type="submit">Iniciar sesión</button>
      </form>

      <p class="auth-alt">
        ¿No tienes cuenta?
        <a class="link" href="/bauhaus2025/subpaginas/registro.php">Regístrate aquí</a>
      </p>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="footer-content">
    <p>Contacto: <a href="mailto:info@bauhaus.com">info@bauhaus.com</a> | Teléfono: +54 123456789</p>
    <p>Dirección: Calle de la Creatividad, 123, Weimar, Alemania</p>

    <div class="social-media">
      <a href="https://www.instagram.com/bauhausdesign_studio/" target="_blank" rel="noopener">
        <img src="/bauhaus2025/logos/instagram.png" alt="Instagram">
      </a>
      <a href="https://www.facebook.com/bauhausthebandofficial/" target="_blank" rel="noopener">
        <img src="/bauhaus2025/logos/facebook.png" alt="Facebook">
      </a>
      <a href="https://twitter.com/bauhausmovement" target="_blank" rel="noopener">
        <img src="/bauhaus2025/logos/twitter.png" alt="Twitter/X">
      </a>
    </div>

    <p>&copy; 2024 Bauhaus Diseño.</p>
  </div>
</footer>


</body>
</html>

