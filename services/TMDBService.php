<?php
class TMDBService {
    // recup film en list par type sur TMDB
    public static function getMovies(string $type): array {
        $validTypes = ['popular', 'top_rated', 'upcoming', 'now_playing'];
        if (!in_array($type, $validTypes)) {
            return ['error' => "Type invalide. Types valides: " . implode(', ', $validTypes)];
        }

        $url = TMDB_BASE_URL . "/movie/{$type}?api_key=" . TMDB_API_KEY . "&language=fr-FR&page=1";
        return self::fetchFromTMDB($url);
    }
    // recherche de film sur TMDB
    public static function searchMovies(string $query): array {
        $url = TMDB_BASE_URL . "/search/movie?api_key=" . TMDB_API_KEY . "&language=fr-FR&query=" . urlencode($query);
        return self::fetchFromTMDB($url);
    }
    // recup detail film sur TMDB
    public static function getMovieById(int $id): array {
        $url = TMDB_BASE_URL . "/movie/{$id}?api_key=" . TMDB_API_KEY . "&language=fr-FR";
        return self::fetchFromTMDB($url);
    }
    // gestion des requetes vers TMDB avec timeout et gestion d'erreur
    private static function fetchFromTMDB(string $url): array {
        $context = stream_context_create([
            'http' => ['timeout' => 10]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return ['error' => 'Impossible de contacter TMDB. Vérifie ta clé API.'];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Réponse TMDB invalide'];
        }

        return $data;
    }
}
