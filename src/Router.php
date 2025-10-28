<?php
namespace TicketFlow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router {
    private $routes = [];
    
    public function __construct() {
        $this->routes = [
            'GET' => [
                '/' => 'LandingController@index',
                '/auth/login' => 'AuthController@showLogin',
                '/auth/signup' => 'AuthController@showSignup',
                '/dashboard' => 'DashboardController@index',
                '/tickets' => 'TicketController@index',
                // Remove logout from GET - it should only be POST
            ],
            'POST' => [
                '/auth/login' => 'AuthController@login',
                '/auth/signup' => 'AuthController@signup',
                '/auth/logout' => 'AuthController@logout', // Move logout to POST
                '/tickets/create' => 'TicketController@create',
                '/tickets/update' => 'TicketController@update',
                '/tickets/delete' => 'TicketController@delete'
            ]
        ];
    }
    
    public function handle(Request $request): Response {
        $path = $request->getPathInfo();
        $method = $request->getMethod();
        
        if ($path === '') $path = '/';
        
        // Simple route matching (you might want to add proper parameter parsing)
        foreach ($this->routes[$method] as $route => $handler) {
            if ($this->matchRoute($route, $path)) {
                list($controller, $action) = explode('@', $handler);
                return $this->callController($controller, $action, $request);
            }
        }
        
        return new Response('Page not found', 404);
    }
    
    private function matchRoute($route, $path): bool {
        if ($route === $path) {
            return true;
        }
        
        // Simple pattern matching for routes with parameters
        $routePattern = preg_replace('/\{[^}]+\}/', '[^/]+', $route);
        $routePattern = str_replace('/', '\/', $routePattern);
        return preg_match('/^' . $routePattern . '$/', $path);
    }
    
    private function callController(string $controller, string $action, Request $request): Response {
        $controllerClass = "TicketFlow\\Controllers\\{$controller}";
        
        if (!class_exists($controllerClass)) {
            return new Response('Controller not found: ' . $controllerClass, 500);
        }
        
        $controllerInstance = new $controllerClass();
        
        if (!method_exists($controllerInstance, $action)) {
            return new Response('Action not found: ' . $action, 500);
        }
        
        return $controllerInstance->$action($request);
    }
}