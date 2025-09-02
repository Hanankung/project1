<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Course;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->input('query'));

        if ($query === '') {
            return redirect()->back()->with('error', 'กรุณากรอกคำค้นหา');
        }

        // ค้นหาในสินค้า
        $product = Post::query()
            ->select('id')
            ->where(function ($q) use ($query) {
                $q->where('product_name', 'LIKE', "%{$query}%")
                  ->orWhere('product_name_ENG', 'LIKE', "%{$query}%")
                  ->orWhere('product_name_MS', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('description_ENG', 'LIKE', "%{$query}%")
                  ->orWhere('description_MS', 'LIKE', "%{$query}%");
            })
            // จัดลำดับ: exact > startswith > contains > ใหม่สุด
            ->orderByRaw("
                (CASE 
                    WHEN product_name = ? THEN 0
                    WHEN product_name LIKE ? THEN 1
                    WHEN product_name LIKE ? THEN 2
                    ELSE 3
                END)
            ", [$query, "{$query}%", "%{$query}%"])
            ->latest('id') // กันกรณีเท่ากัน เลือกตัวใหม่สุด
            ->first();

        if ($product) {
            // ไปหน้า 'รายละเอียดสินค้า' ของ member
            return redirect()->route('member.product.show', $product->id);
        }

        // ค้นหาในคอร์ส
        $course = Course::query()
            ->select('id')
            ->where(function ($q) use ($query) {
                $q->where('course_name', 'LIKE', "%{$query}%")
                  ->orWhere('course_name_ENG', 'LIKE', "%{$query}%")
                  ->orWhere('course_name_MS', 'LIKE', "%{$query}%")
                  ->orWhere('course_detail', 'LIKE', "%{$query}%")
                  ->orWhere('course_detail_ENG', 'LIKE', "%{$query}%")
                  ->orWhere('course_detail_MS', 'LIKE', "%{$query}%");
            })
            ->orderByRaw("
                (CASE 
                    WHEN course_name = ? THEN 0
                    WHEN course_name LIKE ? THEN 1
                    WHEN course_name LIKE ? THEN 2
                    ELSE 3
                END)
            ", [$query, "{$query}%", "%{$query}%"])
            ->latest('id')
            ->first();

    // ถ้าพบคอร์ส → ไปหน้าแสดงรายละเอียดคอร์ส
    if ($course) {
            // ไปหน้า 'รายละเอียดคอร์ส' ของ member
            return redirect()->route('member.course.detail', $course->id);
        }

        // ถ้าไม่พบอะไรเลย
        return redirect()->back()->with('error', 'ไม่พบสิ่งที่ค้นหา');
    }
}