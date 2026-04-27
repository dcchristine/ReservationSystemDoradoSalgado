<?php

class BaseController
{
    protected function render(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        render_view($view, $data, $layout);
    }

    protected function requireGuest(): void
    {
        if (!$this->guestLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? base_url('reservation.php');
            redirect_to('login.php');
        }
    }

    protected function requireAdmin(): void
    {
        if (!$this->adminLoggedIn()) {
            redirect_to('admin/login.php');
        }
    }

    protected function guestLoggedIn(): bool
    {
        return isset($_SESSION['guest_id']);
    }

    protected function adminLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']);
    }
}
