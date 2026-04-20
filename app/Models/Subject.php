<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'ects',
        'semester_id',
        'faculty_id',
        'professor_id',
        'assistant_id',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot(['semester_id', 'status', 'grade'])
            ->withTimestamps();
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }


    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }
}
