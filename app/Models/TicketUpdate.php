<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketUpdate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment',
        'status',
        'priority',
        'attachment',
        'attachments',
        'parent_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
    ];

    /**
     * Get the ticket that the update belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who made the update.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketUpdate::class, 'parent_id')->with('user', 'replies')->orderBy('created_at');
    }

    public function parent()
    {
        return $this->belongsTo(TicketUpdate::class, 'parent_id');
    }
}