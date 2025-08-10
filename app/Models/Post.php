<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'products'; // บอกให้ Model ใช้ตาราง products

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'quantity',
        'material',
        'size',
        'product_image'
    ];
}
