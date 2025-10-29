<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComprehensiveDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        // Get existing IDs
        $studentIds = DB::table('students')->pluck('id')->toArray();
        $coachIds = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'coach')
            ->pluck('users.id')
            ->toArray();
        $branchIds = DB::table('branches')->pluck('id')->toArray();
        $groupIds = DB::table('groups')->pluck('id')->toArray();
        $planIds = DB::table('subscription_plans')->pluck('id')->toArray();

        echo "Found: " . count($studentIds) . " students, " . count($coachIds) . " coaches\n";

        // 1. Create additional students (20 total)
        if (count($studentIds) < 20) {
            for ($i = count($studentIds); $i < 20; $i++) {
                $studentIds[] = DB::table('students')->insertGetId([
                    'first_name' => 'Student' . ($i + 1),
                    'second_name' => 'Test',
                    'dob' => $now->copy()->subYears(rand(10, 18))->format('Y-m-d'),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'parent_user_id' => null,
                    'phone' => '+25078' . rand(1000000, 9999999),
                    'status' => rand(0, 9) < 9 ? 'active' : 'inactive',
                    'joined_at' => $now->copy()->subDays(rand(1, 180)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            echo "Created " . (20 - count($studentIds)) . " additional students\n";
        }

        // 2. Create Training Sessions (last 8 weeks, 3 sessions per week)
        $sessionIds = [];
        for ($week = 0; $week < 8; $week++) {
            for ($session = 0; $session < 3; $session++) {
                $date = $now->copy()->subWeeks($week)->subDays(rand(0, 6));
                $sessionIds[] = DB::table('training_sessions')->insertGetId([
                    'date' => $date->format('Y-m-d'),
                    'start_time' => '14:00:00',
                    'end_time' => '16:00:00',
                    'location' => 'Training Ground ' . rand(1, 3),
                    'group_name' => 'Group ' . rand(1, 5),
                    'coach_user_id' => $coachIds[array_rand($coachIds)] ?? null,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
        echo "Created " . count($sessionIds) . " training sessions\n";

        // 3. Create Student Attendances (70% attendance rate)
        $attendanceCount = 0;
        $statuses = ['present', 'absent', 'late', 'excused'];
        foreach ($sessionIds as $sessionId) {
            $attendingStudents = array_rand(array_flip($studentIds), (int)(count($studentIds) * 0.7));
            foreach ((array)$attendingStudents as $studentId) {
                DB::table('student_attendances')->insert([
                    'training_session_id' => $sessionId,
                    'student_id' => $studentId,
                    'status' => $statuses[rand(0, 9) < 8 ? 0 : rand(1, 3)], // 80% present
                    'notes' => null,
                    'recorded_by_user_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $attendanceCount++;
            }
        }
        echo "Created $attendanceCount student attendance records\n";

        // 4. Create Coach Attendances
        foreach ($sessionIds as $sessionId) {
            $session = DB::table('training_sessions')->find($sessionId);
            if ($session && $session->coach_user_id) {
                DB::table('coach_attendances')->insert([
                    'training_session_id' => $sessionId,
                    'coach_user_id' => $session->coach_user_id,
                    'status' => rand(0, 9) < 9 ? 'present' : 'absent', // 90% coach attendance
                    'notes' => rand(0, 4) == 0 ? 'Great session today' : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
        echo "Created coach attendance records\n";

        // 5. Create Subscriptions (for all active students)
        $subscriptionIds = [];
        $activeStudents = array_filter($studentIds, function($id) {
            return DB::table('students')->where('id', $id)->value('status') === 'active';
        });
        
        foreach ($activeStudents as $studentId) {
            $startDate = $now->copy()->subDays(rand(1, 90));
            $subscriptionIds[] = DB::table('subscriptions')->insertGetId([
                'student_id' => $studentId,
                'subscription_plan_id' => $planIds[array_rand($planIds)] ?? 1,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $startDate->copy()->addDays(30)->format('Y-m-d'),
                'next_billing_date' => $startDate->copy()->addDays(30)->format('Y-m-d'),
                'status' => rand(0, 9) < 8 ? 'active' : 'expired',
                'created_at' => $startDate,
                'updated_at' => $now,
            ]);
        }
        echo "Created " . count($subscriptionIds) . " subscriptions\n";

        // 6. Create Payments (for subscriptions)
        $paymentMethods = ['cash', 'mobile_money', 'card', 'bank'];
        foreach ($subscriptionIds as $index => $subscriptionId) {
            $subscription = DB::table('subscriptions')->find($subscriptionId);
            DB::table('payments')->insert([
                'student_id' => $subscription->student_id,
                'subscription_id' => $subscriptionId,
                'amount_cents' => rand(5000, 20000) * 100, // 5,000 - 20,000 RWF
                'currency' => 'RWF',
                'method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => 'succeeded',
                'paid_at' => Carbon::parse($subscription->start_date)->addHours(rand(1, 48)),
                'reference' => 'PAY' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'notes' => rand(0, 4) == 0 ? 'Monthly subscription payment' : null,
                'created_at' => $subscription->created_at,
                'updated_at' => $now,
            ]);
        }
        echo "Created " . count($subscriptionIds) . " payments\n";

        // 7. Create Invoices (50% of subscriptions)
        $randomSubscriptions = DB::table('subscriptions')
            ->inRandomOrder()
            ->limit((int)(count($subscriptionIds) * 0.5))
            ->get();
            
        foreach ($randomSubscriptions as $subscription) {
            $invoiceId = DB::table('invoices')->insertGetId([
                'subscription_id' => $subscription->id,
                'amount_cents' => rand(5000, 20000) * 100,
                'currency' => 'RWF',
                'due_date' => Carbon::parse($subscription->start_date)->addDays(7)->format('Y-m-d'),
                'status' => 'paid',
                'notes' => 'Subscription payment invoice',
                'created_at' => $subscription->created_at,
                'updated_at' => $now,
            ]);
            
            // Update corresponding payment with invoice_id
            DB::table('payments')
                ->where('subscription_id', $subscription->id)
                ->update(['invoice_id' => $invoiceId]);
        }
        echo "Created " . count($randomSubscriptions) . " invoices\n";

        // 8. Create Expenses (various categories)
        $expenseCategories = [
            ['name' => 'Equipment', 'amount_range' => [50000, 500000]],
            ['name' => 'Facility Rent', 'amount_range' => [200000, 800000]],
            ['name' => 'Utilities', 'amount_range' => [30000, 150000]],
            ['name' => 'Salaries', 'amount_range' => [500000, 2000000]],
            ['name' => 'Marketing', 'amount_range' => [20000, 200000]],
            ['name' => 'Maintenance', 'amount_range' => [10000, 100000]],
        ];

        // Get a user_id for recording expenses (admin or accountant)
        $accountantId = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'accountant')
            ->value('users.id') ?? 1;

        foreach ($expenseCategories as $category) {
            for ($i = 0; $i < rand(2, 5); $i++) {
                $expenseDate = $now->copy()->subDays(rand(1, 60));
                DB::table('expenses')->insert([
                    'user_id' => $accountantId,
                    'branch_id' => $branchIds[array_rand($branchIds)] ?? null,
                    'amount_cents' => rand($category['amount_range'][0], $category['amount_range'][1]),
                    'currency' => 'RWF',
                    'category' => $category['name'],
                    'description' => $category['name'] . ' expense - ' . $expenseDate->format('M Y'),
                    'expense_date' => $expenseDate->format('Y-m-d'),
                    'vendor_name' => 'Vendor ' . rand(1, 10),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'status' => rand(0, 9) < 8 ? 'paid' : 'pending',
                    'receipt_number' => 'RCP' . rand(1000, 9999),
                    'notes' => null,
                    'approved_by' => $accountantId,
                    'approved_at' => $expenseDate,
                    'created_at' => $expenseDate,
                    'updated_at' => $now,
                ]);
            }
        }
        echo "Created expense records\n";

        // 9. Add more equipment if needed
        $equipmentTypes = [
            ['name' => 'Footballs', 'category' => 'balls', 'quantity' => 20],
            ['name' => 'Training Cones', 'category' => 'training', 'quantity' => 50],
            ['name' => 'Goal Posts', 'category' => 'facility', 'quantity' => 4],
            ['name' => 'Jerseys', 'category' => 'training', 'quantity' => 30],
            ['name' => 'First Aid Kit', 'category' => 'safety', 'quantity' => 3],
        ];

        foreach ($equipmentTypes as $equipment) {
            $exists = DB::table('equipment')->where('name', $equipment['name'])->exists();
            if (!$exists) {
                DB::table('equipment')->insert([
                    'name' => $equipment['name'],
                    'category' => $equipment['category'],
                    'quantity' => $equipment['quantity'],
                    'available_quantity' => $equipment['quantity'],
                    'condition' => rand(0, 9) < 8 ? 'good' : 'fair',
                    'purchase_price' => rand(100, 10000),
                    'purchase_date' => $now->copy()->subDays(rand(30, 365)),
                    'status' => 'available',
                    'branch_id' => $branchIds[array_rand($branchIds)] ?? null,
                    'notes' => 'Standard training equipment',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
        echo "Ensured equipment records exist\n";

        echo "\n✅ Database seeded successfully with realistic dashboard data!\n";
    }
}
