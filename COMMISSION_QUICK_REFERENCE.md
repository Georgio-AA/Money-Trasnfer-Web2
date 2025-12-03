# Agent Commission System - Quick Reference

## 🚀 Quick Start

### Access Points
```
Admin Dashboard:  http://localhost/admin
Commissions:      http://localhost/admin/commissions
Agent Detail:     http://localhost/admin/commissions/1
Reports:          http://localhost/admin/commissions/report/view
```

### Test Credentials
```
Email:    admin@example.com
Password: password
Role:     admin
```

### Test Agent Credentials
```
Agent 1:
- Email: john.agent@example.com
- Store: City Money Transfer
- Commission: 2.5%

Agent 2:
- Email: sarah.agent@example.com
- Store: Express Transfers
- Commission: 3.0%

Agent 3:
- Email: mike.agent@example.com
- Store: Global Money Services
- Commission: 2.0%
```

---

## 📊 What You Can Do

### 1. View All Agents with Commission Summary
- Navigate to: `/admin/commissions`
- See: All agents, their commission rates, total commissions earned
- Filter: (None - overview only)

### 2. View Specific Agent Details
- Navigate to: `/admin/commissions/1` (or any agent ID)
- See: Agent info, detailed commissions, transaction breakdown
- Filter by: Daily, Weekly, Monthly, Yearly, Custom date range

### 3. Generate Advanced Reports
- Navigate to: `/admin/commissions/report/view`
- Filter by:
  - **Period**: Daily, Weekly, Monthly, Yearly, Custom
  - **Date Range**: From/To dates (for custom)
  - **Agent**: Specific agent or all
  - **Status**: Pending, Approved, Paid
  - **Method**: Percentage or Fixed
- Export: PDF or Excel

### 4. Export Commission Data
- **PDF Export**: Click "PDF" button
- **Excel Export**: Click "Excel" button
- Exports include: All filtered data + summary

---

## 🔧 Database Queries

### Get Total Commission for Agent
```sql
SELECT SUM(commission_amount) FROM agent_commissions 
WHERE agent_id = 1 AND status = 'paid';
```

### Get Commission Stats by Month
```sql
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    SUM(commission_amount) as total,
    COUNT(*) as transactions
FROM agent_commissions
GROUP BY DATE_FORMAT(created_at, '%Y-%m')
ORDER BY month DESC;
```

### Get Top 5 Agents by Earnings
```sql
SELECT 
    a.id,
    u.name,
    u.surname,
    a.store_name,
    SUM(c.commission_amount) as total_earned,
    COUNT(c.id) as transfer_count
FROM agent_commissions c
JOIN agents a ON c.agent_id = a.id
JOIN users u ON a.user_id = u.id
GROUP BY a.id
ORDER BY total_earned DESC
LIMIT 5;
```

---

## 💻 Programmatic Usage

### Create Commission from Transfer
```php
use App\Http\Controllers\Admin\CommissionController;

$commission = (new CommissionController())
    ->createCommissionForTransfer($transferId);
```

### Get Agent Commission Stats
```php
use App\Models\Commission;
use Carbon\Carbon;

$stats = Commission::forAgent($agentId)
    ->whereBetween('created_at', [
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth()
    ])
    ->sum('commission_amount');
```

### Export Commission Data
```php
use App\Services\CommissionExportService;
use App\Models\Commission;

$commissions = Commission::all();
$totals = ['total_commission' => 5000, 'total_transfers' => 100];

// Export to PDF
return CommissionExportService::exportToPDF($commissions, $totals);

// Export to Excel
return CommissionExportService::exportToExcel($commissions, $totals);
```

---

## 🗂️ File Reference

| File | Purpose | Lines |
|------|---------|-------|
| `app/Models/Commission.php` | Commission model & relationships | 91 |
| `app/Http/Controllers/Admin/CommissionController.php` | Business logic & API | 450+ |
| `app/Services/CommissionExportService.php` | Export functionality | 468 |
| `resources/views/admin/commissions/index.blade.php` | Agent list view | 178 |
| `resources/views/admin/commissions/detail.blade.php` | Agent detail view | 241 |
| `resources/views/admin/commissions/report.blade.php` | Report view | 291 |
| `database/migrations/2025_12_01_100000...` | Create table migration | - |
| `database/migrations/2025_12_01_100100...` | Add column migration | - |
| `database/seeders/TestAgentCommissionSeeder.php` | Test data seeder | - |

---

## 📈 Calculation Examples

### Percentage-Based (Default)
```
Agent Rate: 2.5%
Transfer Amount: $1,000
Commission = (1000 × 2.5) / 100 = $25.00
```

### Fixed Fee
```
Agent Rate: $5.00 (fixed)
Transfer Amount: $1,000 (ignored)
Commission = $5.00 (flat)
```

---

## 🔄 Commission Status Flow

```
PENDING (Initial State)
   ↓
APPROVED (Finance approved)
   ↓
PAID (Payment processed)
```

### Status Breakdown
- **PENDING**: New commission, awaiting approval
- **APPROVED**: Verified, ready for payout
- **PAID**: Payment completed, marked with paid_at date

---

## 📋 API Endpoints

### Commission Routes
```
GET  /admin/commissions                    List agents
GET  /admin/commissions/{agentId}          Agent detail
GET  /admin/commissions/report/view        Filtered report
GET  /admin/commissions/report/stats       JSON stats
POST /admin/commissions/mark-as-paid       Update status
GET  /admin/commissions/export/pdf         PDF export
GET  /admin/commissions/export/excel       Excel export
```

### Query Parameters
```
period=daily|weekly|monthly|yearly|custom
start_date=2025-12-01
end_date=2025-12-31
agent_id=1
status=pending|approved|paid
calculation_method=percentage|fixed
```

---

## 🧪 Testing Commands

### Run Migrations
```bash
php artisan migrate
```

### Seed Test Data
```bash
php artisan db:seed --class=TestAgentCommissionSeeder
```

### Check Database
```bash
php artisan tinker
>>> Commission::count()
>>> Commission::sum('commission_amount')
>>> Agent::find(1)->commissions
```

### View Routes
```bash
php artisan route:list --name=admin.commissions
```

---

## 🐛 Common Issues

| Issue | Solution |
|-------|----------|
| No agents showing | Create agents in `/admin/agents` first |
| No commissions | Run seeder or create via API |
| Export button not working | Ensure admin role & auth session |
| Date filter not working | Check date format (YYYY-MM-DD) |
| Pagination not showing | Need 50+ records to paginate |

---

## ✅ Features at a Glance

- ✅ Track commission per transfer
- ✅ Support percentage & fixed rates
- ✅ View all agents with summaries
- ✅ Detailed agent commission history
- ✅ Advanced filtering & reporting
- ✅ Export to PDF & Excel
- ✅ Status workflow (Pending → Approved → Paid)
- ✅ Batch operations (mark as paid)
- ✅ Real-time statistics
- ✅ Responsive UI

---

## 📞 Support

**For detailed information:** See `COMMISSION_SYSTEM_GUIDE.md`

**For implementation details:** See `COMMISSION_IMPLEMENTATION.md`

**Quick issues:**
1. Check test credentials
2. Verify admin role in database
3. Run migrations and seeders
4. Check browser console for errors
5. Review Laravel logs in `storage/logs/`

---

**Last Updated:** December 1, 2025  
**Status:** Production Ready ✅
