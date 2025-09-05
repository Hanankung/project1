<?php

namespace App\Support;

class ShippingQuote
{
    public static function quote($cartItems, string $countryCode): array
    {
        // ค่ามาตรฐาน (แก้ไขตามจริงได้)
        $zones = [
            'TH' => [ 'base' => 50,  'per_item' => 0,   'box' => 0, 'handling' => 0 ],
            'INTL' => [ 'base' => 660,'per_item' => 120,'box' => 60, 'handling' => 120 ],
        ];

        // ไทยถือว่าเป็น domestic
        $isDomestic = strtoupper($countryCode) === 'TH';
        $zone = $isDomestic ? $zones['TH'] : $zones['INTL'];

        // นับจำนวนชิ้น
        $itemCount = 0;
        foreach ($cartItems as $item) {
            $itemCount += isset($item->quantity) ? $item->quantity : ($item['quantity'] ?? 1);
        }

        // --- กติกา per_item แบบ "เพดาน" สำหรับต่างประเทศ ---
        // ถ้าซื้อ >= 3 ชิ้น ให้คิด per_item แค่ 2 ชิ้น (ค่าส่งจะเท่าเดิมตั้งแต่ 3 ชิ้นขึ้นไป)
        if (!$isDomestic && $itemCount >= 3) {
            $chargeableItems = 2;
        } else {
            $chargeableItems = $itemCount;
        }

        $perItemFee = $zone['per_item'] * $chargeableItems;

        // ค่าขนส่ง (ฐาน + per_item ตามกติกา)
        $shipping = $zone['base'] + $perItemFee;
        $box      = $zone['box'];
        $handling = $zone['handling'];

        return [
            'zone'      => $isDomestic ? 'TH' : 'INTL',
            'shipping'  => $shipping,
            'box'       => $box,
            'handling'  => $handling,
            'total_fee' => $shipping + $box + $handling,
        ];
    }
}
