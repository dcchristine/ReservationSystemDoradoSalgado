<?php

class AdminAuthController extends BaseController
{
    public function login(): void
    {
        if ($this->adminLoggedIn()) {
            redirect_to('admin/dashboard.php');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $admin = AdminUser::authenticate($username, $password);

            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_username'] = $admin['username'];
                redirect_to('admin/dashboard.php');
            }

            $error = 'Invalid username or password.';
        }

        $this->render('admin/login', [
            'pageTitle' => 'Admin Login',
            'error' => $error,
        ], 'layouts/admin_login');
    }

    public function logout(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_username']);
        redirect_to('admin/login.php');
    }
}
