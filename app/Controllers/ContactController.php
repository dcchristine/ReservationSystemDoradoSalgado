<?php

class ContactController extends BaseController
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        }

        $this->render('pages/contacts', [
            'pageTitle' => 'Contact Us',
            'activePage' => 'contacts',
        ]);
    }

    private function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || $email === '' || $message === '') {
            $_SESSION['error'] = 'Please fill in all required fields.';
            redirect_to('contacts.php');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please enter a valid email address.';
            redirect_to('contacts.php');
        }

        $saved = Contact::create([
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
        ]);

        $_SESSION[$saved ? 'success' : 'error'] = $saved
            ? 'Your message has been sent successfully! We will get back to you shortly.'
            : 'Failed to send message. Please try again.';

        redirect_to('contacts.php');
    }
}
