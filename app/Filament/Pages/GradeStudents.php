<?php

namespace App\Filament\Pages;

use App\Models\Enrollment;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GradeStudents extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Grade Students';
    protected static ?string $title = 'Grade Students';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.grade-students';

    public static function canAccess(): bool
    {
        return auth()->user()?->isProfessor() ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Enrollment::query()
                    ->with(['student', 'subject', 'semester'])
                    ->whereHas('subject', fn (Builder $q) => $q->where('professor_id', auth()->id()))
                    ->where('status', Enrollment::STATUS_APPROVED)
            )
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name')
                    ->label('Student')
                    ->formatStateUsing(fn ($record) => $record->student->first_name . ' ' . $record->student->last_name)
                    ->searchable(['students.first_name', 'students.last_name']),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable(),

                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester'),

                Tables\Columns\TextColumn::make('grade')
                    ->label('Grade')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state === null => 'gray',
                        $state >= 6    => 'success',
                        default        => 'danger',
                    })
                    ->placeholder('Not graded'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject_id')
                    ->label('Subject')
                    ->options(
                        fn () => \App\Models\Subject::where('professor_id', auth()->id())
                            ->pluck('name', 'id')
                    ),

                Tables\Filters\SelectFilter::make('semester_id')
                    ->relationship('semester', 'name')
                    ->label('Semester'),

                Tables\Filters\Filter::make('not_graded')
                    ->label('Not yet graded')
                    ->query(fn (Builder $q) => $q->whereNull('grade'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('grade')
                    ->icon('heroicon-o-pencil-square')
                    ->label('Set Grade')
                    ->modalHeading(fn (Enrollment $record) => 'Grade: ' . $record->student->first_name . ' ' . $record->student->last_name)
                    ->form([
                        Forms\Components\TextInput::make('grade')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->required()
                            ->label('Grade (5–10)'),
                    ])
                    ->fillForm(fn (Enrollment $record) => ['grade' => $record->grade])
                    ->action(fn (Enrollment $record, array $data) => $record->update(['grade' => $data['grade']]))
                    ->successNotificationTitle('Grade saved'),
            ]);
    }
}
