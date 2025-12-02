<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    /**
     * Send SMS message via Telerivet API
     *
     * @param string $phone Phone number with country code (e.g., +1234567890)
     * @param string $message Message content to send
     * @return array API response
     * @throws \Exception
     */
    public function send($phone, $message)
    {
        try {
            // Validate phone number format
            if (!preg_match('/^\+\d{1,3}\d{1,14}$/', $phone)) {
                throw new \Exception("Invalid phone number format. Expected format: +<country_code><phone_number>");
            }

            // Validate message length (SMS limit is typically 160 chars)
            if (strlen($message) > 160) {
                Log::warning('SMS message exceeds 160 characters', [
                    'length' => strlen($message),
                    'message' => $message,
                ]);
            }

            $response = Http::withBasicAuth(env('TELERIVET_API_KEY'), '')
                ->asForm()
                ->post("https://api.telerivet.com/v1/projects/" . env('TELERIVET_PROJECT_ID') . "/messages/send", [
                    'phone_number' => $phone,
                    'content' => $message
                ]);

            // Check for successful response
            if ($response->successful()) {
                return $response->json();
            }

            // Handle API errors
            $errorMessage = "SMS API Error: " . $response->status();
            if ($response->json('error_message')) {
                $errorMessage .= " - " . $response->json('error_message');
            }

            throw new \Exception($errorMessage);

        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'phone' => $phone,
                'message' => substr($message, 0, 50) . '...',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

