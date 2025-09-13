<?php

namespace App\Http\Controllers;


use App\Models\CourseBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

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
        // ใช้เรตจาก config/currency.php (อิง .env: CURRENCY_THB_TO_MYR)
        $rate = (float) config('currency.rates.THB_MYR', 0.13);
        $capacity = 15; // ความจุคงที่/วัน

        // นับยอดจองต่อวัน (เฉพาะที่ “กินโควตา”)
        $rows = CourseBooking::select('booking_date', DB::raw('SUM(quantity) as qty'))
            ->whereNotIn('status', ['ไม่อนุมัติ', 'rejected', 'cancelled'])
            ->groupBy('booking_date')
            ->get();

        // map เป็น ['YYYY-MM-DD' => ยอดจองวันนั้น]
        $bookedMap = [];
        foreach ($rows as $r) {
            // ถ้า booking_date เป็น DATE อยู่แล้ว จะได้รูปแบบ YYYY-MM-DD
            $bookedMap[$r->booking_date] = (int) $r->qty;
        }
        return view('member.createBooking', compact('prefill', 'rate', 'capacity', 'bookedMap'));
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
            'quantity'     => 'required|integer|min:1|max:15',
            'booking_date'  => 'required|date',
            'course_name'   => 'required|string|max:50',
            'course_id' => 'nullable|exists:courses,id',
            'price'         => 'required|numeric|min:0',
            'total_price'  => 'nullable|numeric|min:0',
            'payment_slip' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:4096', // ≤ 4MB

        ]);
        $capacity = 15; // ความจุคงที่/วัน
        // ตรวจสอบยอดจองรวม (เฉพาะที่ “กินโควตา”)
        $booked = CourseBooking::whereDate('booking_date', $validated['booking_date'])
            ->whereNotIn('status', ['ไม่อนุมัติ', 'rejected', 'cancelled'])
            ->sum('quantity');

        $remain = max(0, $capacity - $booked);
        if ($validated['quantity'] > $remain) {
            return back()
                ->withErrors(['booking_date' => __('messages.booking_date_full_or_not_enough', ['remain' => $remain])])
                ->withInput();
        }

        $unit = null;
        if (!empty($validated['course_id'])) {
            $course = \App\Models\Course::find($validated['course_id']);
            if ($course) {
                $locale = app()->getLocale();
                $nameField = $locale === 'en' ? 'course_name_ENG' : ($locale === 'ms' ? 'course_name_MS' : 'course_name');

                // ชื่อคอร์สยึด DB เสมอ
                $validated['course_name'] = trim((string)($course->$nameField ?? $course->course_name));
                // ราคา/คนจาก DB
                $unit = (float) ($course->price ?? 0);
            }
        }
        // หากไม่มี course_id (หน้า manual) ให้ใช้ราคาที่ผู้ใช้กรอกเป็นราคา/คน
        if ($unit === null) {
            $unit = (float) ($validated['price'] ?? 0);
        }

        $qty   = (int) $validated['quantity'];
        $total = $unit * $qty;

        $slipPath = null;
        if ($request->hasFile('payment_slip')) {
            $slipPath = $request->file('payment_slip')->store('payment_slips', 'public'); // public/storage/payment_slips/...
        }

        CourseBooking::create([
            'user_id'       => Auth::id(), // ✅ ผูกกับ user ที่ล็อกอิน
            'course_id'    => $validated['course_id'] ?? null,
            'name'          => $validated['name'],
            'lastname'      => $validated['lastname'],
            'phone'         => $validated['phone'],
            'email'         => $validated['email'],
            'quantity'      => $qty,
            'price'        => $unit,
            'total_price'  => $total,
            'booking_date'  => $validated['booking_date'],
            'course_name'   => $validated['course_name'],
            'payment_slip' => $slipPath,
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
