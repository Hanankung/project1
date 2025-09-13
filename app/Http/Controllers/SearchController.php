<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Course;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->input('query', ''));
        if ($query === '') {
            return redirect()->back()->with('error', __('messages.search_enter_query'));
        }

        // เลือกฟิลด์ชื่อ ตามภาษา
        $loc = app()->getLocale();
        $nameField = match ($loc) {
            'en' => 'product_name_ENG',
            'ms' => 'product_name_MS',
            default => 'product_name',
        };

        // ====== สินค้า ======
        $productBuilder = Post::query()
            ->where(function ($q) use ($query) {
                $like = "%{$query}%";
                $q->where('product_name', 'LIKE', $like)
                    ->orWhere('product_name_ENG', 'LIKE', $like)
                    ->orWhere('product_name_MS', 'LIKE', $like)
                    ->orWhere('description', 'LIKE', $like)
                    ->orWhere('description_ENG', 'LIKE', $like)
                    ->orWhere('description_MS', 'LIKE', $like);
            })
            ->orderByRaw("
            (CASE 
                WHEN {$nameField} = ? THEN 0
                WHEN {$nameField} LIKE ? THEN 1
                WHEN {$nameField} LIKE ? THEN 2
                ELSE 3
            END)
        ", [$query, "{$query}%", "%{$query}%"])
            ->latest('id');

        $products = $productBuilder->paginate(12, ['*'], 'products_page')->withQueryString();

        // ====== คอร์ส (เดิม) ======
        $courseBuilder = Course::query()
            ->where(function ($q) use ($query) {
                $like = "%{$query}%";
                $q->where('course_name', 'LIKE', $like)
                    ->orWhere('course_name_ENG', 'LIKE', $like)
                    ->orWhere('course_name_MS', 'LIKE', $like)
                    ->orWhere('course_detail', 'LIKE', $like)
                    ->orWhere('course_detail_ENG', 'LIKE', $like)
                    ->orWhere('course_detail_MS', 'LIKE', $like);
            })
            ->orderByRaw("
            (CASE 
                WHEN course_name = ? THEN 0
                WHEN course_name LIKE ? THEN 1
                WHEN course_name LIKE ? THEN 2
                ELSE 3
            END)
        ", [$query, "{$query}%", "%{$query}%"])
            ->latest('id');

        $courses = $courseBuilder->paginate(8, ['*'], 'courses_page')->withQueryString();

        if ($products->total() === 0 && $courses->total() === 0) {
            return redirect()->back()->with('error', __('messages.search_not_found'));
        }

        return view('search.results', [
            'query'    => $query,
            'products' => $products,
            'courses'  => $courses,
            'isAuth'   => \Illuminate\Support\Facades\Auth::check(),
        ]);
    }
}
