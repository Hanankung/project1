<?php

use Illuminate\Support\Facades\Route;

//หน้าหลัก
Route::get('/', function () {
    return view('welcome');
});
//หน้าล็อกอิน
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
