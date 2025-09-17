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

        $greet = $this->audience === 'admin' ? 'เรียน ผู้ดูแล' : 'สวัสดีค่ะ/ครับ';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting($greet)
            ->line($this->audience === 'admin'
                ? 'มีผู้ใช้ทำการจองคอร์สใหม่ กรุณาตรวจสอบรายละเอียดด้านล่าง'
                : 'เราได้รับคำขอจองคอร์สของคุณแล้ว รายละเอียดตามนี้');

        if (!empty($b->course_name)) {
            $mail->line('คอร์ส: ' . $b->course_name);
        }

        $mail->line('เลขที่การจอง: #' . $b->id);

        if (!empty($b->booking_date)) {
            $mail->line('วันที่เรียน: ' . Carbon::parse($b->booking_date)->timezone('Asia/Bangkok')->format('d/m/Y'));
        }
        if (!empty($b->quantity)) {
            $mail->line('จำนวนผู้เรียน: ' . $b->quantity);
        }
        if (!empty($b->total_price)) {
            $mail->line('ยอดรวม: ' . number_format($b->total_price, 2) . ' บาท');
        }

        // ปุ่มลิงก์ไปหน้ารายการจอง
        $url = route('member.course.booking.list');
        $mail->action('เปิดดูรายการจอง', $url);

        return $mail->line('ขอบคุณที่ใช้บริการ ');
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
