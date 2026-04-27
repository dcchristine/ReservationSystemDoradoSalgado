<?php

class ProfileController extends BaseController
{
    public function index(): void
    {
        $this->render('pages/profile', [
            'pageTitle' => 'Company Profile',
            'activePage' => 'profile',
        ]);
    }
}
