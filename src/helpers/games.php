<?php

function getAllGames() : array {

    // 1. Path jusqu'aux jeux.
    $path = __DIR__ . '/../../data/games.json';

    // 2. Lire le fichier.
    $json = file_get_contents($path);

    // 3. Récupérer nos jeux en tableau.
    if ($json === false) {
        return [];
    }
    $data = json_decode($json, true);

    // 4. Retourne les jeux.
    return is_array($data) ? $data : [];
}

function getGameById(int $id) : ?array {
    foreach (getAllGames() as $gameById) {
        if ((int)($gameById['id'] ?? 0) === $id) {
            return $gameById;
        }
    }

    return null;
    // return array_find(getAllGames(), fn($gameById) => (int)($gameById['id'] ?? 0) === $id);
}