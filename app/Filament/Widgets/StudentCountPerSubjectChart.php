<?php

namespace App\Filament\Widgets;

use App\Models\Subject;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class StudentCountPerSubjectChart extends ChartWidget
{
    protected static ?string $heading = 'Number of Students per Subject';
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['professor', 'assistant']);
    }

    protected function getData(): array
    {
        $user = Auth::user();

        $subjectsQuery = Subject::query();

        if ($user->role === 'professor') {
            $subjectsQuery->where('professor_id', $user->id);
        }

        if ($user->role === 'assistant') {
            $subjectsQuery->where('assistant_id', $user->id);
        }

        $subjects = $subjectsQuery
            ->withCount('enrollments')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Students',
                    'data' => $subjects->pluck('enrollments_count')->toArray(),
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
