<?php


namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Enrollment;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('subject_id')
                ->relationship('subject', 'name')
                ->required(),

            Forms\Components\Select::make('semester_id')
                ->relationship('semester', 'name')
                ->required(),

            Forms\Components\Select::make('status')
                ->options([
                    Enrollment::STATUS_PENDING => 'Pending',
                    Enrollment::STATUS_APPROVED => 'Approved',
                    Enrollment::STATUS_REJECTED => 'Rejected',
                ])
                ->default(Enrollment::STATUS_PENDING),

            Forms\Components\TextInput::make('grade')
                ->numeric(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')->label('Предмет'),
                Tables\Columns\TextColumn::make('semester.name')->label('Семестар'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('grade'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->color('success')
                    ->visible(fn($record) => $record->status === Enrollment::STATUS_PENDING)
                    ->action(fn($record) => $record->update([
                        'status' => Enrollment::STATUS_APPROVED,
                    ])),

                Tables\Actions\Action::make('reject')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === Enrollment::STATUS_PENDING)
                    ->action(fn($record) => $record->update([
                        'status' => Enrollment::STATUS_REJECTED,
                    ])),

                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
