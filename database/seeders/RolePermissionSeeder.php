<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create comprehensive permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'restore users',
            'manage users',
            
            // Student Management
            'view students',
            'create students',
            'edit students',
            'delete students',
            'manage students',
            
            // Branch Management
            'view branches',
            'create branches',
            'edit branches',
            'delete branches',
            'manage branches',
            
            // Group Management
            'view groups',
            'create groups',
            'edit groups',
            'delete groups',
            'manage groups',
            
            // Training Session Management
            'view training sessions',
            'create training sessions',
            'edit training sessions',
            'delete training sessions',
            'manage training sessions',
            'view training schedule',
            
            // Subscription Plan Management
            'view subscription plans',
            'create subscription plans',
            'edit subscription plans',
            'delete subscription plans',
            'manage subscription plans',
            
            // Subscription Management
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'delete subscriptions',
            'manage subscriptions',
            
            // Invoice Management
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'manage invoices',
            
            // Payment Management
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',
            'manage payments',
            'record payments',
            
            // Financial Management
            'view finances',
            'manage finances',
            'view financial reports',
            'export financial data',
            
            // Expense Management
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'manage expenses',
            'approve expenses',
            'reject expenses',
            
            // Equipment Management
            'view equipment',
            'create equipment',
            'edit equipment',
            'delete equipment',
            'manage equipment',
            
            // Team Management
            'view teams',
            'manage teams',
            
            // Parent/Child Access
            'view child info',
            'view own invoices',
            'make payments',
            
            // Reports & Analytics
            'view reports',
            'view dashboard',
            'view analytics',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $coach = Role::firstOrCreate(['name' => 'coach', 'guard_name' => 'web']);
        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $parent = Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);

        // Get all permissions
        $allPermissions = Permission::where('guard_name', 'web')->get();

        // SUPER ADMIN: Grant ALL permissions (highest level)
        $superAdmin->syncPermissions($allPermissions);

        // ADMIN: Grant ALL permissions
        $admin->syncPermissions($allPermissions);

        // COACH: Training sessions, teams, equipment, and viewing schedules
        $coach->syncPermissions([
            'view training sessions',
            'create training sessions',
            'edit training sessions',
            'manage training sessions',
            'view training schedule',
            'view teams',
            'manage teams',
            'view students',
            'view groups',
            'view equipment',
            'view dashboard',
        ]);

        // ACCOUNTANT: Financial operations, invoices, payments, subscriptions, expenses, equipment
        $accountant->syncPermissions([
            'view finances',
            'manage finances',
            'view financial reports',
            'export financial data',
            'view invoices',
            'create invoices',
            'edit invoices',
            'manage invoices',
            'view payments',
            'create payments',
            'record payments',
            'manage payments',
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'manage subscriptions',
            'view subscription plans',
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'manage expenses',
            'approve expenses',
            'reject expenses',
            'view equipment',
            'view students',
            'view dashboard',
            'view reports',
            'view analytics',
        ]);

        // PARENT: View child info, invoices, make payments, view schedule
        $parent->syncPermissions([
            'view child info',
            'view own invoices',
            'make payments',
            'view training schedule',
            'view dashboard',
        ]);

        // Assign roles to users
        // Ensure there is a super admin user
        $superAdminEmail = 'superadmin@example.com';
        $superAdminUser = User::firstOrCreate(
            ['email' => $superAdminEmail],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );
        $superAdminUser->syncRoles(['super-admin']);

        // Ensure there is an admin user
        $adminEmail = 'admin@example.com';
        $adminUser = User::firstOrCreate(
            ['email' => $adminEmail],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        $adminUser->syncRoles(['admin']);

        // Clear cache again after seeding
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
