<?php

require_once 'config/config.php';
require_once 'controllers/MovieController.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/movies' && $method === 'GET') {

    $type = $_GET['type'] ?? 'popular';
    MovieController::list($type);

}
elseif ($path === '/favorites' && $method === 'POST') {

    MovieController::addFavorite();

}
elseif ($path === '/favorites' && $method === 'GET') {

    MovieController::getFavorites();

}
else {

    http_response_code(404);
    echo json_encode(["error" => "Route inconnue"]);

}