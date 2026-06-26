<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryField extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'label',
        'type',
        'is_required',
        'options',
        'placeholder',
        'default_value',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    /**
     * Supported field types.
     */
    public static array $types = [
        'text'           => 'Text (Single Line)',
        'textarea'       => 'Textarea (Multi Line)',
        'number'         => 'Number',
        'email'          => 'Email',
        'date'           => 'Date',
        'datetime-local' => 'Datetime',
        'select'         => 'Select (Dropdown)',
        'radio'          => 'Radio Button',
        'checkbox'       => 'Checkbox',
        'file'           => 'File Upload',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function values()
    {
        return $this->hasMany(TicketFieldValue::class, 'category_field_id');
    }
}
