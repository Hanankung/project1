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
    $approved = in_array($b->status, ['อนุมัติ','approved'], true);

    $subject = $approved
        ? 'คำขอจองคอร์สได้รับการอนุมัติ #' . $b->id
        : 'คำขอจองคอร์สไม่ได้รับการอนุมัติ #' . $b->id;

    $detailUrl = route('member.course.booking.list');

    return (new MailMessage)
        ->subject($subject)
        ->view('emails.booking_status_trilang', [
            'booking'   => $b,
            'approved'  => $approved,
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
