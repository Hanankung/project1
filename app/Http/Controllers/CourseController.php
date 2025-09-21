<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //แสดงหน้าแรกของผู้ดูแลระบบ
        return view('admin.dashboard');
    }

    public function courseList()
{
    $posts = course::all();
    return view('admin.course', compact('posts'));
}

// สำหรับผู้ใช้ทัสมัครสมาชิก
public function showForMember()
{
    // ดึงข้อมูลคอร์สทั้งหมดจาก DB
    $courses = \App\Models\course::all();

    // ส่งไปยัง view ของ member
    return view('member.courses', compact('courses'));
}
public function showDetail($id)
{
    // หา course ตาม id
    $course = \App\Models\course::findOrFail($id);

    // ส่งไปยัง view
    return view('member.course_detail', compact('course'));
}

// สำหรับผู้ใช้ทั่วไป
public function guestIndex()
{
    $courses = Course::all(); // หรือ Model ที่เก็บคอร์ส
    return view('courses', compact('courses'));
}

public function guestShow($id)
{
    $course = Course::findOrFail($id);
    return view('course_detail', compact('course'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        ///ไปยังหน้า เพิ่มสคอร์สเรียน
        return view('admin.create_course');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่รับมา
        $validatedData = $request->validate([
            'course_name' => 'required|string|max:50',
            'course_detail' => 'nullable|string',
            'course_name_ENG' => 'nullable|string|max:50',
            'course_name_MS' => 'nullable|string|max:50',
            'course_detail_ENG' => 'nullable|string',
            'course_detail_MS' => 'nullable|string',
            'price' => 'required|numeric',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // เตรียม path รูปภาพ
        $imagePath = null;
        if ($request->hasFile('course_image')) {
            // ตั้งชื่อไฟล์แบบสุ่ม + นามสกุลเดิม
            $file = $request->file('course_image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // เก็บไฟล์ไว้ที่ public/images
            $file->move(public_path('images'), $filename);

            $imagePath = '/images/' . $filename;
        }

        // สร้างคอร์สใหม่ ในฐานข้อมูล Model course
        course::create([
            'course_name' => $validatedData['course_name'],
            'course_detail' => $validatedData['course_detail'],
            'course_name_ENG' => $validatedData['course_name_ENG'],
            'course_name_MS' => $validatedData['course_name_MS'],
            'course_detail_ENG' => $validatedData['course_detail_ENG'],
            'course_detail_MS' => $validatedData['course_detail_MS'],
            'price' => $validatedData['price'],
            'course_image' => $imagePath,
        ]);

         // redirect ไปที่หน้า index พร้อม flash message
        return redirect()->route('admin.course')->with('success', __('messages.course_created'));

    }

    /**
     * Display the specified resource.
     */
    public function show(course $course)
{
    return view('admin.showcourse', ['post' => $course]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(course $course)
    {
        //ไปยังหน้าแก้ไขตอร์สเรียน
        return view('admin.edit_course', ['post' => $course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, course $course)
    {
        // ตรวจสอบข้อมูลที่รับมา
        $validatedData = $request->validate([
            'course_name' => 'required|string|max:50',
            'course_detail' => 'nullable|string',
            'course_name_ENG' => 'nullable|string|max:50',
            'course_name_MS' => 'nullable|string|max:50',
            'course_detail_ENG' => 'nullable|string',
            'course_detail_MS' => 'nullable|string',
            'price' => 'required|numeric',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // เตรียม path รูปภาพ
        $imagePath = null;
        if ($request->hasFile('course_image')) {
            // ตั้งชื่อไฟล์แบบสุ่ม + นามสกุลเดิม
            $file = $request->file('course_image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // เก็บไฟล์ไว้ที่ public/images
            $file->move(public_path('images'), $filename);

            $imagePath = '/images/' . $filename;
        }

        // สร้างคอร์สใหม่ ในฐานข้อมูล Model course
        $course->update([
            'course_name' => $validatedData['course_name'],
            'course_detail' => $validatedData['course_detail'],
            'course_name_ENG' => $validatedData['course_name_ENG'],
            'course_name_MS' => $validatedData['course_name_MS'],
            'course_detail_ENG' => $validatedData['course_detail_ENG'],
            'course_detail_MS' => $validatedData['course_detail_MS'],
            'price' => $validatedData['price'],
            'course_image' => $imagePath,
        ]);

         // redirect ไปที่หน้า index พร้อม flash message
        return redirect()->route('admin.course')->with('success', __('messages.course_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(course $course)
    {
         $course->delete();
        // redirect ไปที่หน้า index พร้อม flash message
        return redirect()->route('admin.course')->with('success', __('messages.course_deleted'));
    }
}

