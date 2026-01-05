<?php

use Repository\GamesRepository;

require_once __DIR__ . '/../../config/db.php';

function gameRepository() : GamesRepository {
    return new GamesRepository(db());
}

function getAllGamesSortedByRating() : array {
    return gameRepository()->findAllSortedByRating();
}

function getAllGames() : array {
    return gameRepository()->findAll();
}

function getLimitedGames(int $id) : array {
    return gameRepository()->findTop($id);
}

function countAll() : int {
    return gameRepository()->countAll();
}

function getGameById(int $id) : ?array {
    return gameRepository()->findById($id);
}

function getRandomGame() {
    return gameRepository()->findRandom();
}

function createGame(array $data) : int {
    return gameRepository()->createGame($data);
}