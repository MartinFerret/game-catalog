<?php

require_once __DIR__ . '/../helpers/games.php';
require_once __DIR__ . '/../helpers/debug.php';

final class AppController {
    public function handleRequest () : void {
        $page = $_GET['page'] ?? 'home';

        switch ($page) {
            case 'home':
                $this->home();
                break;
            case 'games':
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
        // 1. Récupérer les 3 jeux.

        $games = getAllGames();
        $featuresGames = array_slice($games, 0, 3);

        // 2. Rendre la vue.
        $this->render('home', [
            'featuredGames' => $featuresGames,
            'total' => count($games)
        ]);
    }

    private function games () : void {
        $games = getAllGames();

        usort($games, function ($a, $b) {
            return $b['rating'] <=> $a['rating']; // DESC
        });
        $this->render('games', [
            'games' => $games
        ]);
    }

    private function notFound() : void {
        $this->render('not-found');
    }
}