<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Events\EnrollmentStatus;

class Enrollment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'student_id',
        'subject_id',
        'semester_id',
        'status',
        'grade',
        'exam_session',
    ];


    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';


    protected $casts = [
        'grade' => 'integer',
    ];


    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];


    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    protected static function booted()
    {

        static::updated(function ($enrollment) {

            if (! $enrollment->wasChanged(['status', 'grade'])) {
                return;
            }

            event(new EnrollmentStatus($enrollment));
        });
    }
}
