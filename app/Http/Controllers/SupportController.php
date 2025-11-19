<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    protected $ticketsFile;
    protected $chatHistoryFile;

    public function __construct()
    {
        $this->ticketsFile = storage_path('app/support_tickets.json');
        $this->chatHistoryFile = storage_path('app/chat_history.json');
        
        if (!File::exists($this->ticketsFile)) {
            File::put($this->ticketsFile, json_encode([], JSON_PRETTY_PRINT));
        }
        
        if (!File::exists($this->chatHistoryFile)) {
            File::put($this->chatHistoryFile, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    // Show support page
    public function index()
    {
        $user = session('user');
        $tickets = $this->getUserTickets($user['id']);
        
        return view('support.index', compact('tickets'));
    }

    // Create support ticket
    public function createTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:200',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'description' => 'required|string|min:10',
        ]);

        $user = session('user');
        $tickets = $this->getAllTickets();
        
        $ticket = [
            'id' => 'TKT-' . strtoupper(Str::random(8)),
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'description' => $request->description,
            'status' => 'open',
            'messages' => [],
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];

        $tickets[] = $ticket;
        File::put($this->ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));

        return back()->with('success', 'Support ticket created successfully. Ticket ID: ' . $ticket['id']);
    }

    // View ticket details
    public function showTicket($ticketId)
    {
        $user = session('user');
        $ticket = $this->getTicketById($ticketId);

        if (!$ticket || $ticket['user_id'] != $user['id']) {
            return redirect()->route('support.index')->with('error', 'Ticket not found');
        }

        return view('support.ticket', compact('ticket'));
    }

    // Add message to ticket
    public function addMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|min:1',
        ]);

        $user = session('user');
        $tickets = $this->getAllTickets();
        $ticketIndex = null;

        foreach ($tickets as $index => $ticket) {
            if ($ticket['id'] === $ticketId && $ticket['user_id'] == $user['id']) {
                $ticketIndex = $index;
                break;
            }
        }

        if ($ticketIndex === null) {
            return back()->with('error', 'Ticket not found');
        }

        $tickets[$ticketIndex]['messages'][] = [
            'from' => 'user',
            'name' => $user['name'],
            'message' => $request->message,
            'timestamp' => now()->toDateTimeString(),
        ];

        $tickets[$ticketIndex]['updated_at'] = now()->toDateTimeString();

        File::put($this->ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));

        return back()->with('success', 'Message sent successfully');
    }

    // Close ticket
    public function closeTicket($ticketId)
    {
        $user = session('user');
        $tickets = $this->getAllTickets();
        $ticketIndex = null;

        foreach ($tickets as $index => $ticket) {
            if ($ticket['id'] === $ticketId && $ticket['user_id'] == $user['id']) {
                $ticketIndex = $index;
                break;
            }
        }

        if ($ticketIndex === null) {
            return back()->with('error', 'Ticket not found');
        }

        $tickets[$ticketIndex]['status'] = 'closed';
        $tickets[$ticketIndex]['updated_at'] = now()->toDateTimeString();

        File::put($this->ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));

        return back()->with('success', 'Ticket closed successfully');
    }

    // Chatbot - Send message
    public function chatbot(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->message;
        $botResponse = $this->generateBotResponse($userMessage);

        // Save chat history
        $user = session('user');
        $this->saveChatHistory($user['id'], $userMessage, $botResponse);

        return response()->json([
            'message' => $botResponse,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    // Generate bot response based on keywords
    protected function generateBotResponse($message)
    {
        $message = strtolower($message);

        // Transfer-related queries
        if (strpos($message, 'transfer') !== false || strpos($message, 'send money') !== false) {
            if (strpos($message, 'how') !== false) {
                return "To send money, go to the 'Send' page, select a beneficiary, enter the amount, and choose your transfer speed. We support multiple currencies with competitive exchange rates!";
            }
            if (strpos($message, 'fee') !== false || strpos($message, 'cost') !== false) {
                return "Transfer fees vary by currency and amount. Typically 2.5% with a minimum fee of $1. You can see the exact fee before confirming your transfer.";
            }
            if (strpos($message, 'time') !== false || strpos($message, 'long') !== false) {
                return "Transfer speeds: Standard (1-3 days), Next Day, Same Day, or Instant. Choose the speed that works best for you!";
            }
            return "Our transfer service allows you to send money worldwide quickly and securely. What specific aspect would you like to know more about?";
        }

        // Account-related queries
        if (strpos($message, 'account') !== false || strpos($message, 'profile') !== false) {
            if (strpos($message, 'verify') !== false || strpos($message, 'verification') !== false) {
                return "To verify your account, check your email for the verification link we sent during registration. If you didn't receive it, you can request a new one.";
            }
            if (strpos($message, 'password') !== false || strpos($message, 'reset') !== false) {
                return "To reset your password, click 'Forgot Password' on the login page. We'll send you a reset link via email.";
            }
            return "You can manage your account settings from your profile page. Need help with something specific?";
        }

        // Bank account queries
        if (strpos($message, 'bank') !== false) {
            if (strpos($message, 'add') !== false || strpos($message, 'link') !== false) {
                return "You can add a bank account by going to Bank Accounts → Add New Account. You'll need to verify it via email for security.";
            }
            if (strpos($message, 'verify') !== false) {
                return "Bank account verification is done via email. We'll send you a verification link to confirm ownership of the account.";
            }
            return "Bank accounts can be added and managed from the Bank Accounts section. Each account needs to be verified for security.";
        }

        // Beneficiary queries
        if (strpos($message, 'beneficiary') !== false || strpos($message, 'recipient') !== false) {
            return "You can add beneficiaries (recipients) from the Beneficiaries page. Save their details once, then send them money anytime!";
        }

        // Exchange rate queries
        if (strpos($message, 'rate') !== false || strpos($message, 'exchange') !== false) {
            return "We offer competitive exchange rates for all major currencies including USD, EUR, GBP, CAD, and LBP. Rates are updated regularly and displayed during the transfer process.";
        }

        // Security queries
        if (strpos($message, 'safe') !== false || strpos($message, 'secure') !== false || strpos($message, 'security') !== false) {
            return "Your security is our priority! We use bank-level encryption, email verification, and secure payment processing. All transfers are monitored for compliance.";
        }

        // Wallet queries
        if (strpos($message, 'wallet') !== false || strpos($message, 'balance') !== false) {
            return "Your wallet allows you to deposit funds and make quick transfers. You can deposit or withdraw money from the Wallet section.";
        }

        // Support queries
        if (strpos($message, 'support') !== false || strpos($message, 'help') !== false || strpos($message, 'contact') !== false) {
            return "You can create a support ticket from this page for any issues. Our team typically responds within 24 hours. You can also chat with me for quick answers!";
        }

        // Greeting
        if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false || strpos($message, 'hey') !== false) {
            return "Hello! 👋 I'm your SwiftPay assistant. How can I help you today? You can ask about transfers, fees, account verification, or anything else!";
        }

        // Thanks
        if (strpos($message, 'thank') !== false || strpos($message, 'thanks') !== false) {
            return "You're welcome! Is there anything else I can help you with?";
        }

        // Default response
        return "I'm here to help! You can ask me about:\n• Sending money and transfer fees\n• Account verification\n• Adding bank accounts\n• Exchange rates\n• Security features\n• Wallet management\n\nOr create a support ticket for specific issues.";
    }

    // Helper methods
    protected function getAllTickets()
    {
        if (File::exists($this->ticketsFile)) {
            return json_decode(File::get($this->ticketsFile), true);
        }
        return [];
    }

    protected function getUserTickets($userId)
    {
        $allTickets = $this->getAllTickets();
        return array_filter($allTickets, function($ticket) use ($userId) {
            return $ticket['user_id'] == $userId;
        });
    }

    protected function getTicketById($ticketId)
    {
        $tickets = $this->getAllTickets();
        foreach ($tickets as $ticket) {
            if ($ticket['id'] === $ticketId) {
                return $ticket;
            }
        }
        return null;
    }

    protected function saveChatHistory($userId, $userMessage, $botResponse)
    {
        $history = [];
        if (File::exists($this->chatHistoryFile)) {
            $history = json_decode(File::get($this->chatHistoryFile), true);
        }

        $history[] = [
            'user_id' => $userId,
            'user_message' => $userMessage,
            'bot_response' => $botResponse,
            'timestamp' => now()->toDateTimeString(),
        ];

        // Keep only last 1000 messages
        if (count($history) > 1000) {
            $history = array_slice($history, -1000);
        }

        File::put($this->chatHistoryFile, json_encode($history, JSON_PRETTY_PRINT));
    }
}
