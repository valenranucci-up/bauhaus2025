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
    'nombre'=>'Silla Cesca - Marcel Breuer',
    'img'=>'/bauhaus2025/imagenes/silla-cesca.jpg',
    'desc'=>'La silla Cesca es un clásico del mobiliario Bauhaus. Su estructura de acero tubular combinada con asiento y respaldo de caña tejida logra ligereza, resistencia y confort. Su diseño cantilever, sin patas traseras, marcó una innovación en el mobiliario moderno y la convirtió en un referente atemporal del diseño industrial.'
  ],
  [
    'nombre'=>'Set de Mesa - Marianne Brandt',
    'img'=>'/bauhaus2025/imagenes/set-de-mesa.jpg',
    'desc'=>'El set de mesa refleja la esencia de la Bauhaus. Con formas geométricas y un diseño pensado para la vida cotidiana, destaca por su simplicidad y utilidad práctica. Transformó utensilios comunes en piezas de diseño moderno, demostrando que la unión entre arte, técnica y funcionalidad podía dar lugar a objetos innovadores y atemporales.'
  ],
  [
    'nombre'=>'Lámpara WG24 - Wagenfeld',
    'img'=>'/bauhaus2025/imagenes/lampara-wagenfeld.jpg',
    'desc'=>'La lámpara WG24 es uno de los íconos más reconocidos de la Bauhaus. Con una estructura de vidrio y metal, une estética y funcionalidad en un diseño de líneas simples y geométricas. Representa la idea de que los objetos cotidianos pueden ser bellos y prácticos, y sigue vigente como símbolo del diseño moderno.'
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
  <link rel="stylesheet" href="/bauhaus2025/css/index.css?v=31">
<link rel="stylesheet" href="/bauhaus2025/css/arqui.ind.mob.css?v=31">

<!-- OVERRIDES para que NO se corte el texto en Industrial -->
<style>
  /* El contenedor de tarjetas NO trata de igualar alturas */
  #industrial .influencer-container{
    align-items: start !important;
    grid-auto-rows: auto !important;
  }

  /* La tarjeta crece en alto según su contenido */
  #industrial .influencer-card{
    display: flex !important;
    flex-direction: column !important;
    height: auto !important;
    max-height: none !important;
    overflow: visible !important;
  }

  /* Imagen más baja para liberar espacio al texto (ajustá 160–200px si querés) */
  #industrial .influencer-card img{
    width: 100% !important;
    max-height: 180px !important;
    height: auto !important;
    object-fit: cover !important;
    border-radius: 12px !important;
    margin-bottom: 10px !important;
    flex: 0 0 auto !important;
  }

  #industrial .influencer-card h3{ margin: 8px 0 !important; }

  /* Párrafo SIN truncado de ningún tipo */
  #industrial .influencer-card p{
    margin: 0 !important;
    line-height: 1.5 !important;
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: clip !important;
    max-height: none !important;
    height: auto !important;
    display: block !important;
    -webkit-line-clamp: unset !important;
    line-clamp: unset !important;
    -webkit-box-orient: unset !important;
    word-break: normal !important;
    overflow-wrap: anywhere !important;
  }
</style>

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
