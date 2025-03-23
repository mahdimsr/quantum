<?php

namespace App\Filament\Resources\TokenResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TokensRelationManager extends RelationManager
{
    protected static string $relationship = 'exchangeTokens';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('exchange')->options([
                    'coinex' => 'Coinex',
                ])->required(),

                Select::make('name')->options([
                    'api_key' => 'Api Key',
                    'secret_key' => 'Secret Key',
                ])->required(),

                TextInput::make('value')->required()->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('exchange'),
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
