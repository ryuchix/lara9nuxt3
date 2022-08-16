<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'description_markdown',
        'solution',
        'status',
        'submitted_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
