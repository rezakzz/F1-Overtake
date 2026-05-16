<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team',
        'team_slug',
        'image_path',
    ];
}
