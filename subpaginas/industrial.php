<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$cfg = dirname(__DIR__) . '/config/database.php';
if (!is_file($cfg)) { die('No encuentro el archivo de conexión'); }
require_once $cfg;
if (!isset($pdo) || !($pdo instanceof PDO)) {
  $pdo = new PDO("mysql:host=localhost;dbname=bauhaus2025;charset=utf8mb4", 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
}
function img_path($raw){
  $raw = (string)$raw;
  if (strpos($raw,'../')===0) return '/bauhaus2025/'.substr($raw,3);
  if ($raw!=='' && $raw[0]!=='/') return '/bauhaus2025/'.$raw;
  return $raw ?: '/bauhaus2025/imagenes/placeholder.png';
}
function norm($s){
  $s = mb_strtolower((string)$s,'UTF-8');
  return strtr($s, ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n']);
}

// Búsqueda
$search = trim($_GET['search'] ?? '');
$minlen = 2;

// Artistas (DB)
$db_error = '';
$artistas = [];
try {
  if ($search !== '' && mb_strlen($search,'UTF-8') >= $minlen) {
    $stmt = $pdo->prepare("
      SELECT id_artista, artista, imagen, descripcion
      FROM artistas
      WHERE artista LIKE :q OR descripcion LIKE :q
      ORDER BY artista ASC
    ");
    $stmt->bindValue(':q', "%{$search}%");
  } else {
    $stmt = $pdo->prepare("SELECT id_artista, artista, imagen, descripcion FROM artistas ORDER BY artista ASC");
  }
  $stmt->execute();
  $artistas = $stmt->fetchAll();
} catch (PDOException $e) { $db_error = $e->getMessage(); }

// Productos (estáticos)
$productos = [
  [
    'nombre'=>'Set de Mesa',
    'img'=>'/bauhaus2025/imagenes/set de mesa.jpg',
    'desc'=>'Inventado por Marianne Brandt, se caracteriza por su diseño minimalista y el uso de materiales como el metal y el vidrio.'
  ],
  [
    'nombre'=>'Lámpara Wagenfeld',
    'img'=>'/bauhaus2025/imagenes/lampara wagenfeld.jpg',
    'desc'=>'Diseñada por Wilhelm Wagenfeld, con un lenguaje simple y elegante, utilizando vidrio y metal.'
  ],
  [
    'nombre'=>'Silla Cesca',
    'img'=>'/bauhaus2025/imagenes/silla cesca.jpg',
    'desc'=>'Creada por Marcel Breuer en 1928: acero tubular con asiento de mimbre o tapizado; confort y estética moderna.'
  ],
];
$productos_filtrados = $productos;
if ($search !== '' && mb_strlen($search,'UTF-8') >= $minlen) {
  $q = norm($search);
  $productos_filtrados = array_values(array_filter($productos, function($p) use($q){
    return strpos(norm(($p['nombre']??'').' '.($p['desc']??'')), $q) !== false;
  }));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Industrial</title>
  <link rel="stylesheet" href="/bauhaus2025/css/index.css">
  <link rel="stylesheet" href="/bauhaus2025/css/arqui.ind.mob.css">
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

<main id="industrial">
  <div class="contenido">
    <div class="imagen-principal">
      <img src="/bauhaus2025/imagenes/img industrial.png" alt="Diseño industrial Bauhaus">
    </div>
    <div class="texto-informativo">
      <h2>Diseño Industrial</h2>
      <p>
        El diseño industrial en la Bauhaus fue un campo pionero que buscó integrar el arte con la producción en masa, sentando las bases para el diseño industrial moderno.
        En la Bauhaus, el diseño de objetos cotidianos pasó de ser un proceso artesanal a uno racionalizado, donde la estética, la funcionalidad y la tecnología se combinaban
        para crear productos que pudieran ser producidos en serie, accesibles y útiles para la sociedad. Bajo la dirección de maestros como László Moholy-Nagy y Marcel Breuer,
        la escuela exploró nuevas formas y materiales industriales —como el acero, el vidrio y los plásticos— para desarrollar objetos innovadores alineados con la vida moderna.
        El objetivo era democratizar el diseño: hacer productos más asequibles y prácticos, y romper con el elitismo de los objetos decorativos de la época.
        El diseño industrial en la Bauhaus no solo cambió la manera en que se creaban los objetos, sino también la forma en que se pensaba en ellos. Desde lámparas hasta
        electrodomésticos, los diseñadores de la Bauhaus influyeron en la vida cotidiana de millones de personas, demostrando que la belleza y la utilidad no tienen por qué estar en desacuerdo.
      </p>
    </div>
  </div>

  <section class="influencers">
    <form class="form-inline" method="get" action="/bauhaus2025/subpaginas/industrial.php">
      <input class="input" type="text" name="search" placeholder="Buscar artista, descripción o producto"
             value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
      <button class="btn btn--primary" type="submit">Buscar</button>
      <button class="btn btn--primary" type="button"
              onclick="window.location.href='/bauhaus2025/subpaginas/industrial.php'">
        Limpiar
      </button>
    </form>

    <?php if (!empty($db_error)): ?>
      <p class="muted" style="color:#c00;"><?php echo htmlspecialchars($db_error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php elseif ($search !== '' && mb_strlen($search,'UTF-8') < $minlen): ?>
      <p class="muted">Escribí al menos <?php echo $minlen; ?> caracteres para buscar.</p>
    <?php endif; ?>
  </section>

  <section class="influencers">
    <h2>Principales Influyentes</h2>
    <div class="influencer-container">
      <?php if (empty($artistas) && empty($db_error)): ?>
        <p class="muted">No se encontraron artistas.</p>
      <?php else: ?>
        <?php foreach ($artistas as $a): ?>
          <div class="influencer-card">
            <img src="<?php echo htmlspecialchars(img_path($a['imagen']), ENT_QUOTES, 'UTF-8'); ?>"
                 alt="<?php echo htmlspecialchars($a['artista'], ENT_QUOTES, 'UTF-8'); ?>">
            <h3><?php echo htmlspecialchars($a['artista'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($a['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="influencers">
    <h2>Productos</h2>
    <div class="influencer-container">
      <?php if (empty($productos_filtrados)): ?>
        <p class="muted">No se encontraron productos.</p>
      <?php else: ?>
        <?php foreach ($productos_filtrados as $p): ?>
          <div class="influencer-card">
            <img src="<?php echo htmlspecialchars($p['img'], ENT_QUOTES, 'UTF-8'); ?>"
                 alt="<?php echo htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
            <h3><?php echo htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($p['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
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
