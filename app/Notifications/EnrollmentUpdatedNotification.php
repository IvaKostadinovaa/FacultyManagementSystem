<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EnrollmentUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Enrollment $enrollment)
    {
    }

    public function via($notifiable)
    {
        if ($notifiable instanceof \App\Models\User && $notifiable->isAssistant()) {
            return ['database'];
        }

        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $enrollment = $this->enrollment->load(['student', 'subject']);

        $status = $enrollment->status;
        $iconColor = match ($status) {
            'approved' => 'success',
            'rejected'  => 'danger',
            default     => 'warning',
        };

        $body = "Student: {$enrollment->student->full_name} | Subject: {$enrollment->subject->name} | Status: {$status}";
        if ($enrollment->grade !== null) {
            $body .= " | Grade: {$enrollment->grade}";
        }

        return [
            'format'    => 'filament',
            'duration'  => 'persistent',
            'title'     => 'Enrollment Updated',
            'body'      => $body,
            'icon'      => 'heroicon-o-academic-cap',
            'iconColor' => $iconColor,
        ];
    }

    public function toMail($notifiable)
    {
        $enrollment = $this->enrollment->load(['student', 'subject']);

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Enrollment Updated')
            ->line('Student: ' . $enrollment->student->full_name)
            ->line('Subject: ' . $enrollment->subject->name)
            ->line('Status: ' . $enrollment->status);
    }
}
