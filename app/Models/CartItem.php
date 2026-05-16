<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\produks;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(produks::class, 'product_id');
    }
}
