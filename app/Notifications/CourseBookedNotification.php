<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class CourseBookedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $booking,
        public string $audience = 'customer' // 'customer' หรือ 'admin'
    )
    {
        $this->afterCommit = true; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $b = $this->booking;

    $subject = $this->audience === 'admin'
        ? 'มีการจองคอร์สใหม่ #' . $b->id
        : 'ยืนยันคำขอจองคอร์ส #' . $b->id;

    $detailUrl = route('member.course.booking.list'); // ปุ่มในอีเมลจะลิงก์มาที่นี่

    // ใช้ view เดียวที่มี 3 ภาษาในฉบับเดียว
    return (new MailMessage)
        ->subject($subject)
        ->view('emails.course_booking_trilang', [
            'booking'   => $b,
            'audience'  => $this->audience,
            'detailUrl' => $detailUrl,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
