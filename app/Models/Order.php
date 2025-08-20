<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'total_price',
        'status',
    ];
    // ความสัมพันธ์: 1 Order มีหลาย OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
