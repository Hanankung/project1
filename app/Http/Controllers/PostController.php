<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //แสดงรายการโพสต์ทั้งหมด
        return view('admin.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //ไปยังหน้า เพิ่มสินค้า
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //การเพิ่มข้อมูลไปยังฐานข้อมูล
        // ตรวจสอบข้อมูลที่รับมา
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'material' => 'required|string|max:20',
            'size' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // เตรียม path รูปภาพ
        $imagePath = null;
        if ($request->hasFile('image')) {
            // ตั้งชื่อไฟล์แบบสุ่ม + นามสกุลเดิม
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // เก็บไฟล์ไว้ที่ public/images
            $file->move(public_path('images'), $filename);

            $imagePath = '/images/' . $filename;
        }

        // สร้างสินค้าใหม่
        Post::create([
            'product_name' => $validatedData['product_name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'material' => $validatedData['material'],
            'size' => $validatedData['size'],
            'product_image' => $imagePath,
        ]);

        // สมมติใช้ Model ชื่อ Product
    // \App\Models\Post::create($validatedData);
         // redirect ไปที่หน้า index พร้อม flash message
        return redirect()->route('admin.product')->with('success', 'เพิ่มสินค้าสำเร็จแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
