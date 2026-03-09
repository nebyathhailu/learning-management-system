<?php
namespace App\Notifications;


use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class EnrollmentConfirmed extends Notification {
    public function __construct(private int $courseId) {}


    public function via($notifiable): array { return ['mail', 'database']; }


    public function toMail($notifiable): MailMessage {
        return (new MailMessage)
            ->subject('Enrollment Confirmed!')
            ->line("You have successfully enrolled in the course.")
            ->action('View Course', url("/courses/{$this->courseId}"))
            ->line('Happy Learning!');
    }


    public function toArray($notifiable): array {
        return ['type' => 'enrollment_confirmed', 'course_id' => $this->courseId];
    }
}
