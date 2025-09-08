<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('member.cart', compact('cartItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $product = Post::findOrFail($data['product_id']);
        $incomingQty = (int)($data['quantity'] ?? 1);

        // หมดสต็อกหรือขอมากกว่าสต็อก
        if ($product->quantity <= 0) {
            return back()->with('error', 'สินค้าชิ้นนี้หมดแล้ว');
        }
        if ($incomingQty > $product->quantity) {
            $incomingQty = $product->quantity; // จำกัดไม่ให้เกินสต็อก
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQty = min($cart->quantity + $incomingQty, $product->quantity);
            if ($newQty === $cart->quantity) {
                return back()->with('error', 'จำนวนในตะกร้าถึงสต็อกสูงสุดแล้ว');
            }
            $cart->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'quantity'   => min($incomingQty, $product->quantity),
            ]);
        }

        return back()->with('success', 'เพิ่มสินค้าลงตะกร้าแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    $cart = Cart::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $product = Post::findOrFail($cart->product_id);

    if ($product->quantity <= 0) {
        // ลบออกเพราะสินค้าหมดแล้ว
        $cart->delete();
        return back()->with('error', 'สินค้าหมดแล้ว จึงลบออกจากตะกร้า');
    }

    $newQty = min((int)$data['quantity'], (int)$product->quantity);
    $cart->update(['quantity' => $newQty]);

    if ($newQty < (int)$data['quantity']) {
        return back()->with('success', 'อัปเดตจำนวนแล้ว (จำกัดตามสต็อกที่คงเหลือ)');
    }

    return back()->with('success', 'อัปเดตจำนวนสินค้าแล้ว');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->delete();

        return redirect()->back()->with('success', 'ลบสินค้าออกจากตะกร้าแล้ว');
    }
}
