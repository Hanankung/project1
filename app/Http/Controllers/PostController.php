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
        //แสดงหน้าแรกของผู้ดูแลระบบ
        return view('admin.dashboard');
    }

    public function productList()
{
    // $posts = Post::all();
    $posts = Post::active()->get();
    return view('admin.product', compact('posts'));
}
public function archivedList()
{
    $posts = Post::archived()->get();
    return view('admin.product_archived', compact('posts'));
}

// สำหรับผู้ใช้ทั่วไป
public function guestIndex()
{
    $products = Post::active()->get(); // เดิม all()
    return view('products', compact('products'));
}

public function guestShow($id)
{
    $product = Post::where('id',$id)->where('status','active')->firstOrFail();
    return view('product_detail', compact('product'));
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
            'product_name_ENG' => 'nullable|string|max:50',
            'product_name_MS' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'description_ENG' => 'nullable|string',
            'description_MS' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'material' => 'required|string|max:20',
            'material_ENG' => 'nullable|string|max:50',
            'material_MS' => 'nullable|string|max:50',
            'size' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

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
            'product_name_ENG' => $validatedData['product_name_ENG'] ?? null,
            'product_name_MS' => $validatedData['product_name_MS'] ?? null,
            'description' => $validatedData['description'],
            'description_ENG' => $validatedData['description_ENG'] ?? null,
            'description_MS' => $validatedData['description_MS'] ?? null,
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'material' => $validatedData['material'],
            'material_ENG' => $validatedData['material_ENG'] ?? null,
            'material_MS' => $validatedData['material_MS'] ?? null,
            'size' => $validatedData['size'],
            'product_image' => $imagePath,
            'low_stock_threshold' => $validatedData['low_stock_threshold'] ?? 5,
        ]);

        // สมมติใช้ Model ชื่อ Product
    // \App\Models\Post::create($validatedData);
         // redirect ไปที่หน้า index พร้อม flash message
        return redirect()->route('admin.product')->with('success', __('messages.product_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //เมื่อกดปุ่ม view จะไปยังหน้าแสดงรายละเอียดสินค้า
        return view('admin.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //ไปยังหน้าแก้ไขสินค้า
        return view('admin.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //การเพิ่มข้อมูลไปยังฐานข้อมูล
        // ตรวจสอบข้อมูลที่รับมา
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:50',
            'product_name_ENG' => 'nullable|string|max:50',
            'product_name_MS' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'description_ENG' => 'nullable|string',
            'description_MS' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'material' => 'required|string|max:20',
            'material_ENG' => 'nullable|string|max:50',
            'material_MS' => 'nullable|string|max:50',
            'size' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $imagePath = $post->product_image;
        if ($request->hasFile('image')) {
            // ถ้ามีรูปเก่า ให้ลบออกจาก public/images ก่อน
            if ($post->product_image) {
                $oldImagePath = public_path($post->product_image);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
            // อัปโหลดรูปใหม่
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $imagePath = '/images/' . $filename;
        }

        $post->update([
            'product_name' => $validatedData['product_name'],
            'product_name_ENG' => $validatedData['product_name_ENG'] ?? null,
            'product_name_MS' => $validatedData['product_name_MS'] ?? null,
            'description' => $validatedData['description'],
            'description_ENG' => $validatedData['description_ENG'] ?? null,
            'description_MS' => $validatedData['description_MS'] ?? null,
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'material' => $validatedData['material'],
            'material_ENG' => $validatedData['material_ENG'] ?? null,
            'material_MS' => $validatedData['material_MS'] ?? null,
            'size' => $validatedData['size'],
            'product_image' => $imagePath,
            'low_stock_threshold' => $validatedData['low_stock_threshold'] ?? ($post->low_stock_threshold ?? 5),
        ]);

         // redirect ไปที่หน้า index พร้อม flash message
        return redirect()->route('admin.product')->with('success', __('messages.product_updated'));
    }
    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Post $post)
    // {
    //     // ลบไฟล์รูปภาพที่เกี่ยวข้อง (ถ้ามี)
    //     if ($post->product_image) {
    //         $imagePath = public_path($post->product_image);
    //         if (file_exists($imagePath)) {
    //             @unlink($imagePath);
    //         }
    //     }

    //     $post->delete();
    //     // redirect ไปที่หน้า index พร้อม flash message
    //     return redirect()->route('admin.product')->with('success', __('messages.product_deleted'));
    // }
    public function destroy(Post $post)
{
    // ไม่ลบไฟล์ ไม่ลบ record
    $post->update(['status' => 'archived']);

    return redirect()->route('admin.product')
        ->with('success', __('messages.product_archived'));
}
public function restore($id)
{
    $post = Post::findOrFail($id);
    $post->update(['status' => 'active']);

    return redirect()->route('admin.product.archived')
        ->with('success', __('messages.product_restored'));
}

}
