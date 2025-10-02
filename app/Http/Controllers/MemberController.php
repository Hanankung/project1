<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class MemberController extends Controller
{
     public function products()
    {
        $products = Post::active()->get();
        return view('member.product', compact('products'));
    }
    public function show($id)
    {
       $product = Post::where('id', $id)
                       ->active()
                       ->firstOrFail();

        return view('member.product_detail', compact('product'));
    }
}
