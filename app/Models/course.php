<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    protected $table = 'courses'; // บอกให้ Model ใช้ตาราง course

    protected $fillable = [
        'course_name',
        'course_name_ENG',
        'course_name_MS',
        'course_detail',
        'course_detail_ENG',
        'course_detail_MS',
        'price',
        'course_image'
    ];
}
