<?php
namespace TicketFlow\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LandingController extends Controller
{
    public function index(Request $request): Response
    {
        $toasts = [];
        if ($request->query->get('success')) {
            $toasts[] = ['type' => 'success', 'message' => $request->query->get('success')];
        }
        
        return $this->render('landing/index.twig', [
            'user' => $this->getUser(),
            'toasts' => $toasts
        ]);
    }
}