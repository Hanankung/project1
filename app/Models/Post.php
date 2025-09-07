<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'products'; // บอกให้ Model ใช้ตาราง products

    protected $fillable = [
    'product_name',
    'product_name_ENG',
    'product_name_MS',
    'description',
    'description_ENG',
    'description_MS',
    'price',
    'quantity',
    'low_stock_threshold',
    'material',
    'material_ENG',
    'material_MS',
    'size',
    'product_image'
];
    protected $casts = [
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    public function hasStock(int $qty): bool
    {
        return $this->quantity >= $qty;
    }

    public function decrementStock(int $qty): void
    {
        $this->quantity = max(0, $this->quantity - $qty);
        $this->save();
    }

    public function incrementStock(int $qty): void
    {
        $this->quantity += $qty;
        $this->save();
    }

    public function isLow(): bool
    {
        return $this->quantity <= $this->low_stock_threshold;
    }
}
