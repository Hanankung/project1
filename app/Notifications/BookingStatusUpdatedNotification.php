<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class BookingStatusUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $booking)
    {
        // ให้ส่งหลังบันทึกลง DB เสร็จ
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
        $approved = in_array($b->status, ['อนุมัติ', 'approved'], true);

        $subject = $approved
            ? 'คำขอจองคอร์สได้รับการอนุมัติ #' . $b->id
            : 'คำขอจองคอร์สไม่ได้รับการอนุมัติ #' . $b->id;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('สวัสดีค่ะ/ครับ')
            ->line($approved
                ? 'คำขอจองคอร์สของคุณได้รับการอนุมัติแล้ว ✅'
                : 'ขออภัย คำขอจองคอร์สของคุณไม่ได้รับการอนุมัติ ❌');

        if (!empty($b->course_name))   $mail->line('คอร์ส: ' . $b->course_name);
        if (!empty($b->booking_date))  $mail->line('วันที่เรียน: ' . Carbon::parse($b->booking_date)->timezone('Asia/Bangkok')->format('d/m/Y'));
        if (!empty($b->quantity))      $mail->line('จำนวนผู้เรียน: ' . $b->quantity);
        if (!empty($b->total_price))   $mail->line('ยอดรวม: ' . number_format($b->total_price, 2) . ' บาท');

        return $mail->action('ดูสถานะการจอง', route('member.course.booking.list'))
                    ->line('ขอบคุณที่ใช้บริการ ' . config('app.name') . '!');
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
