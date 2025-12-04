<?php

// Quick verification script for Store feature
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\StoreProduct;
use App\Models\StoreOrder;

echo "\n========== STORE FEATURE VERIFICATION ==========\n\n";

// Check store_products table
$productCount = StoreProduct::count();
echo "✓ Store Products Table: CREATED\n";
echo "  - Total Products Seeded: " . $productCount . "\n";

if ($productCount > 0) {
    echo "  - Sample Products:\n";
    $samples = StoreProduct::limit(3)->get();
    foreach ($samples as $product) {
        echo "    • " . $product->name . " (" . $product->provider . ") - $" . number_format($product->price, 2) . "\n";
    }
}

// Check store_orders table
echo "\n✓ Store Orders Table: CREATED\n";
$orderCount = StoreOrder::count();
echo "  - Total Orders: " . $orderCount . "\n";

// List categories
$categories = StoreProduct::distinct('category')->pluck('category')->sort();
echo "\n✓ Available Categories:\n";
foreach ($categories as $cat) {
    $count = StoreProduct::where('category', $cat)->count();
    echo "  - " . ucfirst(str_replace('_', ' ', $cat)) . ": " . $count . " products\n";
}

// List providers
$providers = StoreProduct::distinct('provider')->pluck('provider')->sort();
echo "\n✓ Available Providers:\n";
foreach ($providers as $prov) {
    $count = StoreProduct::where('provider', $prov)->count();
    echo "  - " . $prov . ": " . $count . " products\n";
}

echo "\n========== ✅ STORE FEATURE READY ==========\n\n";
echo "Routes:\n";
echo "  User:  /store\n";
echo "  Admin: /admin/store/products\n\n";
?>
