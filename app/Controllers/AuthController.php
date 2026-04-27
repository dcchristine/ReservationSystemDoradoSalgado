<?php

class AuthController extends BaseController
{
    public function login(): void
    {
        if ($this->guestLoggedIn()) {
            redirect_to('my-bookings.php');
        }

        $error = '';
        $tab = $_GET['tab'] ?? 'login';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'login';
            if ($action === 'register') {
                [$error, $tab] = $this->registerGuest();
            } else {
                [$error, $tab] = $this->loginGuest();
            }
        }

        $this->render('auth/login', [
            'pageTitle' => 'Login',
            'activePage' => 'login',
            'error' => $error,
            'tab' => $tab,
        ]);
    }

    public function logout(): void
    {
        unset(
            $_SESSION['guest_id'],
            $_SESSION['guest_first_name'],
            $_SESSION['guest_last_name'],
            $_SESSION['guest_email']
        );

        redirect_to('index.php');
    }

    private function loginGuest(): array
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $guest = Guest::authenticate($email, $password);

        if (!$guest) {
            return ['Invalid email or password.', 'login'];
        }

        $this->setGuestSession($guest);
        $redirect = $_SESSION['redirect_after_login'] ?? base_url('my-bookings.php');
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    }

    private function registerGuest(): array
    {
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
            return ['All fields are required.', 'register'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['Please enter a valid email address.', 'register'];
        }
        if (strlen($password) < 6) {
            return ['Password must be at least 6 characters.', 'register'];
        }
        if ($password !== $confirm) {
            return ['Passwords do not match.', 'register'];
        }
        if (Guest::emailExists($email)) {
            return ['An account with this email already exists.', 'register'];
        }

        $created = Guest::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);

        if (!$created) {
            return ['Registration failed. Please try again.', 'register'];
        }

        $guest = Guest::authenticate($email, $password);
        if ($guest) {
            $this->setGuestSession($guest);
            $redirect = $_SESSION['redirect_after_login'] ?? base_url('my-bookings.php');
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        }

        return ['Registration completed, but automatic login failed. Please sign in.', 'login'];
    }

    private function setGuestSession(array $guest): void
    {
        $_SESSION['guest_id'] = $guest['id'];
        $_SESSION['guest_first_name'] = $guest['first_name'];
        $_SESSION['guest_last_name'] = $guest['last_name'];
        $_SESSION['guest_email'] = $guest['email'];
    }
}
