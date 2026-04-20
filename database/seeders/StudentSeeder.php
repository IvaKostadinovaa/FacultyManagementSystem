<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Semester;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $maleFirstNames = [
            'Александар', 'Марко', 'Иван', 'Петар', 'Давид',
            'Никола', 'Стојан', 'Горан', 'Бојан', 'Виктор',
            'Лука', 'Филип'
        ];

        $femaleFirstNames = [
            'Марија', 'Ана', 'Елена', 'Ивана', 'Сара',
            'Јована', 'Кристина', 'Тамара'
        ];

        $maleLastNames = [
            'Стојанов', 'Иванов', 'Петров', 'Димитров', 'Николов',
            'Андонов', 'Митрев', 'Георгиев', 'Трајков', 'Костов'
        ];

        $femaleLastNames = [
            'Поповска', 'Јованова', 'Марковска', 'Димова', 'Атанасова'
        ];

        $currentYear = date('Y');

        for ($i = 1; $i <= 30; $i++) {

            $semester = Semester::inRandomOrder()->first();

            if ($semester->number <= 2) {
                $studyYear = 1;
            } elseif ($semester->number <= 4) {
                $studyYear = 2;
            } elseif ($semester->number <= 6) {
                $studyYear = 3;
            } else {
                $studyYear = 4;
            }

            $enrollmentYear = $currentYear - ($studyYear - 1);

            $isMale = rand(0, 1) === 1;

            if ($isMale) {
                $firstName = $maleFirstNames[array_rand($maleFirstNames)];
                $lastName = $maleLastNames[array_rand($maleLastNames)];
            } else {
                $firstName = $femaleFirstNames[array_rand($femaleFirstNames)];
                $lastName = $femaleLastNames[array_rand($femaleLastNames)];
            }

            Student::create([
                'index_number' => 'IND-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower(Str::slug($firstName . '.' . $lastName)) . $i . '@student.mk',

                'faculty_id' => Faculty::inRandomOrder()->first()->id,
                'current_semester_id' => $semester->id,

                'enrollment_year' => $enrollmentYear,
                'status' => 'active',
            ]);
        }
    }
}
