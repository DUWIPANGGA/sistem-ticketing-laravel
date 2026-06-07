<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable = ['name', 'value', 'sla_hours', 'sort_order'];

    public function label(): string
    {
        return $this->name;
    }
}
