<?php

namespace App\Filament\Resources;

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

    protected static ?string $navigationLabel = 'مدیریت رمز ارزها';

    protected static ?string $modelLabel = 'رمز ارز';

    protected static ?string $pluralModelLabel = 'رمز ارزها';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->minValue(1)
                    ->maxValue(191)
                    ->label('نام'),

                Forms\Components\TextInput::make('percent_tolerance')
                    ->type('number')
                    ->required()
                    ->label('درصد تغییر قیمت'),

                Forms\Components\TextInput::make('leverage')
                    ->type('number')
                    ->required()
                    ->label('اهرم'),

                Forms\Components\TextInput::make('order')
                    ->type('number')
                    ->required()
                    ->label('اولویت'),

                Forms\Components\Select::make('strategy_type')
                    ->label('استراتژی معاملاتی')
                    ->options([
                        1 => 'UT BOT',
                        2 => 'BOLLINGER BANDS',
                    ])
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull()
                ,

                Toggle::make('status')
                    ->label('وضعیت')
                    ->onIcon('heroicon-s-eye')
                    ->offIcon('heroicon-s-x-circle')
                    ->columnSpan('full')
                    ->onColor('success'),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->sortable()
                    ->searchable()
                ,
                ToggleColumn::make('status')
                    ->label('وضعیت')
                    ->onColor('success'),
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
