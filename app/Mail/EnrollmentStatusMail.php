<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Mail\Mailable;

class EnrollmentStatusMail extends Mailable
{
    public function __construct(public Enrollment $enrollment) {}

    public function build()
    {
        return $this->subject('Статус на запишување')
            ->view('emails.enrollment-status');
    }
}
