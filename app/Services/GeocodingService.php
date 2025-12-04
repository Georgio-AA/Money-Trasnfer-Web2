<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function geocode(string $address, string $city = '', string $country = ''): ?array
    {
        $query = trim(implode(', ', array_filter([$address, $city, $country])));
        if ($query === '') {
            return null;
        }

        $response = Http::withHeaders([
                'User-Agent' => 'SwiftPay/1.0 (contact: support@swiftpay.example)'
            ])
            ->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => 1,
            ]);

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();
        if (is_array($data) && isset($data[0]['lat'], $data[0]['lon'])) {
            return [
                'latitude' => (float) $data[0]['lat'],
                'longitude' => (float) $data[0]['lon'],
            ];
        }

        return null;
    }
}
