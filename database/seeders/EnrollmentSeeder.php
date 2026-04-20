<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Semester;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();

        $semesters = Semester::all();

        foreach ($students as $student) {

            $studentSemesters = $semesters->random(min(3, $semesters->count()));

            foreach ($studentSemesters as $semester) {

                $subjects = Subject::where('faculty_id', $student->faculty_id)
                    ->where('semester_id', $semester->id)
                    ->get();

                if ($subjects->isEmpty()) {
                    continue;
                }

                $randomSubjects = $subjects->random(
                    min(rand(2, 4), $subjects->count())
                );

                foreach ($randomSubjects as $subject) {

                    Enrollment::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'semester_id' => $semester->id,

                        'status' => 'pending',
                        'grade' => null,
                    ]);
                }
            }
        }
    }
}
