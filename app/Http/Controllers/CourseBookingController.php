<?php

namespace App\Http\Controllers;


use App\Models\CourseBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class CourseBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //หน้าคอร์สเรียน
        $bookings = CourseBooking::where('user_id', Auth::id())->get();
        return view('member.course', compact('bookings'));
    }
    public function courseBookingList()
    {
        // ดึงเฉพาะการจองของ user ที่ล็อกอินอยู่
        $posts = CourseBooking::where('user_id', Auth::id())->get();
        return view('member.history', compact('posts'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(?Course $course = null)
    {
        // เตรียมค่าพรีฟิลเริ่มต้น (กรณีไม่ได้มากับคอร์ส)
        $prefill = [
            'courseId'   => null,
            'courseName' => '',
            'price'      => '',
        ];

        if ($course) {
            $locale = app()->getLocale();
            $nameField = $locale === 'en'
                ? 'course_name_ENG'
                : ($locale === 'ms' ? 'course_name_MS' : 'course_name');

            $prefill['courseId']   = $course->id;
            $prefill['courseName'] = trim((string)($course->$nameField ?? $course->course_name));
            $prefill['price']      = $course->price ?? '';
        }

        return view('member.createBooking', compact('prefill'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:20',
            'lastname'      => 'required|string|max:20',
            'phone'         => 'required|string|max:10',
            'email'         => 'required|email|max:50',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'booking_date'  => 'required|date',
            'course_name'   => 'required|string|max:50',
            'course_id' => 'nullable|exists:courses,id',

        ]);
        if ($request->filled('course_id')) {
            $course = Course::find($request->course_id);
            if ($course) {
                $locale = app()->getLocale();
                $nameField = $locale === 'en'
                    ? 'course_name_ENG'
                    : ($locale === 'ms' ? 'course_name_MS' : 'course_name');

                // เขียนทับชื่อคอร์สให้ตรงกับ DB เสมอ
                $validated['course_name'] = trim((string)($course->$nameField ?? $course->course_name));

                // ถ้าต้องการล็อกราคาให้ตรง DB เสมอ ให้ปลดคอมเมนท์ด้านล่าง
                // $validated['price'] = $course->price ?? $validated['price'];
            }
        }


        CourseBooking::create([
            'user_id'       => Auth::id(), // ✅ ผูกกับ user ที่ล็อกอิน
            'name'          => $validated['name'],
            'lastname'      => $validated['lastname'],
            'phone'         => $validated['phone'],
            'email'         => $validated['email'],
            'quantity'      => $validated['quantity'],
            'price'         => $validated['price'],
            'booking_date'  => $validated['booking_date'],
            'course_name'   => $validated['course_name'],
        ]);

        return redirect()->route('member.courses')->with('success', __('messages.booking_saved'));
    }

    public function cancel($id)
    {
        $booking = CourseBooking::where('id', $id)
            ->where('user_id', Auth::id()) // ให้ยกเลิกได้เฉพาะของตัวเอง
            ->firstOrFail();

        if ($booking->status === 'รอดำเนินการ') {
            $booking->delete(); // หรือจะ update เป็น "ยกเลิก" ก็ได้
            return redirect()->route('member.course.booking.list')->with('success', __('messages.booking_cancelled'));
        }

        return redirect()->route('member.course.booking.list')->with('error', __('messages.booking_cancel_denied'));
    }
    // แสดงรายการการจองทั้งหมด (ฝั่ง Admin)
    public function adminIndex()
    {
        $bookings = CourseBooking::with('user')->latest()->get();
        return view('admin.courseBookings', compact('bookings'));
    }

    // อนุมัติการจอง
    public function approve($id)
    {
        $booking = CourseBooking::findOrFail($id);
        if ($booking->status === 'รอดำเนินการ') {
            $booking->status = 'อนุมัติ';
            $booking->save();
        }
        return redirect()->route('admin.course.booking.index')->with('success', __('messages.admin_booking_approved'));
    }

    // ไม่อนุมัติการจอง
    public function reject($id)
    {
        $booking = CourseBooking::findOrFail($id);
        if ($booking->status === 'รอดำเนินการ') {
            $booking->status = 'ไม่อนุมัติ';
            $booking->save();
        }
        return redirect()->route('admin.course.booking.index')->with('success', __('messages.admin_booking_rejected'));
    }



    /**
     * Display the specified resource.
     */
    public function show(CourseBooking $courseBooking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseBooking $courseBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseBooking $courseBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseBooking $courseBooking)
    {
        //
    }
}
