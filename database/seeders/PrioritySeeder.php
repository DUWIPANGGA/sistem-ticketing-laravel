<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        Priority::create(['name' => 'Low', 'value' => 'low', 'sla_hours' => 48, 'sort_order' => 0]);
        Priority::create(['name' => 'Medium', 'value' => 'medium', 'sla_hours' => 24, 'sort_order' => 1]);
        Priority::create(['name' => 'High', 'value' => 'high', 'sla_hours' => 4, 'sort_order' => 2]);
        Priority::create(['name' => 'Urgent', 'value' => 'urgent', 'sla_hours' => 1, 'sort_order' => 3]);
    }
}
