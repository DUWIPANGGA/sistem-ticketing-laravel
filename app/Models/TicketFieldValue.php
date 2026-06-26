<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketFieldValue extends Model
{
    protected $fillable = [
        'ticket_id',
        'category_field_id',
        'value',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function field()
    {
        return $this->belongsTo(CategoryField::class, 'category_field_id');
    }
}
