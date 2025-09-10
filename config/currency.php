<?php
return [
    'rates' => [
        // ค่าเริ่มต้น 1 THB = 0.13 MYR (แก้ใน .env ได้)
        'THB_MYR' => env('CURRENCY_THB_TO_MYR', 0.13),
    ],
];
