<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class table_teams extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'logo',
        'background',
    ];
}
