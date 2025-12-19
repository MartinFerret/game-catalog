<?php

use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../services/games.php';
require_once __DIR__ . '/../helpers/debug.php';

final class AppController {
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
            case '/games':
                $this->games();
                break;
            default:
                $this->notFound();
                break;
        }
    }

    private function render (string $view, array $data = [], int $status = 200) : void {
        http_response_code($status);
        extract($data);
        require __DIR__ . '/../../views/partials/header.php'; // Header
        require __DIR__ . '/../../views/pages/' . $view . '.php';
        require __DIR__ . '/../../views/partials/footer.php'; // Footer
    }

    private function home() : void {
        $games = getLimitedGames(3);

        $this->render('home', [
            'featuredGames' => $games,
            'total' => countAll()
        ]);
    }

    private function games() : void {
        $games = getAllGamesSortedByRating();

        $this->render('games', [
            'games' => $games
        ]);
    }

    private function gameById (int $id) : void {
        $game = getGameById($id);

        $this->render('detail', [
            'id' => $id,
            'game' => $game
        ]);
    }

    private function notFound() : void {
        $this->render('not-found', [], 404);
    }

    #[NoReturn]
    private function random() : void {
        $lastId = $_SESSION['last_random_id'] ?? 0;
        $game = null;

        for ($i = 0; $i < 5; $i++) {
            $candidate = getRandomGame();

            if ($candidate['id'] !== $lastId) {
                $game = $candidate;
            }
        }

        $id = $game['id'];
        $_SESSION['last_random_id'] = $id;

        header('Location: /games/' . $id, true, 302);
        exit;
    }
}