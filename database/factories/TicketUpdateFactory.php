<?php

namespace Database\Factories;

use App\Models\TicketUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketUpdateFactory extends Factory
{
    protected $model = TicketUpdate::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'comment' => $this->faker->paragraph(),
            'status' => null,
            'priority' => null,
        ];
    }

    public function statusChange(string $status): static
    {
        return $this->state(fn(array $a) => [
            'comment' => 'Status changed to ' . str_replace('_', ' ', $status) . '.',
            'status' => $status,
        ]);
    }

    public function initialCreation(): static
    {
        return $this->state(fn(array $a) => [
            'comment' => 'Ticket created.',
            'status' => 'open',
        ]);
    }
}
