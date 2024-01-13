<?php

require_once __DIR__ . '/../Router/Router.php';

$router = new Router();

// Get the URI from the server
$uri = trim($_SERVER['REQUEST_URI'], '/');

// Route the request
$router->route($uri);
