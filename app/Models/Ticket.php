<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_number',
        'subject',
        'description',
        'priority',
        'status',
        'category',
        'attachment',
        'attachments',
        'estimated_completion_at',
        'sla_due_at',
        'rating',
        'feedback',
        'user_id',
        'assigned_to',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estimated_completion_at' => 'datetime',
        'sla_due_at' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Get the user that created the ticket.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user assigned to the ticket.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the updates for the ticket.
     */
    public function updates()
    {
        return $this->hasMany(TicketUpdate::class);
    }

    /**
     * Generates a unique ticket number.
     */
    public static function generateTicketNumber(): string
    {
        // Example: TKT-20231027-0001
        $prefix = 'TKT-'.now()->format('Ymd');
        $lastTicket = self::where('ticket_number', 'like', $prefix.'-%')
            ->orderBy('ticket_number', 'desc')
            ->first();

        if ($lastTicket) {
            $parts = explode('-', $lastTicket->ticket_number);
            $sequence = (int) end($parts) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ticket_number)) {
                $model->ticket_number = static::generateTicketNumber();
            }
        });

        static::addGlobalScope('user_visibility', function (Builder $builder) {
            if (Auth::check() && !in_array(Auth::user()->role, ['admin', 'technician'])) {
                $builder->where('user_id', Auth::id());
            }
        });
    }
}
