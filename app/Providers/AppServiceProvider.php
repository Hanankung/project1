<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::statement("SET time_zone = '+07:00'");
        // ส่งตัวแปร $cartCount ไปทุก view
    View::composer('*', function ($view) {
        $cartCount = 0;
        if (Auth::check()) {
            // นับจำนวนสินค้าทั้งหมดของผู้ใช้
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        }
        $view->with('cartCount', $cartCount);
    });
    }
    
}
