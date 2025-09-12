<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseBooking extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'lastname',
        'phone',
        'email',
        'quantity',
        'price',
        'total_price',
        'booking_date',
        'course_name',
        'status',
        'payment_slip',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}
