<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CourseBookingController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});
// หน้าเกี่ยวกับเรา
Route::get('/aboutme', function () {
    return view('aboutme'); // ไม่ต้องใส่นามสกุล .blade.php
});
// หน้าเกี่ยวกับเราของสมาชิก
Route::get('/member/aboutme', function () {
    return view('member.aboutme'); // ไม่ต้องใส่นามสกุล .blade.php
})->name('member.aboutme');

// ติดต่อเรา
Route::get('/contect', function () {
    return view('contect'); // ไม่ต้องใส่นามสกุล .blade.php
});
// ติดต่อเราของสมาชิก
Route::get('/member/contect', function () {
    return view('member.contect'); // ไม่ต้องใส่นามสกุล .blade.php
})->name('member.contect');

// Dashboard หลัก
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Member Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/member/dashboard', function () {
        return view('member.dashboard');
    })->name('member.dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Member Product
Route::get('/member/product', [MemberController::class, 'products'])->name('member.product');
// แสดงรายละเอียดสินค้า (สำหรับสมาชิก)
Route::get('/member/products/{id}', [MemberController::class, 'show'])->name('member.product.show');

Route::middleware(['auth'])->group(function () {
    Route::resource('cart', CartController::class);
});
// แสดงตะกร้าสินค้า
Route::get('/member/cart', [CartController::class, 'index'])->name('member.cart');
// เพิ่มสินค้าลงตะกร้า
Route::post('/member/cart/store', [CartController::class, 'store'])->name('member.cart.store');
// อัพเดตจำนวนสินค้าในตะกร้า
Route::put('/member/cart/update/{id}', [CartController::class, 'update'])->name('member.cart.update');
// ลบสินค้าออกจากตะกร้า
Route::delete('/member/cart/delete/{id}', [CartController::class, 'destroy'])
    ->name('member.cart.delete')
    ->middleware('auth');

// แสดงคอร์สสำหรับ member
Route::get('/member/courses', [CourseController::class, 'showForMember'])->name('member.courses');
// แสดงรายละเอียดคอร์ส (สำหรับ member)
Route::get('/member/course/{id}', [CourseController::class, 'showDetail'])->name('member.course.detail');
// จองคอร์สเรียน
Route::get('/member/courseBooking', [CourseBookingController::class, 'create'])->name('member.course.booking');
Route::post('/member/course/booking/store', [CourseBookingController::class, 'store'])->name('member.course.booking.store');

// แสดงรายการจองคอร์สเรียน
Route::get('/member/courseBookingList', [CourseBookingController::class, 'courseBookingList'])->name('member.course.booking.list');
// ยกเลิกการจองคอร์สเรียน
Route::delete('/member/courseBooking/cancel/{id}', [CourseBookingController::class, 'cancel'])
    ->name('member.course.booking.cancel')
    ->middleware('auth');

// Admin Dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Register Page (Public)
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Product Admin
Route::get('/admin/product', function () {
    return view('admin.product');
})->name('admin.product');

//create Product
Route::get('/admin/create', [PostController::class, 'create'])->name('create');

//Post Product
Route::post('/admin/store', [PostController::class, 'store'])->name('store');
Route::get('/admin/product', [PostController::class, 'productList'])->name('admin.product');
Route::get('/admin/show/{post}', [PostController::class, 'show'])->name('admin.show');

// Edit Product
Route::get('/admin/edit/{post}', [PostController::class, 'edit'])->name('admin.edit');
Route::put('/admin/update/{post}', [PostController::class, 'update'])->name('admin.update');

// Delete Product
Route::delete('/admin/delete/{post}', [PostController::class, 'destroy'])->name('admin.delete');

// Course Admin
Route::get('/admin/course', function () {
    return view('admin.course');
})->name('admin.course');

//create Course
Route::get('/admin/create_course', [CourseController::class, 'create'])->name('create_course');
//Post Course
Route::post('/admin/store_course', [CourseController::class, 'store'])->name('store_course');
Route::get('/admin/course', [CourseController::class, 'courseList'])->name('admin.course');
Route::get('/admin/showcourse/{course}', [CourseController::class, 'show'])->name('admin.showcourse');
// Edit Course
Route::get('/admin/edit_course/{course}', [CourseController::class, 'edit'])->name('admin.edit_course');
Route::put('/admin/update_course/{course}', [CourseController::class, 'update'])->name('admin.update_course');

// Delete Course
Route::delete('/admin/delete_course/{course}', [CourseController::class, 'destroy'])->name('admin.delete_course');

// Admin จัดการการจองคอร์ส
Route::get('/admin/courseBookings', [CourseBookingController::class, 'adminIndex'])->name('admin.course.booking.index');
Route::patch('/admin/courseBookings/{id}/approve', [CourseBookingController::class, 'approve'])->name('admin.course.booking.approve');
Route::patch('/admin/courseBookings/{id}/reject', [CourseBookingController::class, 'reject'])->name('admin.course.booking.reject');



require __DIR__.'/auth.php';
