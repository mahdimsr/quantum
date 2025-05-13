<?php

namespace App\Filament\Pages;

use App\Models\Coin;
use App\Services\Exchange\Enums\TimeframeEnum;
use App\Settings\HarmonySetting;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class HarmonySettingPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'si-harmonyos';

    protected static string $settings = HarmonySetting::class;
    protected static ?string $navigationLabel = 'Harmony Strategy';
    protected static ?string $navigationGroup = 'Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Basics')
                            ->schema([
                                Toggle::make('active')
                                    ->label('Active (Inactive in weekend always)'),
                                Grid::make()->schema([
                                    TextInput::make('margin')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('leverage')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('max_positions')
                                        ->hint('Max position open daily')
                                        ->numeric()
                                        ->required(),
                                    Select::make('coins')
                                        ->multiple()
                                        ->searchable()
                                        ->options(Coin::all()->each(fn(Coin $coin) => $coin->symbol = $coin->symbol('-'))->pluck('symbol', 'name')->toArray()),
                                ]),
                            ]),
                        Tab::make('Take Profit')
                            ->schema([
                                Toggle::make('compound')
                                    ->label('Add TP to margin value'),
                                Radio::make('tp_percent')
                                    ->inline()
                                    ->required()
                                    ->options([
                                        '5' => '5',
                                        '10' => '10',
                                        '20' => '20',
                                    ])
                                    ->descriptions([
                                        '5' => '5 Percent each step',
                                        '10' => '10 Percent each step',
                                        '20' => '20 Percent each step',
                                    ]),

                            ])
                    ])
            ]);
    }
}
