<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['name' => 'IT', 'description' => 'Information Technology'],
            ['name' => 'HRD', 'description' => 'Human Resources Development'],
            ['name' => 'Keuangan', 'description' => 'Finance & Accounting'],
            ['name' => 'Marketing', 'description' => 'Marketing & Sales'],
            ['name' => 'Operasional', 'description' => 'Operations'],
        ];

        foreach ($divisions as $div) {
            Division::firstOrCreate(['name' => $div['name']], ['description' => $div['description']]);
        }
    }
}
