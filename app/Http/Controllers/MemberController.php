<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class MemberController extends Controller
{
     public function products()
    {
        $products = Post::all(); // ดึงสินค้าทั้งหมดจากตาราง products
        return view('member.product', compact('products'));
    }
    public function show($id)
    {
        $product = Post::findOrFail($id); // ดึงข้อมูลสินค้าตาม id
        return view('member.product_detail', compact('product'));
    }
}
