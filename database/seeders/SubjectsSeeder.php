<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Semester;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [

            // ФИНКИ
            [
                'name' => 'Вовед во програмирање',
                'code' => 'FINKI-CS101',
                'ects' => 6,
                'faculty_code' => 'FINKI',
                'semester_number' => 1,
            ],
            [
                'name' => 'Алгоритми и податочни структури',
                'code' => 'FINKI-CS201',
                'ects' => 6,
                'faculty_code' => 'FINKI',
                'semester_number' => 3,
            ],

            // ФЕИТ
            [
                'name' => 'Основи на електротехника',
                'code' => 'FEIT-EE101',
                'ects' => 6,
                'faculty_code' => 'FEIT',
                'semester_number' => 1,
            ],
            [
                'name' => 'Електрични кола',
                'code' => 'FEIT-EE201',
                'ects' => 6,
                'faculty_code' => 'FEIT',
                'semester_number' => 2,
            ],

            // Економски
            [
                'name' => 'Микроекономија',
                'code' => 'ECCF-ECO101',
                'ects' => 6,
                'faculty_code' => 'ECCF',
                'semester_number' => 4,
            ],
            [
                'name' => 'Макроекономија',
                'code' => 'ECCF-ECO201',
                'ects' => 6,
                'faculty_code' => 'ECCF',
                'semester_number' => 8,
            ],

            // Правен
            [
                'name' => 'Вовед во право',
                'code' => 'LAW-101',
                'ects' => 6,
                'faculty_code' => 'PF',
                'semester_number' => 1,
            ],
            [
                'name' => 'Уставно право',
                'code' => 'LAW-201',
                'ects' => 6,
                'faculty_code' => 'PF',
                'semester_number' => 8,
            ],

            // Медицински
            [
                'name' => 'Анатомија',
                'code' => 'MED-AN101',
                'ects' => 6,
                'faculty_code' => 'MED',
                'semester_number' => 7,
            ],
            [
                'name' => 'Физиологија',
                'code' => 'MED-PH201',
                'ects' => 6,
                'faculty_code' => 'MED',
                'semester_number' => 8,
            ],

            // Филозофски
            [
                'name' => 'Вовед во филозофија',
                'code' => 'FZF-PH101',
                'ects' => 6,
                'faculty_code' => 'FZF',
                'semester_number' => 6,
            ],
            [
                'name' => 'Логика',
                'code' => 'FZF-LOG201',
                'ects' => 6,
                'faculty_code' => 'FZF',
                'semester_number' => 4,
            ],

            // Филолошки
            [
                'name' => 'Македонски јазик 1',
                'code' => 'FLF-MK101',
                'ects' => 6,
                'faculty_code' => 'FLF',
                'semester_number' => 3,
            ],
            [
                'name' => 'Светска литература',
                'code' => 'FLF-LIT201',
                'ects' => 6,
                'faculty_code' => 'FLF',
                'semester_number' => 5,
            ],

            // Градежен
            [
                'name' => 'Статика',
                'code' => 'GF-STAT101',
                'ects' => 6,
                'faculty_code' => 'GF',
                'semester_number' => 6,
            ],
            [
                'name' => 'Механика на конструкции',
                'code' => 'GF-MECH201',
                'ects' => 6,
                'faculty_code' => 'GF',
                'semester_number' => 7,
            ],
        ];

        foreach ($subjects as $subject) {

            $faculty = Faculty::where('code', $subject['faculty_code'])->first();
            $semester = Semester::where('number', $subject['semester_number'])->first();

            if (!$faculty || !$semester) {
                continue;
            }

            Subject::create([
                'name' => $subject['name'],
                'code' => $subject['code'],
                'ects' => $subject['ects'],
                'faculty_id' => $faculty->id,
                'semester_id' => $semester->id,
            ]);
        }
    }
}
