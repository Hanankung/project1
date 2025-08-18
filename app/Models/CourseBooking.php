<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseBooking extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'email',
        'quantity',
        'price',
        'booking_date',
        'course_name',
        'course_type',
        'fabric_type',
        'fabric_length',
        'status',
    ];
}
