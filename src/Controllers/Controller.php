<?php
namespace TicketFlow\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected $twig;

    public function __construct()
    {
        session_start();
        
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
        
        // Add custom filters
        $this->twig->addFilter(new \Twig\TwigFilter('title', function($value) {
            return ucwords(str_replace('_', ' ', $value));
        }));
    }

    protected function render(string $view, array $data = []): Response
    {
        try {
            // Always pass user data to templates
            if (!isset($data['user'])) {
                $data['user'] = $this->getUser();
            }
            
            $html = $this->twig->render($view, $data);
            return new Response($html);
        } catch (\Exception $e) {
            return new Response('Template error: ' . $e->getMessage(), 500);
        }
    }

    protected function redirect(string $url): Response
    {
        return new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    }

    protected function isAuthenticated(): bool
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            return true;
        }
        
        // Check cookie for session persistence
        if (isset($_COOKIE['ticketapp_session'])) {
            $sessionData = json_decode($_COOKIE['ticketapp_session'], true);
            if ($sessionData && isset($sessionData['user']) && isset($sessionData['expires'])) {
                if (time() < $sessionData['expires']) {
                    $_SESSION['user'] = $sessionData['user'];
                    return true;
                }
            }
        }
        
        return false;
    }

    protected function getUser()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        
        // Check cookie for session persistence
        if (isset($_COOKIE['ticketapp_session'])) {
            $sessionData = json_decode($_COOKIE['ticketapp_session'], true);
            if ($sessionData && isset($sessionData['user']) && isset($sessionData['expires'])) {
                if (time() < $sessionData['expires']) {
                    $_SESSION['user'] = $sessionData['user'];
                    return $_SESSION['user'];
                }
            }
        }
        
        return null;
    }

    protected function requireAuth(): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }
        return true;
    }
}