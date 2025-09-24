<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/database.php';


if (is_file(__DIR__ . '/pages/home.php')) {
    require __DIR__ . '/pages/home.php';
} elseif (is_file(__DIR__ . '/home.php')) {
    require __DIR__ . '/home.php';
} elseif (is_file(__DIR__ . '/pages/home.html')) {
    readfile(__DIR__ . '/pages/home.html');
} elseif (is_file(__DIR__ . '/index_old.html')) {
    readfile(__DIR__ . '/index_old.html');
} else {
    echo 'No encontré ninguna portada (pages/home.php / home.php / pages/home.html / index_old.html).';
}
