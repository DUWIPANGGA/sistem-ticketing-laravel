<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $admin = $users->where('role', 'admin')->first();
        $user = $users->where('role', 'user')->first();

        if ($admin && $user) {
            DB::table('notifications')->insert([
                [
                    'id' => Str::uuid()->toString(),
                    'type' => 'App\Notifications\TicketCreatedNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $admin->id,
                    'data' => json_encode([
                        'ticket_id' => 1,
                        'ticket_number' => 'TKT-20260101-0001',
                        'message' => 'New ticket created by ' . $user->name . '.',
                        'url' => url('/tickets/1'),
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'type' => 'App\Notifications\TicketUpdatedNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'ticket_id' => 1,
                        'ticket_number' => 'TKT-20260101-0001',
                        'message' => 'Your ticket was updated by ' . $admin->name . '.',
                        'url' => url('/tickets/1'),
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
