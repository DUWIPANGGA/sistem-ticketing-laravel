<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseArticle extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'author_id',
        'is_published'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
