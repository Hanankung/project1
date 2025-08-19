<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function product()
    {
        // ผูกกับ Post (ที่ชี้ไปตาราง products)
        return $this->belongsTo(Post::class, 'product_id');
    }
}
