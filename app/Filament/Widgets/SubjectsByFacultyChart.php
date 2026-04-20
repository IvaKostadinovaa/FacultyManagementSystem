<?php

namespace App\Filament\Widgets;

use App\Models\Faculty;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SubjectsByFacultyChart extends ChartWidget
{
    protected static ?string $heading = 'Subjects by Faculty';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $faculties = Faculty::withCount('subjects')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Subjects',
                    'data' => $faculties->pluck('subjects_count')->toArray(),
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
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }
}
