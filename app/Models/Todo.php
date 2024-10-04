<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'category', 'label', 'due_date'];

    protected $casts = [
        'due_date' => 'datetime',
    ];
}
