<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Bug',
            'Feature Request',
            'Technical Issue',
            'Billing',
            'General Inquiry',
            'Hardware',
            'Software',
            'Network',
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(['name' => $category]);
        }
    }
}
