<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Faculty;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', Student::count())
                ->description('All in Database')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Subjects', Subject::count())
                ->description('Active Courses')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('Faculties', Faculty::count())
                ->description('Institutions')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),
        ];
    }
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'admin';
    }
}
