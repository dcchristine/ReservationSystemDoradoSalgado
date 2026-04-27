<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', __DIR__);

spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/Core/' . $class . '.php',
        APP_PATH . '/Models/' . $class . '.php',
        APP_PATH . '/Controllers/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($scriptDir === '/' || $scriptDir === '.') {
        $scriptDir = '';
    }

    $lastSegment = basename($scriptDir);
    if (in_array($lastSegment, ['admin', 'includes'], true)) {
        $scriptDir = dirname($scriptDir);
        if ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '\\') {
            $scriptDir = '';
        }
    }

    return rtrim($scriptDir, '/') . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return base_url($path);
}

function redirect_to(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function render_view(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data, EXTR_SKIP);

    ob_start();
    require APP_PATH . '/Views/' . $view . '.php';
    $content = ob_get_clean();

    require APP_PATH . '/Views/' . $layout . '.php';
}
