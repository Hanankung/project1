<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
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
    // ชื่อคอร์สตามภาษา
    public function getNameI18nAttribute()
    {
        $loc = app()->getLocale();
        return match ($loc) {
            'en' => $this->course_name_ENG ?: $this->course_name,
            'ms' => $this->course_name_MS  ?: $this->course_name,
            default => $this->course_name,
        };
    }

    // รายละเอียดคอร์สตามภาษา
    public function getDetailI18nAttribute()
    {
        $loc = app()->getLocale();
        return match ($loc) {
            'en' => $this->course_detail_ENG ?: $this->course_detail,
            'ms' => $this->course_detail_MS  ?: $this->course_detail,
            default => $this->course_detail,
        };
    }
}
