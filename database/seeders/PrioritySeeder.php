<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        Priority::updateOrCreate(['value' => 'low'], ['name' => 'Low', 'sla_hours' => 48, 'sort_order' => 0]);
        Priority::updateOrCreate(['value' => 'medium'], ['name' => 'Medium', 'sla_hours' => 24, 'sort_order' => 1]);
        Priority::updateOrCreate(['value' => 'high'], ['name' => 'High', 'sla_hours' => 4, 'sort_order' => 2]);
        Priority::updateOrCreate(['value' => 'urgent'], ['name' => 'Urgent', 'sla_hours' => 1, 'sort_order' => 3]);
    }
}
