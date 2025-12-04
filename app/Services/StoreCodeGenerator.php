<?php

namespace App\Services;

use App\Models\StoreOrder;
use Illuminate\Support\Str;

class StoreCodeGenerator
{
    /**
     * Generate a unique alphanumeric code for a store order
     * Ensures no collisions by checking if code already exists
     * 
     * @param int $length Length of the code to generate (default 14 characters)
     * @return string The generated unique code
     */
    public static function generate($length = 14): string
    {
        do {
            // Generate random alphanumeric string (uppercase + numbers)
            $code = strtoupper(Str::random($length));
        } while (self::codeExists($code));

        return $code;
    }

    /**
     * Check if a code already exists in the database
     * 
     * @param string $code The code to check
     * @return bool True if code exists, false otherwise
     */
    private static function codeExists(string $code): bool
    {
        return StoreOrder::where('generated_code', $code)->exists();
    }

    /**
     * Format code for display (e.g., "ABC123-XYZ789-1234")
     * 
     * @param string $code The code to format
     * @param int $groupSize Number of characters per group (default 6)
     * @return string Formatted code
     */
    public static function formatForDisplay(string $code, int $groupSize = 6): string
    {
        return implode('-', str_split($code, $groupSize));
    }
}
