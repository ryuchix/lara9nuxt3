<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'city',
        'min_price',
        'max_price',
        'beds',
        'baths',
        'min_area',
        'min_garage',
        'features'
    ];
}