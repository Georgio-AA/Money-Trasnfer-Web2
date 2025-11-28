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
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access support');
        }
        
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
        $message = strtolower(trim($message));
        
        // Remove extra spaces
        $message = preg_replace('/\s+/', ' ', $message);

        // Check for specific questions first (most specific to least specific)
        
        // Greeting (check first to respond quickly)
        if (preg_match('/^(hello|hi|hey|good morning|good afternoon|good evening|greetings)/', $message)) {
            $responses = [
                "Hello! 👋 I'm your SwiftPay assistant. How can I help you today?",
                "Hi there! 😊 Welcome to SwiftPay support. What would you like to know?",
                "Hey! 👋 I'm here to help with any questions about transfers, accounts, or anything else!",
            ];
            return $responses[array_rand($responses)];
        }
        
        // Thanks
        if (preg_match('/(thank you|thanks|thx|appreciate)/', $message)) {
            $responses = [
                "You're welcome! 😊 Anything else I can help with?",
                "Happy to help! Is there anything else you'd like to know?",
                "My pleasure! Feel free to ask if you have more questions.",
            ];
            return $responses[array_rand($responses)];
        }
        
        // Goodbye
        if (preg_match('/(bye|goodbye|see you|good night|cya)/', $message)) {
            return "Goodbye! 👋 Feel free to come back anytime you need help. Have a great day!";
        }

        // HOW TO SEND MONEY - Most common question
        if (preg_match('/(how.*send|how.*transfer|send.*money|make.*transfer|steps.*send)/', $message)) {
            return "📝 **How to Send Money (Step-by-Step):**\n\n1. Click 'Send Money' in the navigation menu\n2. Select or add a beneficiary (recipient)\n3. Enter the amount you want to send\n4. Choose your currency (USD, EUR, GBP, CAD, LBP, etc.)\n5. Select transfer speed (Instant, Same Day, Next Day, or Standard)\n6. Review the exchange rate and fees\n7. Confirm your transfer\n\n💡 **Pro Tip:** Add beneficiaries once, then send them money quickly anytime!\n\nNeed help with any specific step?";
        }

        // FEES - Very common question
        if (preg_match('/(what.*fee|how much.*cost|transfer.*fee|fee.*transfer|charge|price|cost.*transfer)/', $message) && !preg_match('/(rate|exchange)/', $message)) {
            return "💰 **Transfer Fees Explained:**\n\n• **Base Fee:** 2.5% of transfer amount\n• **Minimum:** $1 USD (varies by currency)\n• **Maximum:** $50 USD (varies by currency)\n\n**Speed Multipliers:**\n• Standard (1-3 days): 1x - No extra charge ✅\n• Next Day: 1.2x fee\n• Same Day: 1.5x fee  \n• Instant: 2x fee\n\n**Example:**\n$100 transfer = $2.50 base fee\n• Standard: $2.50\n• Instant: $5.00\n\n💡 **You'll see the exact fee BEFORE confirming!**\n\nWant to know about a specific currency?";
        }

        // TRANSFER SPEED/TIME
        if (preg_match('/(how long|how fast|transfer.*time|speed|instant|same day|next day|standard)/', $message) && preg_match('/(transfer|send|money)/', $message)) {
            return "⏱️ **Transfer Speed Options:**\n\n🐢 **Standard** (1-3 business days)\n   • Lowest fee (base rate)\n   • Best for non-urgent transfers\n\n📅 **Next Day** (Next business day)\n   • 1.2x fee\n   • Arrives tomorrow\n\n⚡ **Same Day** (Within 24 hours)\n   • 1.5x fee\n   • Arrives today\n\n🚀 **Instant** (Minutes)\n   • 2x fee  \n   • Arrives almost immediately\n\n💡 **Choose based on your urgency!** Faster speeds cost more but money arrives quicker.\n\nWhat speed works best for you?";
        }

        // EXCHANGE RATES
        if (preg_match('/(exchange rate|currency rate|conversion|usd.*eur|eur.*usd|rate.*currency)/', $message)) {
            return "💱 **Exchange Rates:**\n\nWe support multiple currencies:\n• 🇺🇸 USD (US Dollar)\n• 🇪🇺 EUR (Euro)\n• 🇬🇧 GBP (British Pound)\n• 🇨🇦 CAD (Canadian Dollar)\n• 🇱🇧 LBP (Lebanese Pound)\n• 🇯🇵 JPY (Japanese Yen)\n• And more!\n\n**How it works:**\n✅ Live rates updated regularly\n✅ Competitive market rates\n✅ See exact rate before confirming\n✅ Transparent - no hidden fees\n\n**Example:**\nSending $100 USD → EUR\n• You'll see: Exchange rate 0.92\n• Recipient gets: €92 (minus any recipient fees)\n\n� Rates change based on market conditions.\n\nNeed the current rate for a specific currency pair?";
        }

        // ACCOUNT VERIFICATION - Very important
        if (preg_match('/(verify.*account|account.*verif|email.*verif|verif.*email|confirm.*account|activation)/', $message)) {
            return "✅ **Account Verification:**\n\n**Step-by-Step:**\n1. Check your email inbox (and spam/junk folder)\n2. Look for email from SwiftPay\n3. Click the verification link in the email\n4. You'll be redirected to confirmation page\n5. Your account is now verified! ✅\n\n**Didn't receive the email?**\n• Wait 5-10 minutes (sometimes delayed)\n• Check your spam/junk folder\n• Verify you used the correct email address\n• Request a new verification link\n\n**Still having issues?**\nCreate a support ticket and we'll verify your account manually!\n\n💡 **Important:** You must verify your account to use all features.";
        }

        // PASSWORD RESET
        if (preg_match('/(forgot.*password|reset.*password|change.*password|password.*reset|cant.*login|login.*problem)/', $message)) {
            return "🔐 **Password Reset:**\n\n**Steps:**\n1. Go to the Login page\n2. Click 'Forgot Password' link\n3. Enter your registered email address\n4. Check your email for reset link\n5. Click the link (valid for 60 minutes)\n6. Enter your new password twice\n7. Log in with your new password\n\n**Password Requirements:**\n✅ Minimum 8 characters\n✅ At least one UPPERCASE letter\n✅ At least one lowercase letter\n✅ At least one number (0-9)\n✅ At least one special character (@$!%*?&)\n\n💡 **Pro Tip:** Use a password manager for strong, unique passwords!\n\n**Still can't log in?** Create a support ticket.";
        }

        // BANK ACCOUNT - Add
        if (preg_match('/(add.*bank|link.*bank|connect.*bank|new.*bank|bank.*account.*add)/', $message)) {
            return "🏦 **Adding a Bank Account:**\n\n**Step-by-Step:**\n1. Click 'My Accounts' in navigation menu\n2. Click 'Add New Account' button\n3. Fill in the form:\n   • Bank name (e.g., Chase, Bank of America)\n   • Account number\n   • Routing number / Sort code\n   • Account holder name (must match your name)\n   • Account type (Checking/Savings)\n4. Click 'Save'\n5. **Check your email** for verification link\n6. Click the verification link\n7. Your bank account is now verified! ✅\n\n**Important Notes:**\n⚠️ Account holder name must match your SwiftPay account\n⚠️ You must verify via email for security\n✅ You can add multiple bank accounts\n\n💡 Keep your bank details secure and never share them!\n\nNeed help with verification?";
        }

        // BANK ACCOUNT - Verify
        if (preg_match('/(verify.*bank|bank.*verif|bank.*email)/', $message)) {
            return "✉️ **Bank Account Verification:**\n\n**How it works:**\n1. After adding a bank account, we send you an email\n2. Check your email inbox (and spam folder)\n3. Open the email from SwiftPay\n4. Click the 'Verify Bank Account' button/link\n5. You'll be redirected to confirmation page\n6. Done! Your bank account is verified ✅\n\n**Why do we verify?**\n🔒 Security - Confirms you own the bank account\n🔒 Fraud prevention - Protects your money\n🔒 Compliance - Required by financial regulations\n\n**Didn't receive email?**\n• Check spam/junk folder\n• Wait 5-10 minutes\n• Try adding the account again\n• Create a support ticket\n\n💡 Each bank account must be verified before you can use it!";
        }

        // BENEFICIARY / RECIPIENT
        if (preg_match('/(beneficiary|beneficiaries|recipient|who.*send.*to|add.*recipient)/', $message)) {
            return "👥 **Managing Beneficiaries (Recipients):**\n\nBeneficiaries are people you send money to regularly.\n\n**How to Add:**\n1. Go to 'Beneficiaries' in menu\n2. Click 'Add New Beneficiary'\n3. Enter their information:\n   • Full name\n   • Email address (optional)\n   • Country\n   • Bank name\n   • Bank account number\n   • Routing/SWIFT code (if international)\n4. Click 'Save'\n\n**Benefits:**\n✅ Save time - enter details once\n✅ Quick transfers - select and send!\n✅ Multiple recipients - add as many as you need\n✅ Edit anytime - update their information\n\n**Pro Tips:**\n💡 Double-check account numbers before saving\n💡 Nickname them for easy identification\n💡 You can edit or delete beneficiaries anytime\n\nReady to add your first beneficiary?";
        }

        // WALLET
        if (preg_match('/(wallet|balance.*wallet|my.*balance|add.*money.*wallet|deposit|withdraw)/', $message)) {
            if (preg_match('/(deposit|add money|fund|top up|load)/', $message)) {
                return "💰 **Deposit Money to Wallet:**\n\n**How to Deposit:**\n1. Click 'My Wallet' in menu\n2. Click 'Deposit' or 'Add Money' button\n3. Enter the amount\n4. Select payment method:\n   • Credit/Debit card\n   • Bank transfer\n   • Other payment options\n5. Complete the payment\n6. Money appears in wallet instantly! ⚡\n\n**Why use wallet?**\n✅ Faster transfers\n✅ Lower fees\n✅ Instant availability\n✅ Easy to manage\n\n**Minimum deposit:** $10 USD\n**Maximum deposit:** $10,000 USD per transaction\n\n💡 Keep funds in wallet for quick transfers!";
            }
            if (preg_match('/(withdraw|cash out|take.*money|transfer.*bank)/', $message)) {
                return "💸 **Withdraw from Wallet:**\n\n**How to Withdraw:**\n1. Go to 'My Wallet'\n2. Click 'Withdraw' button\n3. Enter amount to withdraw\n4. Select your verified bank account\n5. Confirm withdrawal\n6. Money arrives in 1-3 business days\n\n**Requirements:**\n✅ Minimum withdrawal: $10 USD\n✅ Bank account must be verified\n✅ Sufficient wallet balance\n\n**Processing time:**\n• Request submitted: Instant\n• Bank processing: 1-3 business days\n• Weekends/holidays: May take longer\n\n💡 No fees for withdrawing to your bank account!\n\nNeed to verify a bank account first?";
            }
            return "💰 **Your Wallet:**\n\nYour digital wallet for easy money management!\n\n**Features:**\n• 💵 Store funds securely\n• 📤 Send money quickly\n• 📊 Track your balance\n• ⚡ Instant deposits\n• 🏦 Withdraw to your bank\n\n**Common Actions:**\n• Deposit money (add funds)\n• Withdraw money (cash out)\n• Check balance\n• View transaction history\n\nWhat would you like to do with your wallet?";
        }

        // Security queries
        if (strpos($message, 'safe') !== false || strpos($message, 'secure') !== false || strpos($message, 'security') !== false || strpos($message, 'fraud') !== false || strpos($message, 'scam') !== false) {
            return "🔒 **Your Security is Our Priority:**\n\n**We protect you with:**\n✅ Bank-level 256-bit encryption\n✅ Email verification for all accounts\n✅ Secure payment processing\n✅ AML (Anti-Money Laundering) monitoring\n✅ Transaction fraud detection\n✅ Two-step bank account verification\n\n**Your Responsibilities:**\n• Never share your password\n• Use strong, unique passwords\n• Verify email links before clicking\n• Report suspicious activity immediately\n\n**Suspicious Activity?** Create a support ticket ASAP!";
        }

        // Support queries  
        if (strpos($message, 'support') !== false || strpos($message, 'help') !== false || strpos($message, 'contact') !== false || strpos($message, 'ticket') !== false) {
            if (strpos($message, 'ticket') !== false || strpos($message, 'create') !== false) {
                return "🎫 **Create a Support Ticket:**\n\n1. Click 'Create Ticket' tab on this page\n2. Choose a subject\n3. Select category (account, transfer, payment, etc.)\n4. Set priority (low, medium, high)\n5. Describe your issue in detail\n6. Submit ticket\n\n**Response Time:**\n• High priority: Within 4 hours\n• Medium priority: Within 12 hours\n• Low priority: Within 24 hours\n\nYou'll get updates via email and can reply in the ticket!";
            }
            if (strpos($message, 'hours') !== false || strpos($message, 'time') !== false || strpos($message, 'when') !== false) {
                return "⏰ **Support Hours:**\n\n💬 **Chatbot:** 24/7 (Always available!)\n🎫 **Ticket Support:** 24/7 (We review tickets continuously)\n📧 **Email Response:** Within 24 hours\n📞 **Phone:** Coming soon!\n\n💡 For fastest help, use the chatbot for common questions or create a ticket for complex issues.";
            }
            return "💬 **Get Support:**\n\nI'm here 24/7 to answer questions! For complex issues:\n\n1. **Create a ticket** - Our team will help personally\n2. **Ask me questions** - I can answer most FAQs instantly\n\n**Common topics:**\n• Transfers and fees\n• Account verification\n• Bank accounts\n• Security concerns\n\nWhat can I help you with?";
        }

        // Greeting
        if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false || strpos($message, 'hey') !== false || strpos($message, 'good morning') !== false || strpos($message, 'good afternoon') !== false) {
            $responses = [
                "Hello! 👋 I'm your SwiftPay assistant. How can I help you today?",
                "Hi there! 😊 Welcome to SwiftPay support. What would you like to know?",
                "Hey! 👋 I'm here to help with any questions about transfers, accounts, or anything else!",
            ];
            return $responses[array_rand($responses)];
        }

        // SECURITY & FRAUD
        if (preg_match('/(security|secure|safe|fraud|scam|hack|stolen|privacy|protect)/', $message)) {
            return "🔒 **Security & Safety:**\n\nYour security is our top priority!\n\n**We protect you with:**\n✅ Bank-level encryption (SSL/TLS)\n✅ Two-factor authentication (2FA)\n✅ Fraud detection systems\n✅ Secure payment processing\n✅ Email verification for all accounts\n✅ Regular security audits\n\n**Keep yourself safe:**\n• Never share your password\n• Use strong, unique passwords\n• Enable 2FA if available\n• Verify beneficiary details before sending\n• Only use official SwiftPay website/app\n• Watch out for phishing emails\n\n**Warning signs of scams:**\n⚠️ Urgent requests for money\n⚠️ Requests to send to unknown people\n⚠️ Too-good-to-be-true offers\n⚠️ Emails asking for passwords\n\n**Suspicious activity?** Create a HIGH PRIORITY ticket immediately!\n\n💡 We'll NEVER ask for your password via email or chat.";
        }

        // SUPPORT TICKET / CONTACT
        if (preg_match('/(create.*ticket|open.*ticket|contact.*support|speak.*human|talk.*person|real.*person)/', $message)) {
            return "🎫 **Create a Support Ticket:**\n\n**When to create a ticket:**\n• Complex issues the chatbot can't solve\n• Account-specific problems\n• Technical difficulties\n• Transfer investigations\n• Security concerns\n\n**How to create:**\n1. Click the **'Create Ticket'** tab on the left\n2. Fill in the form:\n   • Subject (brief description)\n   • Category (select appropriate one)\n   • Priority (Low/Medium/High)\n   • Description (detailed explanation)\n3. Click 'Submit'\n4. Our team will respond within:\n   • High priority: 1-4 hours\n   • Medium priority: 4-12 hours\n   • Low priority: 12-24 hours\n\n**Track your tickets:**\nView all your tickets in the 'My Tickets' tab!\n\n💡 **Tip:** Be specific and include relevant details (IDs, dates, screenshots) for faster resolution!";
        }

        // PROBLEM / ERROR / ISSUE
        if (preg_match('/(problem|issue|error|not working|broken|cant|wont|doesnt work|failed)/', $message)) {
            return "😟 **Having Issues? Let's Fix It!**\n\n**Common Issues & Solutions:**\n\n**1️⃣ Can't log in:**\n• Reset your password\n• Check your email for verification link\n• Clear browser cache/cookies\n\n**2️⃣ Transfer failed:**\n• Check bank account is verified\n• Ensure sufficient funds\n• Verify beneficiary details\n\n**3️⃣ Email not received:**\n• Check spam/junk folder\n• Wait 10 minutes\n• Request new email\n\n**4️⃣ Bank account won't verify:**\n• Click the link in verification email\n• Check that email address is correct\n• Try adding account again\n\n**5️⃣ Payment declined:**\n• Check card details\n• Contact your bank\n• Try different payment method\n\n**Still not working?**\n👉 **Create a support ticket** with:\n• What you're trying to do\n• What page you're on\n• Error message (if any)\n• Screenshots (helpful!)\n\nOur team will investigate immediately! 🔍";
        }

        // MONEY / PAYMENT ISSUES
        if (preg_match('/(refund|money.*back|return.*money|cancel.*transfer)/', $message)) {
            return "💵 **Refunds & Cancellations:**\n\n**Transfer Cancellation:**\n• Can only cancel if status is 'Pending'\n• Go to 'My Transfers' → Find transfer → Click 'Cancel'\n• Refund processed automatically\n\n**Refund Timeline:**\n• Wallet balance: Instant refund\n• Credit/Debit card: 5-7 business days\n• Bank transfer: 3-5 business days\n\n**Refund Status:**\nCheck 'My Transfers' to see if refund was processed.\n\n**Refund not received?**\n1. Check your original payment method\n2. Wait full processing time\n3. Verify payment details are correct\n4. Create a support ticket with:\n   • Transfer ID\n   • Date of cancellation\n   • Payment method used\n\n💡 Refunds always go to the original payment method!\n\nNeed help tracking a refund?";
        }

        if (preg_match('/(money.*lost|money.*missing|didnt.*receive|havent.*received|where.*money|transfer.*not.*received)/', $message)) {
            return "😰 **Money Not Received?**\n\nDon't worry, we'll help you track it down!\n\n**Step 1: Check Transfer Status**\n1. Go to **'My Transfers'**\n2. Find the transfer\n3. Check the status:\n   • ✅ **Completed** - Money was sent successfully\n   • ⏳ **Processing** - Still being processed\n   • ❌ **Failed** - Transfer failed (refund issued)\n   • ⏸️ **Pending** - Awaiting approval\n\n**Step 2: If Completed**\n• Bank transfers can take 1-2 business days\n• Check with recipient's bank\n• Verify account details were correct\n• Weekends/holidays may delay delivery\n\n**Step 3: If Processing**\n• Wait for processing to complete\n• International transfers: up to 3-5 days\n• Domestic transfers: 1-2 days\n\n**Step 4: If Still Missing**\nCreate a **HIGH PRIORITY** ticket with:\n• Transfer ID (very important!)\n• Date and time sent\n• Recipient name and account\n• Amount sent\n\n🚨 We'll investigate immediately and track your money!\n\n💡 **Pro Tip:** Save your Transfer ID for easy tracking!";
        }

        if (preg_match('/(money|payment|transaction|paid)/', $message)) {
            return "💰 **Money & Payments:**\n\nI can help with:\n\n**Transfer Issues:**\n• Money not received by recipient\n• Transfer failed or pending\n• Wrong amount sent\n• Transfer taking too long\n\n**Refunds:**\n• Request refund\n• Refund status\n• Refund timeline\n\n**Payment Methods:**\n• Credit/Debit cards\n• Bank transfer\n• Wallet balance\n\n**Tracking:**\n• Find transfer status\n• Get Transfer ID\n• View history\n\nWhat specifically do you need help with?";
        }

        // Default response
        return "I'm here to help! 🤖\n\n**I can answer questions about:**\n📤 Sending money & transfer fees\n✅ Account & email verification\n🏦 Adding & verifying bank accounts\n💱 Exchange rates & currencies\n💰 Wallet deposits & withdrawals\n🔒 Security & fraud protection\n🎫 Creating support tickets\n\n**Quick tips:**\n• Ask specific questions for better answers\n• Use keywords like 'how to', 'fees', 'verify', etc.\n• Create a ticket for complex issues\n\nWhat would you like to know?";
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
