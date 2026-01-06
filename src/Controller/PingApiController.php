<?php

namespace Controller;

use Core\Request;
use Core\Response;

final class PingApiController
{
    public function ping(Request $request, Response $response) {
        $response->json(['ok' => true, 'message' => 'pong']);
    }
}