# Enhanced Subscription and Payments System - Implementation Summary

## Overview
This document summarizes the major enhancements made to the Sport Club Management System's subscription and payments functionality, implementing 5 key features:

1. **Invoices with outstanding balance tracking**
2. **Branch-aware pricing**
3. **Parent portal for payment visibility**
4. **Payment gateway integration (Flutterwave & Stripe)**
5. **CSV export with date-range filters**

## Database Changes

### New Tables Created

#### 1. `invoices` table
Tracks expected charges (what students owe):
- `subscription_id` - Links to subscription
- `amount_cents` - Invoice amount (stored in cents)
- `currency` - Default: RWF
- `due_date` - When payment is due
- `status` - pending, paid, overdue, cancelled
- `notes` - Optional notes

#### 2. `branch_plan_prices` table
Enables different pricing per branch:
- `branch_id` - The branch
- `subscription_plan_id` - The plan
- `price_cents` - Override price for this branch
- Unique constraint on (branch_id, subscription_plan_id)

#### 3. `payment_gateway_transactions` table
Tracks external payment gateway transactions:
- `payment_id` - Links to payment record
- `gateway` - flutterwave or stripe
- `transaction_id` - External transaction ID
- `amount_cents` - Transaction amount
- `status` - pending, succeeded, failed
- `metadata` - JSON field for additional data

#### 4. Updated `payments` table
Added `invoice_id` column to link payments to specific invoices.

## Models Enhanced

### Invoice Model (`app/Models/Invoice.php`)
**Key Methods:**
- `getTotalPaidAttribute()` - Computes total of succeeded payments
- `getOutstandingBalanceAttribute()` - Returns `amount_cents - total_paid`
- `markAsPaidIfFull()` - Auto-updates status to 'paid' when fully paid

**Relationships:**
- `subscription()` - Belongs to subscription
- `payments()` - Has many payments

### Subscription Model (`app/Models/Subscription.php`)
**Key Methods:**
- `getTotalInvoicedAttribute()` - Sum of all invoice amounts
- `getTotalPaidAttribute()` - Sum of all succeeded payments
- `getOutstandingBalanceAttribute()` - `total_invoiced - total_paid`

**Relationships:**
- `invoices()` - Has many invoices

### SubscriptionPlan Model (`app/Models/SubscriptionPlan.php`)
**Key Methods:**
- `getPriceForBranch(?int $branchId)` - Returns branch-specific price or default price

**Relationships:**
- `branchPrices()` - Has many branch price overrides

### Payment Model (`app/Models/Payment.php`)
**Updated:**
- Added `invoice_id` to fillable fields
- Added `invoice()` relationship
- Added `gatewayTransaction()` relationship

### New Models
- `BranchPlanPrice` - Pivot model for branch-specific pricing
- `PaymentGatewayTransaction` - External gateway transaction tracking

## Controllers

### InvoicesController (`app/Http/Controllers/Accountant/InvoicesController.php`)
Full CRUD for invoice management:
- `index()` - List invoices with student search, ordered by due date
- `create()` - Form to create new invoice
- `store()` - Save new invoice
- `edit()` - Edit existing invoice
- `update()` - Update invoice
- `destroy()` - Delete invoice

### PaymentsController (`app/Http/Controllers/Accountant/PaymentsController.php`)
**Enhanced Methods:**
- `index()` - Added date filters (from/to)
- `export()` - Streams CSV with payment data filtered by date range
- `create()` - Now includes invoice selector for unpaid/overdue invoices
- `store()` - Links payment to invoice and auto-marks invoice as paid when fully paid

### ParentController (`app/Http/Controllers/ParentController.php`)
Parent dashboard functionality:
- `index()` - Shows children with subscriptions, invoices, and payments
- `childPayments($student)` - Detailed payment history with ownership verification

