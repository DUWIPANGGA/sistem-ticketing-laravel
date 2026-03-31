<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'g',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $tech = User::factory()->create([
            'name' => 'IT Technician',
            'email' => 'tech@tech.com',
            'password' => bcrypt('password'),
            'role' => 'technician',
        ]);

        \App\Models\KnowledgeBaseArticle::create([
            'title' => 'Panduan Manajemen SLA dan Estimasi Waktu Penyelesaian',
            'content' => "## Apa itu SLA (Service Level Agreement)?\nSLA adalah perjanjian tingkat layanan yang menentukan batas waktu maksimum bagi tim IT (Admin & Teknisi) untuk menyelesaikan sebuah tiket berdasarkan prioritasnya.\n\nBatasan SLA standar kami:\n- **Urgent**: 1 Jam\n- **High**: 4 Jam\n- **Medium**: 24 Jam\n- **Low**: 48 Jam\n\n## Bagaimana Estimasi Waktu Penyelesaian Bekerja?\nEstimasi sistem kini bekerja secara **otomatis dan dinamis**. Sistem kecerdasan buatan membaca histori perbaikan (*log penyelesaian*) dari semua tiket yang ditutup oleh teknisi sebelumnya. Kemudian, sistem secara pintar merata-rata waktu aktual (*average resolution time*) bagi setiap jenis kategori atau prioritas tiket, untuk menyuguhkan estimasi waktu yang lebih akurat daripada sekedar mengandalkan nilai batas maksimal SLA.\n\nIni berarti, semakin cepat teknisi kita rata-rata menyelesaikan tiket tipe _Medium_, kemungkinan besar estimasi penyelesaian tiket Anda berikutnya juga akan tertulis lebih cepat (tidak selalu harus 24 jam!).",
            'is_published' => true,
            'category' => 'General',
            'author_id' => $tech->id,
        ]);

        $this->call([
            NotificationSeeder::class,
        ]);
    }
}
