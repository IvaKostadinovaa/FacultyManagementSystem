<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers\EnrollmentsRelationManager;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->label('Name'),

                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->label('Last Name'),

                Forms\Components\TextInput::make('index_number')
                    ->required()
                    ->label('Index'),


                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),

                Forms\Components\Select::make('faculty_id')
                    ->relationship('faculty', 'name')
                    ->required()
                    ->label('Faculty'),

                Forms\Components\Select::make('current_semester_id')
                    ->relationship('semester', 'name')
                    ->label('Semester'),

                Forms\Components\TextInput::make('enrollment_year')
                    ->numeric()
                    ->required()
                    ->label('Enrollment Year'),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'graduated' => 'Graduated',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn($record) => $record->full_name)
                    ->searchable(['first_name', 'last_name']),

                Tables\Columns\TextColumn::make('index_number')
                    ->label('Index')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Faculty'),

                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'graduated',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('faculty_id')
                    ->relationship('faculty', 'name')
                    ->label('Faculty'),

                Tables\Filters\SelectFilter::make('current_semester_id')
                    ->relationship('semester', 'name')
                    ->label('Semester'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'graduated' => 'Graduated',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
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
        return auth()->user()->isAdmin();
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->isAdmin();
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isProfessor()) {
            return $query->whereHas('enrollments.subject', function ($subQuery) use ($user) {
                $subQuery->where('professor_id', $user->id);
            });
        }

        if ($user->isAssistant()) {
            return $query->whereHas('enrollments.subject', function ($subQuery) use ($user) {
                $subQuery->where('assistant_id', $user->id);
            });
        }

        return $query->whereRaw('1 = 0');
    }
}
