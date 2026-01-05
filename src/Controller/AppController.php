<?php

namespace Controller;

use JetBrains\PhpStorm\NoReturn;
use Core\Response;
use Repository\GamesRepository;

require_once __DIR__ . '/../Helper/Debug.php';

final readonly class AppController {

    public function __construct(
        private Response $response,
        private GamesRepository $gamesRepository,
    ) {}
    public function handleRequest (string $path) : void {

        if (preg_match('#^/games/(\d+)$#', $path, $m)) {
            $this->gameById((int)$m[1]);
            return;
        }

        switch ($path) {
            case '/':
                $this->home();
                break;
            case '/random':
                $this->random();
            case '/add':
                $this->add();
                break;
            case '/games':
                $this->games();
                break;
            default:
                $this->notFound();
                break;
        }
    }

    private function home() : void {
        $games = $this->gamesRepository->findTop(3);

        $this->response->render('home', [
            'featuredGames' => $games,
            'total' => $this->gamesRepository->countAll()
        ]);
    }

    private function games() : void {
        $games = $this->gamesRepository->findAllSortedByRating();

        $this->response->render('games', [
            'games' => $games
        ]);
    }

    private function gameById (int $id) : void {
        $game = $this->gamesRepository->findById($id);

        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);
        $this->response->render('detail', [
            'id' => $id,
            'game' => $game,
            'success' => $success
        ]);
    }

    private function notFound() : void {
        $this->response->render('not-found', [], 404);
    }

    #[NoReturn]
    private function random() : void {
        $lastId = $_SESSION['last_random_id'] ?? 0;
        $game = null;

        for ($i = 0; $i < 5; $i++) {
            $candidate = $this->gamesRepository->findRandom();

            if ($candidate['id'] !== $lastId) {
                $game = $candidate;
            }
        }

        $id = $game['id'];
        $_SESSION['last_random_id'] = $id;

        $this->response->redirect('/games/' . $id);
    }

    private function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAddGame();
            return;
        }

        $this->response->render('add', []);
    }

    private function handleAddGame() : void {
        $title = trim($_POST['title']);
        $platform = trim($_POST['platform']);
        $genre = trim($_POST['genre']);
        $releaseYear = (int)($_POST['releaseYear']);
        $rating = (int)($_POST['rating']);
        $description = trim($_POST['description']);
        $notes = trim($_POST['notes']);

        $errors = [];

        if ($title === '') $errors['title'] = 'Title should not be empty';
        if ($platform === '') $errors['platform'] = 'Platform should not be empty';
        if ($genre === '') $errors['genre'] = 'Genre should not be empty';
        if ($rating < 0 || $rating > 10) $errors['rating'] = 'Rating should be between 0 and 10';
        if ($releaseYear < 1800 || $releaseYear > (int)date('Y')) $errors['releaseYear'] = 'Release year should be between 1800 and 2025';
        if ($description === '') $errors['description'] = 'Description should not be empty';
        if ($notes === '') $errors['notes'] = 'Note should not be empty';

        $old = [
            'title' => $title,
            'platform' => $platform,
            'genre' => $genre,
            'releaseYear' => $releaseYear,
            'rating' => $rating,
            'description' => $description,
            'notes' => $notes
        ];

        if (!empty($errors)) {
            $this->response->render('add', ['old' => $old, 'errors' => $errors], 422);
            return;
        }

        $newGameId = $this->gamesRepository->createGame($old);
        $_SESSION['flash_success'] = 'Game added successfully';

        $this->response->redirect('/games/' . $newGameId);
    }
}