<?php

namespace App\Filament\Resources;

use App\Enums\StrategyEnum;
use App\Filament\Resources\UserStrategyResource\Actions\FormModalAction;
use App\Filament\Resources\UserStrategyResource\Pages;
use App\Models\User;
use App\Models\UserStrategy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserStrategyResource extends Resource
{
    protected static ?string $model = UserStrategy::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('balance'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                FormModalAction::create()
            ])
            ->actions([
                FormModalAction::create()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
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
            'index' => Pages\ListUserStrategies::route('/')
        ];
    }
}
