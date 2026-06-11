<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $priority = Priority::inRandomOrder()->first();
        $status = $this->faker->randomElement(['open', 'in_progress', 'on_hold', 'resolved', 'closed']);
        $createdAt = Carbon::now()->subDays(rand(1, 180))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
        $slaHours = $priority ? $priority->sla_hours : 24;

        $updatedAt = null;
        if (in_array($status, ['resolved', 'closed'])) {
            $resolutionMinutes = rand(30, $slaHours * 60);
            $updatedAt = (clone $createdAt)->addMinutes($resolutionMinutes);
        } else {
            $updatedAt = (clone $createdAt)->addHours(rand(1, 72));
        }

        return [
            'subject' => $this->faker->sentence(rand(4, 10)),
            'description' => $this->faker->paragraph(rand(2, 5)),
            'priority' => $priority?->value ?? 'medium',
            'status' => $status,
            'category' => Category::inRandomOrder()->first()?->name ?? 'General',
            'user_id' => User::where('role', 'user')->inRandomOrder()->first()?->id ?? 1,
            'assigned_to' => rand(0, 3) > 0
                ? User::whereIn('role', ['technician', 'admin'])->inRandomOrder()->first()?->id
                : null,
            'sla_due_at' => (clone $createdAt)->addHours($slaHours),
            'estimated_completion_at' => (clone $createdAt)->addHours(intdiv($slaHours, 2)),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    public function resolved(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = Carbon::parse($attributes['created_at'] ?? now());
            $priority = Priority::where('value', $attributes['priority'] ?? 'medium')->first();
            $slaHours = $priority?->sla_hours ?? 24;
            $resolutionMinutes = rand(30, $slaHours * 60);
            $updatedAt = (clone $createdAt)->addMinutes($resolutionMinutes);

            return [
                'status' => 'resolved',
                'updated_at' => $updatedAt,
                'rating' => rand(1, 5),
                'feedback' => $this->faker->optional(0.7)->sentence(),
            ];
        });
    }

    public function closed(): static
    {
        return $this->resolved()->state(fn(array $a) => ['status' => 'closed']);
    }

    public function withRating(): static
    {
        return $this->state(fn(array $a) => [
            'rating' => rand(1, 5),
            'feedback' => $this->faker->optional(0.8)->sentence(),
        ]);
    }
}
