<?php

namespace App\Listeners;

use App\Events\EnrollmentStatus;
use App\Mail\EnrollmentStatusMail;
use App\Notifications\EnrollmentUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEnrollmentStatusNotification implements ShouldQueue
{
    public function handle(EnrollmentStatus $event): void
    {
        $enrollment = $event->enrollment;

        $enrollment->load(['student', 'subject.professor', 'subject.assistant']);

        $student = $enrollment->student;

        if ($student && $student->email) {
            Mail::to($student->email)
                ->send(new EnrollmentStatusMail($enrollment));
        }

        $professor = $enrollment->subject?->professor;

        if ($professor) {
            $professor->notify(new EnrollmentUpdatedNotification($enrollment));
        }

        $assistant = $enrollment->subject?->assistant;

        if ($assistant) {
            $assistant->notify(new EnrollmentUpdatedNotification($enrollment));
        }

        $admin = \App\Models\User::where('role', 'admin')->first();

        if ($admin) {
            $admin->notify(new EnrollmentUpdatedNotification($enrollment));
        }
    }
}
