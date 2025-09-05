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
        'country',
        'subtotal',
        'shipping_fee',
        'box_fee',
        'handling_fee',
        'currency',
        'total_price',
        'status',
    ];
    // ความสัมพันธ์: 1 Order มีหลาย OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function getStatusI18nKeyAttribute(): string
{
    $raw = trim((string) $this->status);

    // ถ้าเก็บเป็นคีย์อยู่แล้ว
    $directKeys = [
        'pending','approved','rejected',
        'status_shipped','status_delivered','status_cancelled',
    ];
    if (in_array($raw, $directKeys, true)) {
        return $raw;
    }

    // ถ้าเก็บเป็นคีย์สากล (ไม่มี prefix)
    $canonicalToLang = [
        'pending'   => 'pending',
        'approved'  => 'approved',
        'rejected'  => 'rejected',
        'shipped'   => 'status_shipped',
        'delivered' => 'status_delivered',
        'cancelled' => 'status_cancelled',
        'canceled'  => 'status_cancelled',
    ];
    if (isset($canonicalToLang[$raw])) {
        return $canonicalToLang[$raw];
    }

    // ถ้าเก็บเป็นภาษาไทย
    $thaiToLang = [
        'รอดำเนินการ'   => 'pending',
        'อนุมัติแล้ว'        => 'approved',
        'ไม่อนุมัติ'      => 'rejected',
        'กำลังจัดส่งแล้ว' => 'status_shipped',
        'จัดส่งสำเร็จ'    => 'status_delivered',
        'ยกเลิก'         => 'status_cancelled',
    ];
    if (isset($thaiToLang[$raw])) {
        return $thaiToLang[$raw];
    }

    // เผื่อค่าที่ไม่รู้จัก
    return 'pending';
}
}
