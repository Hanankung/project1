<?php

namespace App\Http\Controllers;


use App\Models\CourseBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function create()
    {
        //หน้าสร้างการจองคอร์สเรียน
        return view('member.createBooking');
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
        'course_type'   => 'nullable|string|max:50',
        'fabric_type'   => 'nullable|string|max:50',
        'fabric_length' => 'nullable|string|max:50',
        ]);

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
            'course_type'   => $validated['course_type'],
            'fabric_type'   => $validated['fabric_type'],
            'fabric_length' => $validated['fabric_length'],
        ]);

        return redirect()->route('member.courses')->with('success', 'บันทึกการจองเรียบร้อยแล้ว!');
    }

    public function cancel($id)
{
    $booking = CourseBooking::where('id', $id)
                ->where('user_id', Auth::id()) // ให้ยกเลิกได้เฉพาะของตัวเอง
                ->firstOrFail();

    if ($booking->status === 'รอดำเนินการ') {
        $booking->delete(); // หรือจะ update เป็น "ยกเลิก" ก็ได้
        return redirect()->route('member.course.booking.list')->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
    }

    return redirect()->route('member.course.booking.list')->with('error', 'ไม่สามารถยกเลิกได้ เนื่องจากแอดมินได้อนุมัติแล้ว');
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
    return redirect()->route('admin.course.booking.index')->with('success', 'อนุมัติการจองเรียบร้อยแล้ว');
}

// ไม่อนุมัติการจอง
    public function reject($id)
{
    $booking = CourseBooking::findOrFail($id);
    if ($booking->status === 'รอดำเนินการ') {
        $booking->status = 'ไม่อนุมัติ';
        $booking->save();
    }
    return redirect()->route('admin.course.booking.index')->with('success', 'ไม่อนุมัติการจองเรียบร้อยแล้ว');
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
