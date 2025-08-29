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
    'material',
    'material_ENG',
    'material_MS',
    'size',
    'product_image'
];
}
