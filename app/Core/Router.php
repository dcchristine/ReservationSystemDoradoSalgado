<?php

class Router
{
    public function dispatch(string $route): void
    {
        $route = trim($route, '/');
        $route = $route === '' ? 'home' : $route;

        switch ($route) {
            case 'home':
                (new HomeController())->index();
                break;
            case 'profile':
                (new ProfileController())->index();
                break;
            case 'reservation':
                (new ReservationController())->index();
                break;
            case 'contacts':
                (new ContactController())->index();
                break;
            case 'login':
                (new AuthController())->login();
                break;
            case 'logout':
                (new AuthController())->logout();
                break;
            case 'my-bookings':
                (new BookingController())->index();
                break;
            case 'admin/login':
                (new AdminAuthController())->login();
                break;
            case 'admin/logout':
                (new AdminAuthController())->logout();
                break;
            case 'admin/dashboard':
                (new AdminController())->dashboard();
                break;
            case 'admin/reservations':
                (new AdminController())->reservations();
                break;
            case 'admin/reservations/edit':
                (new AdminController())->editReservation();
                break;
            case 'admin/reservations/delete':
                (new AdminController())->deleteReservation();
                break;
            case 'admin/contacts':
                (new AdminController())->contacts();
                break;
            default:
                http_response_code(404);
                render_view('pages/404', [
                    'pageTitle' => 'Page Not Found',
                    'activePage' => '',
                ]);
        }
    }
}
