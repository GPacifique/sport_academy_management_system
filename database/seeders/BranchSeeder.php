<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure core branches exist
        $branches = [
            ['name' => 'MASAKA', 'code' => 'MSK'],
            ['name' => 'KICUKIRO', 'code' => 'KCK'],
            ['name' => 'MWANZA', 'code' => 'MWZ'],
        ];

        foreach ($branches as $b) {
            Branch::firstOrCreate(
                ['name' => $b['name']],
                ['code' => $b['code'], 'address' => null, 'phone' => null]
            );
        }

        // Fallback default if still none exist
        if (!Branch::query()->exists()) {
            Branch::create([
                'name' => 'Main Branch',
                'code' => 'MAIN',
                'address' => null,
                'phone' => null,
            ]);
        }
    }
}
