<?php

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
            case '/games':
                $this->games();
                break;
            default:
                $this->notFound();
                break;
        }
    }

    private function render (string $view, array $data = []) : void {
        extract($data);
        require __DIR__ . '/../../views/partials/header.php'; // Header
        require __DIR__ . '/../../views/pages/' . $view . '.php';
        require __DIR__ . '/../../views/partials/footer.php'; // Footer
    }

    private function home() : void {
        $games = getLimitedGames(3);

        http_response_code(200);

        $this->render('home', [
            'featuredGames' => $games,
            'total' => countAll()
        ]);
    }

    private function games() : void {
        $games = getAllGamesSortedByRating();

        http_response_code(200);

        $this->render('games', [
            'games' => $games
        ]);
    }

    private function gameById (int $id) : void {
        $game = getGameById($id);

        http_response_code(200);

        $this->render('detail', [
            'id' => $id,
            'game' => $game
        ]);
    }

    private function notFound() : void {
        http_response_code(404);

        $this->render('not-found');
    }
}