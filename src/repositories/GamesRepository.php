<?php

readonly final class GamesRepository {
    public function __construct(private readonly PDO $pdo) {}

    public function findAllSortedByRating() : array {
        $sql = $this->pdo->query("SELECT * FROM games ORDER BY rating DESC");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() : array {
        $sql = $this->pdo->query("SELECT * FROM games");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findTop(int $limit) : array {
        $sql = $this->pdo->prepare("SELECT * FROM games ORDER BY id LIMIT :limit"); // On ajoute du dynamise avec le token :limit.
        $sql->bindValue('limit', $limit, PDO::PARAM_INT); // On lie le token :limit à l'argument $limit.
        $sql->execute(); // On execute.
        return $sql->fetchAll(PDO::FETCH_ASSOC); // On retourne nos valeurs.
    }

    public function findById(int $id) : ?array {
        $sql = $this->pdo->prepare("SELECT * FROM games WHERE id = :id");
        $sql->bindValue('id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function countAll() : int {
        $sql = $this->pdo->query("SELECT COUNT(*) FROM games");
        return $sql->fetch(PDO::FETCH_COLUMN);
    }

    public function findRandom() {
        $sql = $this->pdo->query("SELECT * FROM games ORDER BY RAND() LIMIT 1");
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}