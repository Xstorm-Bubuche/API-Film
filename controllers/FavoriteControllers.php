<?php
class FavoriteController {
    // gestion favoris en local
    private static function loadFavorites(): array {
        if (!file_exists(FAVORITES_FILE)) {
            return [];
        }
        $content = file_get_contents(FAVORITES_FILE);
        return json_decode($content, true) ?? [];
    }
    // sauvegarde favoris
    private static function saveFavorites(array $favorites): void {
        $dir = dirname(FAVORITES_FILE);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        file_put_contents(FAVORITES_FILE, json_encode($favorites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    // recup favoris
    public static function getAll(): void {
        $favorites = self::loadFavorites();
        echo json_encode(['favorites' => array_values($favorites), 'total' => count($favorites)]);
    }
    // ajout favoris
    public static function add(): void {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['id'], $input['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Données invalides. Champs requis: id, title']);
            return;
        }

        $favorites = self::loadFavorites();
        $id = (string)$input['id'];

        if (isset($favorites[$id])) {
            http_response_code(409);
            echo json_encode(['error' => 'Ce film est déjà dans les favoris']);
            return;
        }

        $favorites[$id] = [
            'id'           => $input['id'],
            'title'        => $input['title'],
            'poster_path'  => $input['poster_path'] ?? null,
            'vote_average' => $input['vote_average'] ?? null,
            'added_at'     => date('Y-m-d H:i:s'),
        ];

        self::saveFavorites($favorites);
        http_response_code(201);
        echo json_encode(['message' => 'Film ajouté aux favoris', 'movie' => $favorites[$id]]);
    }
    // suppression favoris (des fois ca bug)
    public static function remove(int $id): void {
        $favorites = self::loadFavorites();
        $key = (string)$id;

        if (!isset($favorites[$key])) {
            http_response_code(404);
            echo json_encode(['error' => 'Film non trouvé dans les favoris']);
            return;
        }

        unset($favorites[$key]);
        self::saveFavorites($favorites);
        echo json_encode(['message' => 'Film supprimé des favoris']);
    }
}
