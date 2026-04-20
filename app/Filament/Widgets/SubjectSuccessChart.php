<?php

namespace App\Filament\Widgets;

use App\Models\Subject;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SubjectSuccessChart extends ChartWidget
{
    protected static ?string $heading = 'Average Grade per Subject';
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'professor';
    }

    protected function getData(): array
    {
        $professorId = Auth::id();

        $subjects = Subject::where('professor_id', $professorId)
            ->withAvg('enrollments', 'grade')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Average Grade',
                    'data' => $subjects->pluck('enrollments_avg_grade')->map(fn($avg) => round($avg, 2))->toArray(),
                    'backgroundColor' => '#c3d1c8',
                    'borderColor' => '#22c55e',
                ],
            ],
            'labels' => $subjects->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
