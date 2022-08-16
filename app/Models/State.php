<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'keyword',
        'image',
        'title',
        'heading_body',
        'heading_body_markdown',
        'about_body',
        'about_body_markdown',
        'footer_body',
        'footer_body_markdown',
    ];

    public function getImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }
}
