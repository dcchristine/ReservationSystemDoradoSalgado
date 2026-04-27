<?php

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->render('pages/home', [
            'pageTitle' => 'Home',
            'activePage' => 'home',
            'rooms' => Room::all(),
            'isLoggedIn' => $this->guestLoggedIn(),
        ]);
    }
}
