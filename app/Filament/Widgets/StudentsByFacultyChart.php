<?php

namespace App\Filament\Widgets;

use App\Models\Faculty;
use Filament\Widgets\ChartWidget;

class StudentsByFacultyChart extends ChartWidget
{
    protected static ?string $heading = 'Students by Faculty';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $faculties = Faculty::withCount('students')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Students',
                    'data' => $faculties->pluck('students_count')->toArray(),
                    'backgroundColor' => ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0'],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $faculties->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'admin';
    }
}
