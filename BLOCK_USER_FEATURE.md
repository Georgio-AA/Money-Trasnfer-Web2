# Block User Feature Documentation

## Overview
Added comprehensive user blocking functionality to the fraud detection system. Administrators can now block users suspected of fraudulent activity directly from the user management panel or fraud detection dashboard.

## Features Implemented

### 1. **Block User Method** (`FraudDetectionController`)
```php
public function blockUser(Request $request, $userId)
```
**Functionality:**
- Validates reason for blocking (required, max 500 characters)
- Updates user status to 'blocked' in database
- Adds user to blocked entities list (JSON file)
- Automatically cancels all pending/processing transfers
- Returns success message with user name

**Actions Performed:**
1. ✅ Set user status to 'blocked'
2. ✅ Add to blocked_entities.json
3. ✅ Cancel all active transfers (pending/processing)
4. ✅ Log blocking action with admin name and reason

---

### 2. **Enhanced Unblock Method**
```php
public function unblockEntity(Request $request)
```
**New Functionality:**
- When unblocking a user, automatically sets status back to 'active'
- Updates database record in addition to JSON file
- Maintains consistency between database and blocked entities list

---

### 3. **Database Changes**

#### Migration: `add_status_to_users_table`
```php
$table->string('status')->default('active')->after('role');
```

**Status Values:**
- `active` - Normal user (default)
- `blocked` - Blocked by admin (fraud/security)
- `suspended` - Temporarily suspended

#### User Model Update
Added `'status'` to `$fillable` array

---

### 4. **User Interface Updates**

#### User Detail Page (`admin/users/show.blade.php`)
**Added:**
- 🚫 **Block User** button (visible if user not already blocked)
- **Blocked Status Badge** (shows if user is blocked)
- **Block User Modal** with reason input
- Confirmation dialog before blocking

**UI Features:**
- Modal overlay for blocking user
- Required reason text area (max 500 chars)
- Cancel and confirm buttons
- Displays user name in modal header
- Click outside modal to close

#### Fraud Detection Page (`admin/fraud-detection.blade.php`)
**Enhanced Blocked Users Table:**
- Now shows User ID, Name, and Email
- Added columns: Name, Email
- Better identification of blocked users
- Confirmation dialog before unblocking

---

### 5. **Routes Added**
```php
Route::post('/fraud/block-user/{userId}', [FraudDetectionController::class, 'blockUser'])
    ->name('admin.fraud.block-user');
```

---

## Usage Guide

### For Administrators

#### **Blocking a User:**

1. **From User Management:**
   - Navigate to Admin Panel → Users
   - Click on user to view details
   - Click "🚫 Block User" button
   - Enter reason for blocking
   - Confirm action

2. **From Fraud Detection:**
   - When reviewing fraud alerts
   - Click "Block" action on suspicious alert
   - User automatically added to blocked entities

#### **Unblocking a User:**

1. Navigate to Admin Panel → Fraud Detection
2. Switch to "🚫 Blocked Entities" tab
3. Find the user in "Blocked Users" table
4. Click "Unblock" button
5. Confirm action

---

## Technical Details

### Blocked Entities Storage
**Location:** `storage/app/private/blocked_entities.json`

**Structure:**
```json
{
  "users": [
    {
      "value": 123,
      "name": "John Doe",
      "email": "john@example.com",
      "reason": "Multiple suspicious transactions detected",
      "blocked_at": "2025-11-28 12:05:00",
      "blocked_by": "Admin Name"
    }
  ],
  "ips": [...],
  "devices": [...],
  "emails": [...],
  "phones": [...]
}
```

### Automatic Actions When Blocking
1. **User Status:** Changed to 'blocked'
2. **Active Transfers:** All pending/processing transfers set to 'fraud_blocked'
3. **Blocked List:** User added to blocked entities
4. **Audit Trail:** Blocking action logged with timestamp and admin

### Automatic Actions When Unblocking
1. **User Status:** Changed back to 'active'
2. **Blocked List:** User removed from blocked entities
3. **Audit Trail:** Unblocking action logged

---

## Security Considerations

✅ **Authorization:** Only admins can block/unblock users
✅ **Validation:** Reason is required (prevents accidental blocking)
✅ **Audit Trail:** All actions logged with timestamp and admin name
✅ **Transfer Safety:** Active transfers cancelled immediately
✅ **Confirmation:** UI requires confirmation before blocking
✅ **Self-Protection:** Admins cannot delete themselves (existing check)

---

## Testing Checklist

- [x] Block user from user detail page
- [x] Verify user status changes to 'blocked' in database
- [x] Verify user appears in blocked entities list
- [x] Verify pending transfers are cancelled
- [x] Unblock user and verify status changes to 'active'
- [x] Verify blocked user badge displays correctly
- [x] Verify modal opens/closes properly
- [x] Verify form validation (required reason)
- [x] Verify user name and email display in blocked list
- [x] Verify confirmation dialogs work

---

## Files Modified

1. ✅ `app/Http/Controllers/Admin/FraudDetectionController.php`
   - Added `blockUser()` method
   - Enhanced `unblockEntity()` method
   - Enriched blocked users with name/email in `index()`

2. ✅ `app/Models/User.php`
   - Added `'status'` to `$fillable`

3. ✅ `routes/web.php`
   - Added `/fraud/block-user/{userId}` route

4. ✅ `resources/views/admin/users/show.blade.php`
   - Added block user button
   - Added block user modal
   - Added blocked status badge
   - Added JavaScript for modal

5. ✅ `resources/views/admin/fraud-detection.blade.php`
   - Enhanced blocked users table with name/email columns
   - Added confirmation for unblock action

6. ✅ `database/migrations/2025_11_28_120557_add_status_to_users_table.php`
   - New migration to add status column

---

## Future Enhancements (Optional)

- [ ] Email notification to user when blocked
- [ ] Temporary suspension (time-based)
- [ ] Block reason categories/templates
- [ ] Block history log per user
- [ ] Bulk block/unblock operations
- [ ] IP blocking from user activity
- [ ] Automatic blocking based on fraud score threshold

---

## API Endpoints

### Block User
```
POST /admin/fraud/block-user/{userId}
Body: { reason: "string" }
Auth: Admin only
```

### Unblock User
```
POST /admin/fraud/unblock
Body: { type: "user", value: userId }
Auth: Admin only
```

---

**Implementation Date:** November 28, 2025  
**Status:** ✅ Complete and Tested  
**Version:** 1.0
