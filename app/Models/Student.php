<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use LogsActivity;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_GRADUATED = 'graduated';

    protected $fillable = [
        'index_number',
        'first_name',
        'last_name',
        'email',
        'faculty_id',
        'current_semester_id',
        'enrollment_year',
        'status',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function subjects()
    {
        return $this->hasManyThrough(
            Subject::class,
            Enrollment::class,
            'student_id',
            'id',
            'id',
            'subject_id'
        );
    }

    public function approvedEnrollments()
    {
        return $this->hasMany(Enrollment::class)
            ->where('status', Enrollment::STATUS_APPROVED);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'current_semester_id');
    }
}
