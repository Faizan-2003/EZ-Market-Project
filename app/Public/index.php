<?php

require_once __DIR__ . '/../Router/Router.php';

$router = new Router();

$uri = trim($_SERVER['REQUEST_URI'], '/');

$router->route($uri);
