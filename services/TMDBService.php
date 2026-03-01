<?php

class TMDBService {

    public static function getMovies($type) {

        $url = TMDB_BASE_URL . "/movie/$type?api_key=" 
            . TMDB_API_KEY . "&language=fr-FR";

        $response = @file_get_contents($url);

        if ($response === FALSE) {
            return null;
        }

        return json_decode($response, true);
    }
}