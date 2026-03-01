<?php

require_once __DIR__ . '/../services/TMDBService.php';

class MovieController {

    public static function list($type) {

        $movies = TMDBService::getMovies($type);

        if (!$movies) {
            http_response_code(500);
            echo json_encode(["error" => "Erreur TMDB"]);
            return;
        }

        echo json_encode($movies);
    }

    public static function addFavorite() {

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id']) || !isset($data['title'])) {
            http_response_code(400);
            echo json_encode(["error" => "Données invalides"]);
            return;
        }

        $file = __DIR__ . '/../storage/favorites.json';

        $favorites = [];

        if (file_exists($file)) {
            $favorites = json_decode(file_get_contents($file), true);
        }

        $favorites[] = $data;

        file_put_contents($file, json_encode($favorites));

        echo json_encode(["message" => "Ajouté aux favoris"]);
    }

    public static function getFavorites() {

        $file = __DIR__ . '/../storage/favorites.json';

        if (!file_exists($file)) {
            echo json_encode([]);
            return;
        }

        echo file_get_contents($file);
    }
}