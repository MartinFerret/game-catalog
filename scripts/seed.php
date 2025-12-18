<?php

require __DIR__ . '/../config/db.php';

$pdo = db();

$jsonPath = __DIR__ . '/../data/games.json';
$items = json_decode(file_get_contents($jsonPath), true);

if (!is_array($items)) {
    die("JSON invalide\n");
}

$pdo->beginTransaction();

$stmt = $pdo->prepare("INSERT INTO games (title, platform, genre, releaseYear, rating, description, notes)  VALUES (:title, :platform, :genre, :releaseYear, :rating, :description, :notes)");

foreach ($items as $g) {
    $stmt->execute([
        'title' => $g['title'],
        'platform' => $g['platform'],
        'genre' => $g['genre'],
        'releaseYear' => (int)$g['releaseYear'],
        'rating' => (int)$g['rating'],
        'description' => $g['description'],
        'notes' => $g['notes'],
    ]);
}

$pdo->commit();

echo "Seed OK : " . count($items) . " jeux insérés.\n";