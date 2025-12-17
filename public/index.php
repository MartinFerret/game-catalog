<?php

require_once __DIR__ . '/../src/controllers/AppController.php';

$appController = new AppController();
$appController->handleRequest();

