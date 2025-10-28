<?php
require_once '../vendor/autoload.php';

use TicketFlow\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

session_start();
$request = Request::createFromGlobals();
$router = new Router();
$response = $router->handle($request);
$response->send();