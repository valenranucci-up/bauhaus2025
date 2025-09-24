<?php
// Estilos y fondo igual que login
require_once dirname(__DIR__) . '/config/database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = trim($_POST['usuario'] ?? '');
  $correo  = trim($_POST['correo']  ?? '');
  $pass1   = $_POST['password']     ?? '';
  $pass2   = $_POST['password2']    ?? '';

  if ($usuario === '' || $correo === '' || $pass1 === '' || $pass2 === '') {
    $errors[] = 'Completá todos los campos.';
  }
  if ($pass1 !== $pass2) {
    $errors[] = 'Las contraseñas no coinciden.';
  }

  if (!$errors) {
    try {
      // ¿Ya existe?
      $stmt = $pdo->prepare('SELECT 1 FROM usuarios WHERE correo = ? OR usuario = ? LIMIT 1');
      $stmt->execute([$correo, $usuario]);
      if ($stmt->fetch()) {
        $errors[] = 'Ese usuario o correo ya existe.';
      }
    } catch (PDOException $e) {
      $errors[] = 'Error verificando usuario: ' . $e->getMessage();
    }
  }

  if (!$errors) {
    try {
      // Nota: tu tabla usa MD5 (ver admin en tu dump). Mantenemos compatibilidad.
      $hash = md5($pass1);
      $stmt = $pdo->prepare('INSERT INTO usuarios (usuario, password, correo) VALUES (?, ?, ?)');
      $stmt->execute([$usuario, $hash, $correo]);

      // Redirige al login con aviso
      header('Location: /bauhaus2025/subpaginas/inicio_de_sesion.php?registrado=1');
      exit;
    } catch (PDOException $e) {
      $errors[] = 'Error guardando: ' . $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registro</title>

  <!-- CSS base del sitio + estilos auth (los mismos que login) -->
  <link rel="stylesheet" href="/bauhaus2025/css/index.css">
  <link rel="stylesheet" href="/bauhaus2025/css/auth.css">
</head>
<body class="auth"><!-- misma clase que login para usar el mismo fondo -->

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
      <h2>Crear cuenta</h2>

      <?php if (!empty($errors)): ?>
        <div class="alert error">
          <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form class="auth-form" method="post" action="/bauhaus2025/subpaginas/registro.php" novalidate>
        <div class="field">
          <label for="usuario">Usuario</label>
          <input class="input" type="text" id="usuario" name="usuario"
                 value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>" required>
        </div>

        <div class="field">
          <label for="correo">Email</label>
          <input class="input" type="email" id="correo" name="correo"
                 value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" required>
        </div>

        <div class="field">
          <label for="password">Contraseña</label>
          <input class="input" type="password" id="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="field">
          <label for="password2">Repetir contraseña</label>
          <input class="input" type="password" id="password2" name="password2" placeholder="••••••••" required>
        </div>

        <button class="btn btn--primary w-100" type="submit">Registrarse</button>
      </form>

      <p class="auth-alt">
        ¿Ya tenés cuenta?
        <a class="link" href="/bauhaus2025/subpaginas/inicio_de_sesion.php">Iniciar sesión</a>
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

