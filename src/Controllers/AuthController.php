<?php
namespace TicketFlow\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $users;

    public function __construct()
    {
        parent::__construct();
        $this->loadUsers();
    }

    private function loadUsers()
    {
        $usersFile = __DIR__ . '/../../data/users.json';
        if (file_exists($usersFile)) {
            $this->users = json_decode(file_get_contents($usersFile), true) ?? [];
        } else {
            $this->users = [
                [
                    'id' => '1',
                    'email' => 'demo@example.com',
                    'password' => 'password123',
                    'name' => 'Demo User'
                ],
                [
                    'id' => '2',
                    'email' => 'admin@ticketflow.com',
                    'password' => 'admin123',
                    'name' => 'Admin User'
                ]
            ];
            file_put_contents($usersFile, json_encode($this->users, JSON_PRETTY_PRINT));
        }
    }

    public function showLogin(Request $request): Response
    {
        if ($this->isAuthenticated()) {
            return $this->redirect('/dashboard');
        }
        
        $toasts = [];
        if ($request->query->get('error')) {
            $toasts[] = ['type' => 'error', 'message' => $request->query->get('error')];
        }
        
        return $this->render('auth/login.twig', [
            'error' => $request->query->get('error'),
            'toasts' => $toasts
        ]);
    }

    public function showSignup(Request $request): Response
    {
        if ($this->isAuthenticated()) {
            return $this->redirect('/dashboard');
        }
        
        $toasts = [];
        if ($request->query->get('error')) {
            $toasts[] = ['type' => 'error', 'message' => $request->query->get('error')];
        }
        
        return $this->render('auth/signup.twig', [
            'error' => $request->query->get('error'),
            'toasts' => $toasts
        ]);
    }

    public function login(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $this->authenticateUser($email, $password);

        if ($user) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name']
            ];
            
            // Set cookie for session persistence
            $sessionData = [
                'user' => $_SESSION['user'],
                'expires' => time() + (24 * 60 * 60) // 24 hours
            ];
            setcookie('ticketapp_session', json_encode($sessionData), time() + (24 * 60 * 60), '/');
            
            return $this->redirect('/dashboard?success=Login successful');
        }

        return $this->redirect('/auth/login?error=Invalid email or password');
    }

    public function signup(Request $request): Response
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');

        $errors = $this->validateSignup($name, $email, $password, $confirmPassword);

        if (!empty($errors)) {
            return $this->redirect('/auth/signup?error=' . urlencode(implode(', ', $errors)));
        }

        $newUser = [
            'id' => (string)(count($this->users) + 1),
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $this->users[] = $newUser;
        file_put_contents(__DIR__ . '/../../data/users.json', json_encode($this->users, JSON_PRETTY_PRINT));

        $_SESSION['user'] = [
            'id' => $newUser['id'],
            'email' => $newUser['email'],
            'name' => $newUser['name']
        ];
        
        // Set cookie for session persistence
        $sessionData = [
            'user' => $_SESSION['user'],
            'expires' => time() + (24 * 60 * 60) // 24 hours
        ];
        setcookie('ticketapp_session', json_encode($sessionData), time() + (24 * 60 * 60), '/');

        return $this->redirect('/dashboard?success=Account created successfully');
    }

    public function logout(Request $request): Response
    {
        session_destroy();
        setcookie('ticketapp_session', '', time() - 3600, '/');
        return $this->redirect('/?success=Logged out successfully');
    }

    private function authenticateUser($email, $password)
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return $user;
            }
        }
        return null;
    }

    private function validateSignup($name, $email, $password, $confirmPassword): array
    {
        $errors = [];

        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Name must be at least 2 characters';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }

        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                $errors[] = 'Email already exists';
                break;
            }
        }

        return $errors;
    }
}