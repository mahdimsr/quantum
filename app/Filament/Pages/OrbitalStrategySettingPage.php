<?php

namespace App\Filament\Pages;

use App\Models\Coin;
use App\Services\Exchange\Enums\TimeframeEnum;
use App\Settings\OrbitalStrategySetting;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class OrbitalStrategySettingPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'gameicon-orbital';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Orbital Strategy';

    protected static string $settings = OrbitalStrategySetting::class;

    public function getTitle(): string|Htmlable
    {
        return 'Orbital Strategy';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Basics')
                            ->schema([
                                Toggle::make('active'),
                                Grid::make()->schema([
                                    TextInput::make('margin')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('leverage')
                                        ->numeric()
                                        ->required(),
                                    Select::make('timeframe')
                                        ->enum(TimeframeEnum::class)
                                        ->options(TimeframeEnum::class),
                                    Select::make('coins')
                                        ->multiple()
                                        ->searchable()
                                        ->options(Coin::all()->each(fn(Coin $coin) => $coin->symbol = $coin->symbol('-'))->pluck('symbol', 'name')->toArray()),
                                ]),
                            ]),
                        Tab::make('Stop Loss and Close Position')->schema([
                            Radio::make('stopLossType')
                                ->inline()
                                ->required()
                                ->options([
                                    'low-risk' => 'Low Risk',
                                    'high-risk' => 'High Risk',
                                ])
                                ->descriptions([
                                    'low-risk' => 'now on the value is base on utbot 2,3 config',
                                    'high-risk' => 'now on the value is base on utbot 1,2 config',
                                ]),
                            Checkbox::make('autoClose'),
                        ]),
                    ])
            ]);
    }
}
