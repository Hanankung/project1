<?php

namespace App\Http\Controllers;


use App\Models\CourseBooking;
use Illuminate\Http\Request;

class CourseBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //หน้าคอร์สเรียน
        return view('member.course');
    }
    public function courseBookingList()
{
    $posts = CourseBooking::all();
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
