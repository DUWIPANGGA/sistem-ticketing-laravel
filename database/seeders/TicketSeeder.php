<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Division;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    protected \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('id_ID');
    }

    public function run(): void
    {
        $categories = Category::pluck('name')->toArray();
        if (empty($categories)) {
            $this->call(CategorySeeder::class);
            $categories = Category::pluck('name')->toArray();
        }
        $divisions = Division::all();
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'in_progress', 'on_hold', 'resolved', 'closed'];

        $technicians = User::whereIn('role', ['technician'])->get();
        if ($technicians->count() < 2) {
            $technicians = collect();
            $technicians->push(User::factory()->create([
                'name' => 'Teknisi Andi',
                'email' => 'andi@tech.com',
                'role' => 'technician',
                'division_id' => $divisions->where('name', 'IT')->first()?->id,
            ]));
            $technicians->push(User::factory()->create([
                'name' => 'Teknisi Budi',
                'email' => 'budi@tech.com',
                'role' => 'technician',
                'division_id' => $divisions->where('name', 'IT')->first()?->id,
            ]));
        }

        $regularUsers = User::where('role', 'user')->count();
        if ($regularUsers < 5) {
            $userData = [
                ['name' => 'Siti Rahayu', 'email' => 'siti@hrd.com', 'division' => 'HRD'],
                ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@keuangan.com', 'division' => 'Keuangan'],
                ['name' => 'Dewi Lestari', 'email' => 'dewi@marketing.com', 'division' => 'Marketing'],
                ['name' => 'Bambang Susilo', 'email' => 'bambang@ops.com', 'division' => 'Operasional'],
                ['name' => 'Rina Wijaya', 'email' => 'rina@it.com', 'division' => 'IT'],
                ['name' => 'Hendra Gunawan', 'email' => 'hendra@hrd.com', 'division' => 'HRD'],
                ['name' => 'Maya Sari', 'email' => 'maya@marketing.com', 'division' => 'Marketing'],
            ];
            foreach ($userData as $u) {
                $div = $divisions->where('name', $u['division'])->first();
                User::factory()->create([
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'role' => 'user',
                    'division_id' => $div?->id,
                ]);
            }
        }
        $allUsers = User::where('role', 'user')->get();
        $allStaff = User::whereIn('role', ['technician', 'admin'])->get();
        $allTechs = User::whereIn('role', ['technician'])->get();

        $ticketSubjects = [
            'Bug' => ['Error saat login aplikasi', 'Tombol submit tidak berfungsi', 'Halaman putih (white screen)',
                'Data tidak muncul di dashboard', 'Notifikasi error di console', 'Form tidak bisa disubmit',
                'Link rusak di halaman utama', 'Tampilan mobile berantakan'],
            'Feature Request' => ['Tambah fitur export Excel', 'Integrasi dengan WhatsApp',
                'Fitur notifikasi email otomatis', 'Tambah dark mode', 'Filter pencarian lanjutan',
                'Tambah grafik dashboard'],
            'Technical Issue' => ['Koneksi database terputus', 'Server lambat merespon', 'SSL certificate expired',
                'Email tidak terkirim', 'API timeout', 'Cron job tidak berjalan'],
            'Billing' => ['Tagihan tidak sesuai', 'Pembayaran double', 'Refund belum diproses',
                'Invoice tidak muncul', 'Diskon tidak teraplikasi'],
            'General Inquiry' => ['Cara reset password', 'Panduan penggunaan sistem', 'Info jadwal maintenance',
                'Cara ganti profil', 'Pertanyaan seputar SLA'],
            'Hardware' => ['Monitor rusak', 'Keyboard tidak berfungsi', 'Printer error', 'Mouse double klik',
                'Hardisk penuh', 'Kabel LAN putus'],
            'Software' => ['Aplikasi tidak bisa diinstall', 'Lisensi software expired', 'Update Windows gagal',
                'Antivirus false positive', 'Driver tidak terdeteksi'],
            'Network' => ['Internet lemot', 'WiFi sering disconnect', 'VLAN tidak terhubung',
                'VPN connection refused', 'DNS tidak resolve', 'Port network down'],
        ];

        $statusWeights = ['open' => 8, 'in_progress' => 10, 'on_hold' => 4, 'resolved' => 20, 'closed' => 8];
        $priorityWeights = ['low' => 10, 'medium' => 20, 'high' => 12, 'urgent' => 8];

        for ($i = 0; $i < 50; $i++) {
            $category = $this->faker->randomElement($categories);
            $subjects = $ticketSubjects[$category] ?? $ticketSubjects['General Inquiry'];
            $subject = $this->faker->randomElement($subjects) . ' (' . ($i + 1) . ')';

            $priority = $this->weightedRandom($priorities, $priorityWeights);
            $status = $this->weightedRandom($statuses, $statusWeights);

            $daysAgo = rand(1, 180);
            $hoursAgo = rand(0, 23);
            $createdAt = Carbon::now()->subDays($daysAgo)->subHours($hoursAgo);

            $p = \App\Models\Priority::where('value', $priority)->first();
            $slaHours = $p?->sla_hours ?? 24;
            $slaDue = (clone $createdAt)->addHours($slaHours);

            $user = $allUsers->random();
            $assignee = in_array($status, ['open', 'in_progress', 'on_hold']) && rand(0, 2) > 0
                ? $allTechs->random()
                : (in_array($status, ['resolved', 'closed']) ? $allTechs->random() : null);

            $resolutionMinutes = rand(30, max(60, $slaHours * 60));
            $updatedAt = in_array($status, ['resolved', 'closed'])
                ? (clone $createdAt)->addMinutes($resolutionMinutes)
                : (clone $createdAt)->addHours(rand(1, 72));

            $estimatedCompletion = in_array($status, ['resolved', 'closed'])
                ? $updatedAt
                : (clone $createdAt)->addHours(intdiv($slaHours, 2));

            $rating = in_array($status, ['resolved', 'closed']) && rand(0, 3) > 0 ? rand(1, 5) : null;
            $feedback = $rating ? $this->faker->optional(0.8)->sentence() : null;

            $ticket = Ticket::create([
                'ticket_number' => 'TKT-' . $createdAt->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'subject' => $subject,
                'description' => $this->faker->paragraph(rand(2, 4)),
                'priority' => $priority,
                'status' => $status,
                'category' => $category,
                'user_id' => $user->id,
                'assigned_to' => $assignee?->id,
                'sla_due_at' => $slaDue,
                'estimated_completion_at' => $estimatedCompletion,
                'rating' => $rating,
                'feedback' => $feedback,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            $this->createTicketUpdates($ticket, $createdAt, $updatedAt, $status, $priority, $allStaff, $user);
        }
    }

    private function createTicketUpdates(Ticket $ticket, Carbon $createdAt, Carbon $updatedAt, string $finalStatus, string $priority, $staff, $creator): void
    {
        TicketUpdate::create([
            'ticket_id' => $ticket->id,
            'user_id' => $creator->id,
            'comment' => 'Ticket created - ' . $ticket->subject,
            'status' => 'open',
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        $currentTime = clone $createdAt;
        $statusFlow = ['open'];
        $statusOrder = ['open', 'in_progress', 'on_hold', 'resolved', 'closed'];
        $finalIdx = array_search($finalStatus, $statusOrder);

        for ($s = 1; $s <= $finalIdx; $s++) {
            if (rand(0, 2) === 0) continue;
            $statusFlow[] = $statusOrder[$s];
        }

        for ($s = 1; $s < count($statusFlow); $s++) {
            $interval = max(5, $createdAt->diffInMinutes($updatedAt) / count($statusFlow));
            $currentTime = (clone $currentTime)->addMinutes(rand((int)$interval, (int)$interval * 2));
            if ($currentTime->greaterThan($updatedAt)) $currentTime = clone $updatedAt;

            $commentOptions = [
                'Memeriksa dan menganalisis masalah...',
                'Sedang dalam penanganan tim teknis.',
                'Menunggu informasi lebih lanjut dari user.',
                'Masalah sudah diidentifikasi, sedang diperbaiki.',
                'Perbaikan sudah dilakukan, melakukan verifikasi.',
                'Update: sedang koordinasi dengan pihak terkait.',
                'Progress 50%, masih perlu pengujian.',
            ];

            TicketUpdate::create([
                'ticket_id' => $ticket->id,
                'user_id' => $staff->random()->id,
                'comment' => $this->faker->randomElement($commentOptions),
                'status' => $statusFlow[$s],
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
        }

        if ($currentTime->lessThan($updatedAt) && rand(0, 1) === 0) {
            $currentTime = (clone $currentTime)->addMinutes(rand(10, 60));
            if ($currentTime->greaterThan($updatedAt)) $currentTime = clone $updatedAt;

            TicketUpdate::create([
                'ticket_id' => $ticket->id,
                'user_id' => $creator->id,
                'comment' => $this->faker->randomElement([
                    'Terima kasih atas bantuannya!',
                    'Apakah ada progress?',
                    'Mohon segera ditindaklanjuti.',
                    'Sudah dicoba, masih tetap error.',
                    'Ada update terbaru?',
                ]),
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
        }
    }

    private function weightedRandom(array $items, array $weights): string
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $cumulative = 0;
        foreach ($items as $i => $item) {
            $cumulative += $weights[$item] ?? 1;
            if ($rand <= $cumulative) return $item;
        }
        return $items[0];
    }

}
