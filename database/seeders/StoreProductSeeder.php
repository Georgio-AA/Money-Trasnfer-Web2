<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreProduct;

class StoreProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products first (optional - comment out if you want to keep existing)
        // StoreProduct::truncate();

        $products = [
            // ========== MOBILE RECHARGE - MTC ==========
            [
                'name' => 'MTC Recharge $5',
                'provider' => 'MTC',
                'category' => 'mobile_recharge',
                'price' => 5.00,
                'description' => 'Instant mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'MTC Recharge $10',
                'provider' => 'MTC',
                'category' => 'mobile_recharge',
                'price' => 10.00,
                'description' => 'MTC mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'MTC Recharge $20',
                'provider' => 'MTC',
                'category' => 'mobile_recharge',
                'price' => 20.00,
                'description' => 'MTC mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'MTC Recharge $50',
                'provider' => 'MTC',
                'category' => 'mobile_recharge',
                'price' => 50.00,
                'description' => 'MTC mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'MTC Recharge $100',
                'provider' => 'MTC',
                'category' => 'mobile_recharge',
                'price' => 100.00,
                'description' => 'MTC mobile recharge credit',
                'is_active' => true,
            ],

            // ========== MOBILE RECHARGE - ALFA ==========
            [
                'name' => 'Alfa Recharge $5',
                'provider' => 'Alfa',
                'category' => 'mobile_recharge',
                'price' => 5.00,
                'description' => 'Instant mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'Alfa Recharge $10',
                'provider' => 'Alfa',
                'category' => 'mobile_recharge',
                'price' => 10.00,
                'description' => 'Alfa mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'Alfa Recharge $20',
                'provider' => 'Alfa',
                'category' => 'mobile_recharge',
                'price' => 20.00,
                'description' => 'Alfa mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'Alfa Recharge $50',
                'provider' => 'Alfa',
                'category' => 'mobile_recharge',
                'price' => 50.00,
                'description' => 'Alfa mobile recharge credit',
                'is_active' => true,
            ],
            [
                'name' => 'Alfa Recharge $100',
                'provider' => 'Alfa',
                'category' => 'mobile_recharge',
                'price' => 100.00,
                'description' => 'Alfa mobile recharge credit',
                'is_active' => true,
            ],

            // ========== STREAMING SERVICES ==========
            [
                'name' => 'Netflix Basic 1 Month',
                'provider' => 'Netflix',
                'category' => 'streaming',
                'price' => 6.99,
                'description' => 'Netflix Basic plan - SD quality, 1 screen',
                'is_active' => true,
            ],
            [
                'name' => 'Netflix Standard 1 Month',
                'provider' => 'Netflix',
                'category' => 'streaming',
                'price' => 10.99,
                'description' => 'Netflix Standard - HD quality, 2 screens',
                'is_active' => true,
            ],
            [
                'name' => 'Netflix Premium 1 Month',
                'provider' => 'Netflix',
                'category' => 'streaming',
                'price' => 14.99,
                'description' => 'Netflix Premium - 4K Ultra HD, 4 screens',
                'is_active' => true,
            ],
            [
                'name' => 'Disney+ 1 Month',
                'provider' => 'Disney+',
                'category' => 'streaming',
                'price' => 7.99,
                'description' => 'Disney+ streaming service - Movies & Series',
                'is_active' => true,
            ],
            [
                'name' => 'Amazon Prime Video 1 Month',
                'provider' => 'Amazon',
                'category' => 'streaming',
                'price' => 8.99,
                'description' => 'Amazon Prime Video subscription',
                'is_active' => true,
            ],
            [
                'name' => 'Shahid Plus 1 Month',
                'provider' => 'Shahid',
                'category' => 'streaming',
                'price' => 5.99,
                'description' => 'Middle East streaming platform - Arabic content',
                'is_active' => true,
            ],

            // ========== MUSIC SERVICES ==========
            [
                'name' => 'Spotify Premium 1 Month',
                'provider' => 'Spotify',
                'category' => 'music',
                'price' => 10.99,
                'description' => 'Spotify Premium - Unlimited music streaming',
                'is_active' => true,
            ],
            [
                'name' => 'Apple Music 1 Month',
                'provider' => 'Apple Music',
                'category' => 'music',
                'price' => 10.99,
                'description' => 'Apple Music subscription - 100M+ songs',
                'is_active' => true,
            ],
            [
                'name' => 'Anghami Premium 1 Month',
                'provider' => 'Anghami',
                'category' => 'music',
                'price' => 4.99,
                'description' => 'Anghami Premium - Arabic music streaming',
                'is_active' => true,
            ],
            [
                'name' => 'YouTube Music Premium 1 Month',
                'provider' => 'YouTube',
                'category' => 'music',
                'price' => 10.99,
                'description' => 'YouTube Music Premium - Ad-free music',
                'is_active' => true,
            ],
            [
                'name' => 'Deezer Premium 1 Month',
                'provider' => 'Deezer',
                'category' => 'music',
                'price' => 9.99,
                'description' => 'Deezer Premium - 73M+ songs streaming',
                'is_active' => true,
            ],

            // ========== GAMING SUBSCRIPTIONS ==========
            [
                'name' => 'PlayStation Plus Essential 1 Month',
                'provider' => 'PlayStation',
                'category' => 'gaming',
                'price' => 9.99,
                'description' => 'PS Plus Essential - Online gaming & games',
                'is_active' => true,
            ],
            [
                'name' => 'PlayStation Plus Extra 1 Month',
                'provider' => 'PlayStation',
                'category' => 'gaming',
                'price' => 13.99,
                'description' => 'PS Plus Extra - Premium games catalog',
                'is_active' => true,
            ],
            [
                'name' => 'Xbox Game Pass 1 Month',
                'provider' => 'Xbox',
                'category' => 'gaming',
                'price' => 10.99,
                'description' => 'Xbox Game Pass - 100+ games unlimited',
                'is_active' => true,
            ],
            [
                'name' => 'Xbox Game Pass Ultimate 1 Month',
                'provider' => 'Xbox',
                'category' => 'gaming',
                'price' => 16.99,
                'description' => 'Xbox Game Pass Ultimate - All games + Gold',
                'is_active' => true,
            ],
            [
                'name' => 'Nintendo Switch Online 1 Month',
                'provider' => 'Nintendo',
                'category' => 'gaming',
                'price' => 3.99,
                'description' => 'Nintendo Switch Online - Online gaming',
                'is_active' => true,
            ],

            // ========== TV & CABLE ==========
            [
                'name' => 'Cablevision Package 1 Month',
                'provider' => 'Cablevision',
                'category' => 'tv',
                'price' => 30.00,
                'description' => 'TV + Internet + Phone package',
                'is_active' => true,
            ],
            [
                'name' => 'OSN Premium 1 Month',
                'provider' => 'OSN',
                'category' => 'tv',
                'price' => 19.99,
                'description' => 'OSN channels - Movies, series & sports',
                'is_active' => true,
            ],
            [
                'name' => 'Etisalat Digital TV 1 Month',
                'provider' => 'Etisalat',
                'category' => 'tv',
                'price' => 25.00,
                'description' => 'Etisalat digital TV subscription',
                'is_active' => true,
            ],

            // ========== OTHER SERVICES ==========
            [
                'name' => 'Adobe Creative Cloud 1 Month',
                'provider' => 'Adobe',
                'category' => 'other',
                'price' => 29.99,
                'description' => 'Adobe CC - Photoshop, Premiere, etc',
                'is_active' => true,
            ],
            [
                'name' => 'Canva Pro 1 Month',
                'provider' => 'Canva',
                'category' => 'other',
                'price' => 14.99,
                'description' => 'Canva Pro - Design tool unlimited',
                'is_active' => true,
            ],
            [
                'name' => 'WPS Office Premium 1 Year',
                'provider' => 'WPS',
                'category' => 'other',
                'price' => 19.99,
                'description' => 'WPS Office - Document editor suite',
                'is_active' => true,
            ],
            [
                'name' => 'Grammarly Premium 1 Month',
                'provider' => 'Grammarly',
                'category' => 'other',
                'price' => 12.00,
                'description' => 'Grammarly Premium - Writing assistant',
                'is_active' => true,
            ],
        ];

        // Check if products already exist to avoid duplicates
        foreach ($products as $product) {
            StoreProduct::firstOrCreate(
                ['name' => $product['name'], 'provider' => $product['provider']],
                $product
            );
        }

        $this->command->info("\n✓ Store products seeded successfully!");
        $this->command->info("✓ Created/Updated " . count($products) . " products");
        $this->command->info("✓ Categories: Mobile Recharge, Streaming, Music, Gaming, TV, Other\n");
    }
}
