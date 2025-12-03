<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    /**
     * Send SMS via Telerivet
     */
    public function send($phone, $message)
    {
        try {
            // Validate phone format
            if (!preg_match('/^\+\d{8,15}$/', $phone)) {
                throw new \Exception("Invalid phone number format: $phone");
            }

            Log::info("Sending SMS to $phone: $message");

            $response = Http::withBasicAuth(env('TELERIVET_API_KEY'), '')
                ->post("https://api.telerivet.com/v2/projects/" . env('TELERIVET_PROJECT_ID') . "/messages/send", [
                    'to_number' => $phone,
                    'content' => $message,
                ]);

            Log::info("Telerivet Response:", $response->json());

            if (!$response->successful()) {
                throw new \Exception("API Error: " . json_encode($response->json()));
            }

            return $response->json();

        } catch (\Exception $e) {

            Log::error("SMS FAILED:", [
                'phone' => $phone,
                'message' => $message,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
