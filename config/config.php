<?php

define('TMDB_API_KEY', '0d8819aed9e64813df0aabc6ef24e3f4'); //clé api
define('TMDB_BASE_URL', 'https://api.themoviedb.org/3'); //url
define('TMDB_IMAGE_URL', 'https://image.tmdb.org/t/p/w500'); //pour les belles image
define('FAVORITES_FILE', __DIR__ . '/../data/favorites.json'); //favorie en local

//accès
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
