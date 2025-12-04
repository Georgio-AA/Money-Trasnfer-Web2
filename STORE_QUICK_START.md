# 🚀 Store Feature - Quick Start Guide

## 5-Minute Setup

### Step 1: Run Migrations (30 seconds)
```bash
php artisan migrate
```

### Step 2: Seed Initial Products (30 seconds)
```bash
php artisan db:seed --class=StoreProductSeeder
```

Or seed everything including other seeders:
```bash
php artisan db:seed
```

**Done!** 15 products are now in your database.

---

## Access the Feature

### For Users
📍 Go to: `http://localhost/money-transfer2/WebProject/public/store`
- Browse digital services
- Make purchases
- View purchase history
- Copy redemption codes

### For Admins
📍 Go to: `http://localhost/money-transfer2/WebProject/public/admin/store/products`
- Manage products
- View orders
- Create/edit products
- Monitor purchases

---

## Default Products Available

### 🏪 Mobile Recharge
- MTC: $10, $20, $50
- Alfa: $10, $20, $50

### 🎬 Streaming
- Netflix: $9.99/month
- Disney+: $7.99/month

### 🎵 Music
- Anghami: $4.99/month
- Spotify: $10.99/month
- Apple Music: $10.99/month

### 📺 TV
- Cablevision: $30/month

### 🎮 Gaming
- PlayStation Plus: $9.99/month
- Xbox Game Pass: $10.99/month

---

## Test the Purchase Flow

1. **Login** as a user
2. **Go to Store** - `/store`
3. **Check Balance** - See your current balance at top
4. **Select Product** - Click "Buy" button
5. **See Confirmation** - Your redemption code is displayed
6. **Copy Code** - Click "Copy" button to copy to clipboard
7. **View History** - Click "My Purchases" to see all orders

---

## Admin Panel Walkthrough

### View Products
1. Navigate to `/admin/store/products`
2. See all products with statistics
3. View total orders per product

### Create Product
1. Click "Add Product" button
2. Fill in product details:
   - Name: "Netflix 3 Months"
   - Provider: "Netflix"
   - Category: "Streaming"
   - Price: "29.97"
   - Status: "Active"
3. Click "Create Product"

### Edit Product
1. Click edit icon on product row
2. Modify details as needed
3. Click "Save Changes"

### Deactivate Product
1. Click eye-slash icon to deactivate
2. Product no longer appears in user store
3. Click again to reactivate

### View All Orders
1. Click "View All Orders" button
2. See all customer purchases
3. View redemption codes and amounts

---

## Key Features Explained

### 💰 Balance System
- Users need sufficient balance to purchase
- Balance is deducted immediately upon purchase
- Users can add funds via their profile

### 🔐 Unique Codes
- Every purchase generates a unique 14-character code
- Format: ABC123-XYZ789-1234
- Code never repeats (guaranteed uniqueness)
- Users can copy codes to clipboard for easy sharing

### 📊 Purchase History
- Users can view all past purchases
- Shows product, amount, date, and status
- Codes available for quick copying
- Paginated for performance

### 🛡️ Data Safety
- Purchases use database transactions
- Balance only deducted if purchase completes
- No partial purchases possible
- Product prices can be changed without affecting past orders

---

## Common Tasks

### Add New Provider
1. Go to admin store
2. Click "Create Product"
3. Enter provider name (e.g., "Hulu")
4. Select category
5. Set price
6. Save

(No code changes needed!)

### Bulk Import Products
Edit `database/seeders/StoreProductSeeder.php`:
1. Add products to the `$products` array
2. Run: `php artisan db:seed --class=StoreProductSeeder`

### Check Purchase History
1. User navigates to `/store/my-purchases`
2. See all purchases with codes
3. Copy codes with one click

### Monitor Orders
1. Admin goes to `/admin/store/orders`
2. See all customer purchases
3. Track revenue and popular products

---

## Troubleshooting

### Issue: Database Error During Seed
**Solution:**
```bash
php artisan migrate:fresh --seed
```
This resets all migrations and seeds from scratch.

### Issue: Route Not Found
**Solution:**
```bash
php artisan route:clear
php artisan cache:clear
```

### Issue: Insufficient Balance Warning
**Solution:**
- User needs to add funds to their account first
- Products showing as "Insufficient" button means balance is too low

### Issue: Can't Delete Product
**Solution:**
- Products with existing orders can't be deleted
- Click the toggle button to deactivate instead

---

## Files in This Feature

### Backend
- `app/Models/StoreProduct.php` - Product model
- `app/Models/StoreOrder.php` - Order model
- `app/Http/Controllers/StoreController.php` - User controller
- `app/Http/Controllers/Admin/StoreProductController.php` - Admin controller
- `app/Services/StoreCodeGenerator.php` - Code generation service

### Database
- `database/migrations/2025_12_03_create_store_products_table.php`
- `database/migrations/2025_12_03_create_store_orders_table.php`
- `database/seeders/StoreProductSeeder.php`

### Views (7 files)
- `resources/views/store/index.blade.php` - Product catalog
- `resources/views/store/confirmation.blade.php` - Purchase confirmation
- `resources/views/store/my_purchases.blade.php` - Purchase history
- `resources/views/admin/store/products/index.blade.php` - Admin product list
- `resources/views/admin/store/products/create.blade.php` - Create product form
- `resources/views/admin/store/products/edit.blade.php` - Edit product form
- `resources/views/admin/store/orders/index.blade.php` - View all orders

### Routes
- 4 user routes in `/store` prefix
- 8 admin routes in `/admin/store` prefix

---

## Next Steps

### For Development
1. ✅ Run migrations and seeders (already done)
2. 🔄 Test user purchase flow
3. 🔄 Test admin management
4. 🔄 Customize product list as needed
5. 🔄 Add any additional providers

### For Production
1. Update payment/balance system integration
2. Configure provider APIs if needed
3. Set up email notifications
4. Test with real user data
5. Monitor purchase patterns
6. Gather user feedback

---

## Support

For detailed documentation, see:
- `STORE_FEATURE_GUIDE.md` - Complete feature documentation
- `STORE_IMPLEMENTATION_CHECKLIST.md` - Implementation reference
- `STORE_COMPLETE_SUMMARY.md` - Technical overview

---

## Questions?

Check controller files for additional options or modify seeder for custom products.

**Ready to launch!** 🎉
