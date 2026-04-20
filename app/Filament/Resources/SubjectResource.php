<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\RelationManagers;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),

                Forms\Components\TextInput::make('code')
                    ->required()
                    ->label('Code'),

                Forms\Components\TextInput::make('ects')
                    ->numeric()
                    ->required()
                    ->label('ECTS'),

                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'name')
                    ->required()
                    ->label('Semester'),

                Forms\Components\Select::make('faculty_id')
                    ->relationship('faculty', 'name')
                    ->required()
                    ->label('Faculty'),

                Forms\Components\Select::make('professor_id')
                    ->relationship('professor', 'name')
                    ->label('Professor'),

                Forms\Components\Select::make('assistant_id')
                    ->relationship('assistant', 'name')
                    ->label('Assistant'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('faculty.name')->label('Faculty'),
                Tables\Columns\TextColumn::make('semester.name')->label('Semester'),
                Tables\Columns\TextColumn::make('ects'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('faculty_id')
                    ->relationship('faculty', 'name')
                    ->label('Faculty'),

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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->role === 'professor') {
            return $query->where('professor_id', $user->id);
        }

        if ($user->role === 'assistant') {
            return $query->where('assistant_id', $user->id);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'admin';
    }
}
