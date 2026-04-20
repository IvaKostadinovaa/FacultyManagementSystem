<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Subject;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class ProfessorStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'professor';
    }

    protected function getStats(): array
    {
        $professorId = Auth::id();

        return [
            Stat::make('My Subjects', Subject::where('professor_id', $professorId)->count())
                ->description('Subjects you are teaching')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Active Students', Enrollment::whereHas('subject', function($query) use ($professorId) {
                $query->where('professor_id', $professorId);
            })->count())
                ->description('Total students in your courses')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Ungraded Students', Enrollment::whereHas('subject', function($q) {
                $q->where('professor_id', auth()->id());
            })->whereNull('grade')->count())
                ->description('Students waiting for grading')
                ->color('danger')
                ->icon('heroicon-m-exclamation-circle'),
        ];

    }
}
