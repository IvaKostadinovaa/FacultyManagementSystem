<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $baseYear = '2025/2026';

        $semesters = [];


        for ($i = 1; $i <= 8; $i++) {

            $isWinter = $i % 2 == 1;
            $yearOffset = intdiv($i - 1, 2);

            if ($isWinter) {
                $startDate = date('Y-m-d', strtotime("2025-10-01 +$yearOffset years"));
                $endDate   = date('Y-m-d', strtotime("2026-01-25 +$yearOffset years"));
            } else {
                $startDate = date('Y-m-d', strtotime("2026-02-25 +$yearOffset years"));
                $endDate   = date('Y-m-d', strtotime("2026-06-10 +$yearOffset years"));
            }

            $semesters[] = [
                'name' => $baseYear . ' - ' . ($isWinter ? 'Зимски' : 'Летен') . " ($i)",
                'number' => $i,
                'academic_year' => $baseYear,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => true,
            ];
        }

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }
    }
}
