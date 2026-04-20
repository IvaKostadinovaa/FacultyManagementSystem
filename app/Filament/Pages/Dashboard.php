<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AssistantStats;
use App\Filament\Widgets\ProfessorStats;
use App\Filament\Widgets\RecentEnrollments;
use App\Filament\Widgets\StudentCountPerSubjectChart;
use App\Filament\Widgets\StudentStatsOverview;
use App\Filament\Widgets\StudentsByFacultyChart;
use App\Filament\Widgets\SubjectSuccessChart;
use App\Filament\Widgets\SubjectsByFacultyChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function getWidgets(): array
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return [
                StudentStatsOverview::class,
                StudentsByFacultyChart::class,
                SubjectsByFacultyChart::class,
            ];
        }

        if ($user->role === 'professor') {
            return [
                ProfessorStats::class,
                SubjectSuccessChart::class,
                StudentCountPerSubjectChart::class,
            ];
        }

        if ($user->role === 'assistant') {
            return [
                AssistantStats::class,
                StudentCountPerSubjectChart::class,
                RecentEnrollments::class,
            ];
        }

        return [];
    }
}
