<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'type', 'image', 'content', 'markdown', 'user_id', 'status', 'meta_description', 'meta_tags', 'excerpt', 'category'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }
}