### WebhooksController (`app/Http/Controllers/WebhooksController.php`)
Payment gateway webhook handlers:
- `flutterwave()` - Handles Flutterwave webhooks with signature verification
- `stripe()` - Handles Stripe payment_intent webhooks
- Both methods update transaction/payment status and mark invoices as paid

## Views

### Invoice Views
- `accountant/invoices/index.blade.php` - List all invoices with balance tracking
- `accountant/invoices/create.blade.php` - Create invoice form
- `accountant/invoices/edit.blade.php` - Edit invoice with outstanding balance display

### Parent Portal Views
- `parent/dashboard.blade.php` - Shows children cards with subscription status and balances
- `parent/child-payments.blade.php` - Detailed payment history for a child

### Payment Views Updates
- `accountant/payments/index.blade.php` - Added date filters and Export to CSV button
- `accountant/payments/create.blade.php` - Added invoice selector for linking payments to invoices

## Routes Added

### Accountant Routes (requires 'accountant' role)
```php
// Invoices
GET  /accountant/invoices
GET  /accountant/invoices/create
POST /accountant/invoices
GET  /accountant/invoices/{invoice}/edit
PUT  /accountant/invoices/{invoice}
DELETE /accountant/invoices/{invoice}

// Payment Export
GET  /accountant/payments/export
```

### Parent Routes (requires 'parent' role)
```php
GET /parent/dashboard
GET /parent/child/{student}/payments
```

### Webhook Routes (public, no auth)
```php
POST /webhooks/flutterwave
POST /webhooks/stripe
```

## Key Features

### 1. Invoice System
- Track expected charges separate from actual payments
- Auto-compute outstanding balances
- Auto-mark invoices as 'paid' when fully paid
- Support for partial payments

### 2. Branch-Aware Pricing
- Default price set at plan level
- Optional per-branch price overrides
- `getPriceForBranch($branchId)` method returns correct price

### 3. Parent Portal
- Parents can view all their children
- See subscription status and outstanding balances
- View full payment history per child
- Ownership verification prevents viewing other parents' data

### 4. Payment Gateway Integration
- Webhook handlers for Flutterwave and Stripe
- Signature verification for security
- Automatic status updates (pending → succeeded/failed)
- Links external transactions to internal payment records
- Auto-marks invoices as paid when webhooks confirm payment

### 5. CSV Export & Reporting
- Date-range filters on payments list
- Export to CSV with all payment details
- Includes student, plan, invoice, amount, method, status, reference
- Filtered export respects date range

## Financial Data Handling

All amounts stored in **cents** to avoid floating-point precision issues:
- `amount_cents` fields throughout
- Division by 100 for display: `{{ number_format($amount_cents/100, 2) }}`
- Default currency: RWF (Rwandan Franc)

## Status Flows

### Invoice Status
- `pending` - Awaiting payment
- `paid` - Fully paid
- `overdue` - Past due date, unpaid
- `cancelled` - Cancelled invoice

### Payment Status
- `pending` - Awaiting confirmation
- `succeeded` - Payment successful
- `failed` - Payment failed

### Subscription Status
- `active` - Currently active
- `paused` - Temporarily paused
- `expired` - Subscription ended
- `cancelled` - Cancelled by user

## Security Considerations

### Webhook Security
- **Flutterwave**: Verifies `verif-hash` header against secret
- **Stripe**: Uses Stripe's webhook signature verification
- Both return 401 if signature invalid

### Parent Portal Security
- Ownership verification in `childPayments()` method
- Only shows children associated with authenticated parent user
- 403 Forbidden if parent tries to access another parent's child

## Configuration Required

