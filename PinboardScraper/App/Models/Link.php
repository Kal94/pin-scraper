<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'title',
        'comments',
        'tags',
        'is_live',
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}
