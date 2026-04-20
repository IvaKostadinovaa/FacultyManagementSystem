<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Subject;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class AssistantStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'assistant';
    }

    protected function getStats(): array
    {
        $assistantId = Auth::id();

        return [
            Stat::make('My Subjects', Subject::where('assistant_id', $assistantId)->count())
                ->description('Subjects where you are an assistant')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Active Students', Enrollment::whereHas('subject', function($query) use ($assistantId) {
                $query->where('assistant_id', $assistantId);
            })->count())
                ->description('Total students in your exercise groups')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Enrollments', Enrollment::whereHas('subject', function($query) use ($assistantId) {
                $query->where('assistant_id', $assistantId);
            })->count())
                ->description('Overview of all subject enrollments')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('info'),
        ];
    }
}
