<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebAgent extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'firstname', 'title', 'lastname', 'slug', 'primary', 'email', 'address', 'mlsid', 'phone', 'mobile', 'image', 'description', 'long_description_markdown'];

    public function getImageAttribute($value)
    {
        return $value !== null ? asset('uploads/'.$value) : null;
    }
}