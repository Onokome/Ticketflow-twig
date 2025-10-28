<?php
namespace TicketFlow\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        if (!$this->requireAuth()) {
            return $this->redirect('/auth/login');
        }

        $tickets = $this->loadTickets();
        
        $stats = [
            'total' => count($tickets),
            'open' => count(array_filter($tickets, fn($t) => $t['status'] === 'open')),
            'in_progress' => count(array_filter($tickets, fn($t) => $t['status'] === 'in_progress')),
            'closed' => count(array_filter($tickets, fn($t) => $t['status'] === 'closed'))
        ];

        $toasts = [];
        if ($request->query->get('success')) {
            $toasts[] = ['type' => 'success', 'message' => $request->query->get('success')];
        }
        if ($request->query->get('error')) {
            $toasts[] = ['type' => 'error', 'message' => $request->query->get('error')];
        }

        return $this->render('dashboard/index.twig', [
            'user' => $this->getUser(),
            'stats' => $stats,
            'success' => $request->query->get('success'),
            'error' => $request->query->get('error'),
            'toasts' => $toasts
        ]);
    }

    private function loadTickets()
    {
        $ticketsFile = __DIR__ . '/../../data/tickets.json';
        
        // Create data directory if it doesn't exist
        $dataDir = dirname($ticketsFile);
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        
        if (file_exists($ticketsFile)) {
            $content = file_get_contents($ticketsFile);
            return $content ? json_decode($content, true) : [];
        }
        
        // Create sample tickets if none exist
        $sampleTickets = [
            [
                'id' => '1',
                'title' => 'Website Login Issue',
                'description' => 'Users are unable to login to the website',
                'status' => 'open',
                'priority' => 'high',
                'createdBy' => '1',
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s')
            ],
            [
                'id' => '2',
                'title' => 'Mobile App Crash',
                'description' => 'App crashes when opening settings page',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'createdBy' => '1',
                'createdAt' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updatedAt' => date('Y-m-d H:i:s')
            ],
            [
                'id' => '3',
                'title' => 'Feature Request: Dark Mode',
                'description' => 'Users are requesting dark mode support',
                'status' => 'closed',
                'priority' => 'low',
                'createdBy' => '2',
                'createdAt' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updatedAt' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ]
        ];
        file_put_contents($ticketsFile, json_encode($sampleTickets, JSON_PRETTY_PRINT));
        return $sampleTickets;
    }
}