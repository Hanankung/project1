<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    protected $table = 'courses'; // บอกให้ Model ใช้ตาราง course

    protected $fillable = [
        'course_name',
        'course_detail',
        'price',
        'course_image'
    ];
}
