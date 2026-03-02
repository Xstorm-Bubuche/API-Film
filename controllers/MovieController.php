<?php
require_once __DIR__ . '/../services/TMDBService.php';

class MovieController {
    //recup film en list par type
    public static function list(string $type): void {
        try {
            $movies = TMDBService::getMovies($type);
            if (isset($movies['error'])) {
                http_response_code(400);
            }
            echo json_encode($movies, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    // recherche
    public static function search(string $query): void {
        if (empty(trim($query))) {
            http_response_code(400);
            echo json_encode(['error' => 'pas de recherche vide']);
            return;
        }
        try {
            $movies = TMDBService::searchMovies($query);
            echo json_encode($movies, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    // detail
    public static function detail(int $id): void {
        try {
            $movie = TMDBService::getMovieById($id);
            echo json_encode($movie, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
