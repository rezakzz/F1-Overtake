<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','total','status', 'order_code', 'snap_token', 'payment_status','customer_name','customer_email', 'recipient_name', 'phone', 'shipping_address', 'city', 'postal_code', 'note',
    ];

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function canPay()
    {
        return in_array($this->payment_status, ['pending', 'failed', 'expired']);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->order_code) {
                $lastId = self::max('id') + 1;
                $order->order_code = 'ORD-' . $lastId;
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
