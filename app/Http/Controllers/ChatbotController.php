<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        $message = strtolower(trim($validated['message']));
        $sessionId = $validated['session_id'] ?? uniqid('chat_', true);
        $user = auth()->user();

        // Get response from chatbot
        $response = $this->getChatbotResponse($message, $user);

        return response()->json([
            'success' => true,
            'response' => $response['message'],
            'suggestions' => $response['suggestions'] ?? [],
            'create_ticket' => $response['create_ticket'] ?? false,
            'session_id' => $sessionId,
        ]);
    }

    public function createTicketFromChat(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category' => 'required|in:technical,billing,account,course,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'open';

        $ticket = SupportTicket::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully!',
            'ticket_id' => $ticket->id,
            'ticket_url' => route('support.show', $ticket),
        ]);
    }

    private function getChatbotResponse($message, $user)
    {
        // Greetings
        if (preg_match('/\b(hi|hello|hey|greetings|good morning|good afternoon|good evening)\b/i', $message)) {
            return [
                'message' => "Hello! 👋 I'm here to help you with your questions. How can I assist you today?",
                'suggestions' => [
                    'I need help with a course',
                    'I have a billing question',
                    'I forgot my password',
                    'I want to report a technical issue',
                ],
            ];
        }

        // Course-related queries
        if (preg_match('/\b(course|lesson|video|content|enroll|enrollment|access|unable to access|can\'t access)\b/i', $message)) {
            return [
                'message' => "I can help you with course-related questions. Are you having trouble accessing a course, or do you need help with course content?",
                'suggestions' => [
                    'I cannot access my enrolled course',
                    'Video is not playing',
                    'I want to enroll in a course',
                    'I need help with course materials',
                ],
                'create_ticket' => true,
            ];
        }

        // Billing/Payment queries
        if (preg_match('/\b(payment|billing|invoice|refund|charge|price|cost|purchase|buy|payment method|card|paypal)\b/i', $message)) {
            return [
                'message' => "I can help with billing and payment questions. What specific issue are you experiencing?",
                'suggestions' => [
                    'I need a refund',
                    'I was charged incorrectly',
                    'I want to update my payment method',
                    'I need an invoice',
                ],
                'create_ticket' => true,
            ];
        }

        // Account/Password queries
        if (preg_match('/\b(password|login|account|profile|email|username|forgot|reset|change password|update profile)\b/i', $message)) {
            return [
                'message' => "I can help with account-related issues. Are you having trouble logging in or need to update your account information?",
                'suggestions' => [
                    'I forgot my password',
                    'I want to change my email',
                    'I cannot log in',
                    'I want to update my profile',
                ],
                'create_ticket' => true,
            ];
        }

        // Technical issues
        if (preg_match('/\b(error|bug|broken|not working|issue|problem|technical|slow|crash|freeze|glitch)\b/i', $message)) {
            return [
                'message' => "I understand you're experiencing a technical issue. Let me help you create a support ticket so our technical team can assist you.",
                'suggestions' => [
                    'Create a support ticket',
                ],
                'create_ticket' => true,
            ];
        }

        // Certificate queries
        if (preg_match('/\b(certificate|certification|completion|cert|download certificate)\b/i', $message)) {
            return [
                'message' => "For certificate-related questions, you can view your certificates in your dashboard. If you've completed a course but don't see your certificate, we can help!",
                'suggestions' => [
                    'I completed a course but no certificate',
                    'I want to download my certificate',
                    'I need to verify my certificate',
                ],
                'create_ticket' => true,
            ];
        }

        // Progress/Completion queries
        if (preg_match('/\b(progress|complete|completion|finished|done|percentage|tracking)\b/i', $message)) {
            return [
                'message' => "You can check your course progress in the 'My Progress' section of your dashboard. Is there a specific issue with your progress tracking?",
                'suggestions' => [
                    'My progress is not updating',
                    'I completed lessons but progress shows 0%',
                    'I want to see my overall progress',
                ],
                'create_ticket' => true,
            ];
        }

        // Help/Support queries
        if (preg_match('/\b(help|support|assistance|need help|can you help|how do i|what is|where is)\b/i', $message)) {
            return [
                'message' => "I'm here to help! What would you like assistance with? You can ask me about courses, billing, your account, or technical issues.",
                'suggestions' => [
                    'I need help with a course',
                    'I have a billing question',
                    'I want to create a support ticket',
                    'How do I contact support?',
                ],
            ];
        }

        // Default response
        return [
            'message' => "I understand you need help. To better assist you, could you provide more details? Or I can help you create a support ticket where our support team can address your specific issue.",
            'suggestions' => [
                'Create a support ticket',
                'I need help with courses',
                'I have a billing question',
                'I have a technical issue',
            ],
            'create_ticket' => true,
        ];
    }
}

