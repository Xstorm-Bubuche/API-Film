<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/MovieController.php';
require_once __DIR__ . '/controllers/FavoriteControllers.php';


$requestUri = $_SERVER['REQUEST_URI'];
$path = strtok($requestUri, '?');
$method = $_SERVER['REQUEST_METHOD'];

// routing
if ($method === 'GET' && preg_match('#^/movies$#', $path)) {
    $type = $_GET['type'] ?? 'popular';
    MovieController::list($type);

// recherche
} elseif ($method === 'GET' && preg_match('#^/movies/search$#', $path)) {
    $query = $_GET['q'] ?? '';
    MovieController::search($query);

// detail
} elseif ($method === 'GET' && preg_match('#^/movies/(\d+)$#', $path, $matches)) {
    MovieController::detail((int)$matches[1]);

// get favoris
} elseif ($method === 'GET' && preg_match('#^/favorites$#', $path)) {
    FavoriteController::getAll();

// post favoris
} elseif ($method === 'POST' && preg_match('#^/favorites$#', $path)) {
    FavoriteController::add();

// del favoris
} elseif ($method === 'DELETE' && preg_match('#^/favorites/(\d+)$#', $path, $matches)) {
    FavoriteController::remove((int)$matches[1]);

// accueil
} elseif ($method === 'GET' && $path === '/') {
    echo json_encode([
        'api'     => 'API Films',
        'version' => '1.0',
        'routes'  => [
            'GET /movies?type=popular'    => 'Films populaires',
            'GET /movies?type=top_rated'  => 'Meilleurs films',
            'GET /movies?type=upcoming'   => 'Prochainement',
            'GET /movies?type=now_playing'=> 'En ce moment',
            'GET /movies/search?q=titre'  => 'Recherche',
            'GET /movies/{id}'            => 'Détail film',
            'GET /favorites'              => 'Liste favoris',
            'POST /favorites'             => 'Ajouter favori',
            'DELETE /favorites/{id}'      => 'Supprimer favori',
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// erreur
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route inconnue', 'path' => $path, 'method' => $method]);
}
