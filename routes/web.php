<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

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
require __DIR__.'/auth.php';