Add these to `.env`:
```env
# Flutterwave
FLUTTERWAVE_SECRET_HASH=your_secret_hash

# Stripe
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

## Next Steps for Production

1. **Run Migrations**: Already executed (`php artisan migrate`)

2. **Seed Sample Data** (optional):
   - Create sample invoices for existing subscriptions
   - Test parent portal with sample parent users

3. **Configure Payment Gateways**:
   - Set up Flutterwave account and obtain secret hash
   - Set up Stripe account and obtain webhook secret
   - Add webhook URLs to gateway dashboards

4. **Test Webhook Flows**:
   - Use gateway test modes to simulate payments
   - Verify status updates and invoice marking

5. **Branch Price Setup**:
   - Set default prices on plans
   - Add branch-specific overrides as needed via database or admin panel

6. **Parent User Creation**:
   - Assign 'parent' role to parent users
   - Link students to parent users (via `parent_id` if implemented, or relationship table)

## Usage Examples

### Create an Invoice
```php
Invoice::create([
    'subscription_id' => $subscription->id,
    'amount_cents' => 5000000, // 50,000 RWF
    'currency' => 'RWF',
    'due_date' => now()->addDays(30),
    'status' => 'pending',
    'notes' => 'Monthly subscription fee',
]);
```

### Check Outstanding Balance
```php
$subscription = Subscription::with('invoices', 'payments')->find($id);
$balance = $subscription->outstanding_balance; // in cents
echo "Balance due: " . number_format($balance/100, 2) . " RWF";
```

### Get Branch-Specific Price
```php
$plan = SubscriptionPlan::find($planId);
$price = $plan->getPriceForBranch($branchId); // Returns cents
```

### Record Payment with Invoice Link
```php
$payment = Payment::create([
    'student_id' => $student->id,
    'subscription_id' => $subscription->id,
    'invoice_id' => $invoice->id, // Links to invoice
    'amount_cents' => 2500000, // Partial payment
    'currency' => 'RWF',
    'method' => 'mobile_money',
    'status' => 'succeeded',
    'paid_at' => now(),
]);

// Auto-checks if invoice fully paid
$payment->invoice->markAsPaidIfFull();
```

## Testing Checklist

- [ ] Create subscription plans with default prices
- [ ] Add branch-specific price overrides
- [ ] Create subscriptions for students
- [ ] Generate invoices for subscriptions
- [ ] Record payments (partial and full)
- [ ] Verify outstanding balance calculations
- [ ] Test parent portal access (own children only)
- [ ] Test CSV export with date filters
- [ ] Simulate webhook calls (Flutterwave & Stripe)
- [ ] Verify auto-marking of paid invoices

## File Locations

### Migrations
- `database/migrations/2025_10_27_140000_create_invoices_table.php`
- `database/migrations/2025_10_27_140100_create_branch_plan_prices_table.php`
- `database/migrations/2025_10_27_140200_add_invoice_id_to_payments.php`
- `database/migrations/2025_10_27_140300_create_payment_gateway_transactions_table.php`

### Models
- `app/Models/Invoice.php`
- `app/Models/BranchPlanPrice.php`
- `app/Models/PaymentGatewayTransaction.php`
- `app/Models/Subscription.php` (enhanced)
- `app/Models/SubscriptionPlan.php` (enhanced)
- `app/Models/Payment.php` (enhanced)

### Controllers
- `app/Http/Controllers/Accountant/InvoicesController.php`
- `app/Http/Controllers/Accountant/PaymentsController.php` (enhanced)
- `app/Http/Controllers/ParentController.php` (enhanced)
- `app/Http/Controllers/WebhooksController.php`

### Views
- `resources/views/accountant/invoices/` (index, create, edit)
- `resources/views/parent/` (dashboard, child-payments)
- `resources/views/accountant/payments/` (index, create - enhanced)

### Routes
- `routes/web.php` (invoices, parent portal, webhooks routes added)

---

## Summary

This implementation transforms the Sport Club Management System from a basic subscription tracker into a comprehensive billing platform with:
- **Financial Transparency**: Parents see balances and payment history
- **Flexible Pricing**: Different prices per branch
- **Automated Reconciliation**: Invoices auto-mark as paid
- **Gateway Integration**: Webhooks for online payments
- **Reporting**: CSV exports for accounting

All code follows Laravel best practices with proper validation, authorization, and security measures.
