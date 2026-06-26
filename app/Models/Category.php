<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'owner_name'];

    /**
     * Get all fields for this category, ordered by sort_order then id.
     */
    public function fields()
    {
        return $this->hasMany(CategoryField::class, 'category_id')->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Get only active fields, ordered by sort_order.
     */
    public function activeFields()
    {
        return $this->hasMany(CategoryField::class, 'category_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
