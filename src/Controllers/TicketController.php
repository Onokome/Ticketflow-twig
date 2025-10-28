<?php
namespace TicketFlow\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function index(Request $request): Response
    {
        if (!$this->requireAuth()) {
            return $this->redirect('/auth/login');
        }

        $tickets = $this->loadTickets();

        return $this->render('tickets/index.twig', [
            'user' => $this->getUser(),
            'tickets' => $tickets,
            'success' => $request->query->get('success'),
            'error' => $request->query->get('error')
        ]);
    }

    public function create(Request $request): Response
    {
        if (!$this->requireAuth()) {
            return $this->redirect('/auth/login');
        }

        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $status = $request->request->get('status');
        $priority = $request->request->get('priority');

        $errors = $this->validateTicket($title, $status);

        if (!empty($errors)) {
            return $this->redirect('/tickets?error=' . urlencode(implode(', ', $errors)));
        }

        $tickets = $this->loadTickets();

        // Generate unique ID
        $newId = $this->generateUniqueId($tickets);

        $newTicket = [
            'id' => $newId,
            'title' => $title,
            'description' => $description ?: '',
            'status' => $status,
            'priority' => $priority ?: 'medium',
            'createdBy' => $this->getUser()['id'],
            'createdByEmail' => $this->getUser()['email'],
            'createdByName' => $this->getUser()['name'],
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];

        $tickets[] = $newTicket;
        $this->saveTickets($tickets);

        return $this->redirect('/tickets?success=Ticket created successfully');
    }

    public function update(Request $request): Response
    {
        if (!$this->requireAuth()) {
            return $this->redirect('/auth/login');
        }

        $id = $request->request->get('id');
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $status = $request->request->get('status');
        $priority = $request->request->get('priority');

        $errors = $this->validateTicket($title, $status);

        if (!empty($errors)) {
            return $this->redirect('/tickets?error=' . urlencode(implode(', ', $errors)));
        }

        $tickets = $this->loadTickets();
        $updated = false;

        foreach ($tickets as &$ticket) {
            if ($ticket['id'] == $id) { // Use loose comparison for string IDs
                $ticket['title'] = $title;
                $ticket['description'] = $description ?: '';
                $ticket['status'] = $status;
                $ticket['priority'] = $priority ?: 'medium';
                $ticket['updatedAt'] = date('Y-m-d H:i:s');
                $updated = true;
                break;
            }
        }

        if ($updated) {
            $this->saveTickets($tickets);
            return $this->redirect('/tickets?success=Ticket updated successfully');
        }

        return $this->redirect('/tickets?error=Ticket not found');
    }

    public function delete(Request $request): Response
    {
        if (!$this->requireAuth()) {
            return $this->redirect('/auth/login');
        }

        $id = $request->request->get('id');
        $tickets = $this->loadTickets();

        $initialCount = count($tickets);
        $tickets = array_filter($tickets, function($ticket) use ($id) {
            return $ticket['id'] != $id; // Use loose comparison for string IDs
        });
        
        if (count($tickets) < $initialCount) {
            $this->saveTickets(array_values($tickets));
            return $this->redirect('/tickets?success=Ticket deleted successfully');
        }

        return $this->redirect('/tickets?error=Ticket not found');
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
        
        // Return empty array if file doesn't exist
        return [];
    }

    private function saveTickets($tickets)
    {
        $ticketsFile = __DIR__ . '/../../data/tickets.json';
        file_put_contents($ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));
    }

    private function validateTicket($title, $status): array
    {
        $errors = [];

        if (empty(trim($title))) {
            $errors[] = 'Title is required';
        }

        if (strlen(trim($title)) > 255) {
            $errors[] = 'Title must be less than 255 characters';
        }

        $validStatuses = ['open', 'in_progress', 'closed'];
        if (!in_array($status, $validStatuses)) {
            $errors[] = 'Status must be one of: ' . implode(', ', $validStatuses);
        }

        return $errors;
    }

    private function generateUniqueId($tickets)
    {
        if (empty($tickets)) {
            return '1';
        }
        
        $maxId = 0;
        foreach ($tickets as $ticket) {
            $id = (int)$ticket['id'];
            if ($id > $maxId) {
                $maxId = $id;
            }
        }
        
        return (string)($maxId + 1);
    }
}