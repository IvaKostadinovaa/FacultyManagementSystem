<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),

                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),

                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'name')
                    ->required()
                    ->disabled(fn () => !auth()->user()->isAdmin()),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->disabled(fn () => !auth()->user()->isAdmin()),

                Forms\Components\TextInput::make('grade')
                    ->numeric()
                    ->label('Grade')
                    ->disabled(fn () => auth()->user()->isAssistant())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name')
                    ->label('Student')
                    ->formatStateUsing(fn ($record) => $record->student->first_name . ' ' . $record->student->last_name)
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable(),

                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester'),

                Tables\Columns\TextColumn::make('subject.faculty.name')
                    ->label('Faculty'),



                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('grade')
                    ->label('Grade'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('subject_id')
                    ->relationship('subject', 'name')
                    ->label('Subject'),

                Tables\Filters\SelectFilter::make('semester_id')
                    ->relationship('semester', 'name')
                    ->label('Semester'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin()
            || auth()->user()->isProfessor()
            || auth()->user()->isAssistant();
    }
    public static function canCreate(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isProfessor();
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user->isProfessor()) {
            $query->whereHas('subject', function ($q) use ($user) {
                $q->where('professor_id', $user->id);
            });
        }

        if ($user->isAssistant()) {
            $query->whereHas('subject', function ($q) use ($user) {
                $q->where('assistant_id', $user->id);
            });
        }

        return $query;
    }
}
