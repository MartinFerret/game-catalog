<?php

namespace Core;

final class Router
{

    private array $getRoutes = [];
    private array $postRoutes = [];
    private array $getRegexRoutes = [];

    private array $postRegexRoutes = [];

    private array $deleteRoutes = [];
    private array $deleteRegexRoutes = [];


    public function get(string $path, callable $handler): void
    {
        $this->getRoutes[$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->postRoutes[$path] = $handler;
    }

    public function getRegex(string $pattern, callable $handler): void
    {
        $this->getRegexRoutes[$pattern] = $handler;
    }
    public function postRegex(string $pattern, callable $handler): void
    {
        $this->postRegexRoutes[$pattern] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->deleteRoutes[$path] = $handler;
    }

    public function deleteRegex(string $pattern, callable $handler): void
    {
        $this->deleteRegexRoutes[$pattern] = $handler;
    }


    public function dispatch(Request $request, Response $response): void
    {
        // Connaître le path.
        $path = $request->path();

        // Connaître la méthode.
        $method = $request->method();

        if ($method === 'GET' && isset($this->getRoutes[$path])) {
            $this->getRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'POST' && isset($this->postRoutes[$path])) {
            $this->postRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'DELETE' && isset($this->deleteRoutes[$path])) {
            $this->deleteRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'DELETE') {
            foreach ($this->deleteRegexRoutes as $pattern => $handler) {
                if (preg_match($pattern, $path, $matches)) {
                    $handler($request, $response, $matches);
                    return;
                }
            }
        }


        if ($method === 'GET') {
            foreach ($this->getRegexRoutes as $pattern => $handler) {
                if (preg_match($pattern, $path, $matches)) {
                    $handler($request, $response, $matches);
                    return;
                }
            }
        }

        if ($method === 'POST') {
            foreach ($this->postRegexRoutes as $pattern => $handler) {
                if (preg_match($pattern, $path, $matches)) {
                    $handler($request, $response, $matches);
                    return;
                }
            }
        }


        $response->json(['error' => 'Not Found'], 404);

    }
}