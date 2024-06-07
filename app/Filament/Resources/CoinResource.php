<?php

namespace App\Filament\Resources;

use App\Enums\CoinStatusEnum;
use App\Enums\StrategyEnum;
use App\Filament\Resources\CoinResource\Pages;
use App\Filament\Resources\CoinResource\RelationManagers;
use App\Models\Coin;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoinResource extends Resource
{
    protected static ?string $model = Coin::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->minValue(1)
                    ->maxValue(191),

                Forms\Components\TextInput::make('percent_tolerance')
                    ->required(),

                Forms\Components\TextInput::make('leverage')
                    ->type('number')
                    ->required(),

                Forms\Components\TextInput::make('order')
                    ->type('number')
                    ->required(),

                Forms\Components\Select::make('strategy_type')
                    ->options(StrategyEnum::optionCases())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan('md'),



                Forms\Components\Select::make('status')
                    ->options(CoinStatusEnum::optionCases())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan('md'),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('leverage')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\SelectColumn::make('strategy_type')
                    ->options(StrategyEnum::optionCases()),
                Tables\Columns\SelectColumn::make('status')
                    ->options(CoinStatusEnum::optionCases()),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCoins::route('/'),
            'create' => Pages\CreateCoin::route('/create'),
            'edit' => Pages\EditCoin::route('/{record}/edit'),
        ];
    }
}
