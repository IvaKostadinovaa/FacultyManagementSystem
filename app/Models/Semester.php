<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'number',
        'academic_year',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public static function active()
    {
        return self::where('is_active', true)->first();
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function currentStudents() {
        return $this->hasMany(Student::class, 'current_semester_id');
    }


}

