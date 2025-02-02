<?php

namespace App\Filament\Resources\BudgetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetRelationManager extends RelationManager
{
    protected static string $relationship = 'budgetsendemail';


    public function form(Forms\Form $form): Forms\Form
    {


        return $form
            ->schema([

                TextInput::make('subject')
                    ->label('Subject')
                    ->translateLabel()
                    ->readOnly(),

                TextInput::make('send_customer')
                    ->label('Send Customer')
                    ->translateLabel()
                   ->readOnly(),

                Forms\Components\RichEditor::make('message')
                    ->translateLabel()
                    ->columnSpan('full'),
                Forms\Components\Checkbox::make('status'),
                Forms\Components\DatePicker::make('created_at')->label('Send Date')->date('d/m/Y H:i:s'),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('budgetEmailSend')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                ->label('Subject')->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                ->boolean()
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View Details')
                    ->icon('heroicon-o-eye'),

            ])
            ->bulkActions([

            ]);
    }
}
