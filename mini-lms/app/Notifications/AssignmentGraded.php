<?php
namespace App\Notifications;


use App\Models\Submission;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class AssignmentGraded extends Notification {
    public function __construct(private Submission $submission) {}


    public function via($notifiable): array { return ['mail', 'database']; }


    public function toMail($notifiable): MailMessage {
        return (new MailMessage)
            ->subject('Your Assignment Has Been Graded')
            ->line("Score: {$this->submission->score}")
            ->line("Feedback: {$this->submission->feedback}");
    }


    public function toArray($notifiable): array {
        return ['type' => 'assignment_graded', 'submission_id' => $this->submission->id, 'score' => $this->submission->score];
    }
}