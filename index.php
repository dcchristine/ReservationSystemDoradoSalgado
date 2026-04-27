<?php
require_once __DIR__ . '/app/bootstrap.php';

$route = $_GET['route'] ?? 'home';
(new Router())->dispatch($route);
