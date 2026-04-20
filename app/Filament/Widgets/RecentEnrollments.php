<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentEnrollments extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Enrollment::query()
                    ->with(['student', 'subject'])
                    ->whereHas('subject', function ($query) {
                        $user = Auth::user();

                        if ($user->role === 'professor') {
                            $query->where('professor_id', $user->id);
                        }

                        if ($user->role === 'assistant') {
                            $query->where('assistant_id', $user->id);
                        }
                    })
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name')
                    ->label('Student')
                    ->formatStateUsing(fn ($record) =>
                        $record->student->first_name . ' ' . $record->student->last_name
                    ),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enrolled on')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
    public static function canView(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['professor', 'assistant']);
    }
}
