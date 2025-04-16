<?php

namespace App\Filament\Pages;

use App\Settings\MandaStrategySetting;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageManda extends SettingsPage
{
    protected static ?string $navigationIcon = 'rpg-snake';

    protected static string $settings = MandaStrategySetting::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Manda Strategy';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('settings')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Entry Config')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextInput::make('margin')
                                            ->numeric()
                                            ->required(),
                                        TextInput::make('leverage')
                                            ->numeric()
                                            ->required(),
                                        Select::make('time_frame')
                                            ->required()
                                            ->options([
                                                '1h' => '1 Hour',
                                                '4h' => '4 Hours',
                                                '1d' => 'Daily',
                                            ])
                                    ])
                            ]),
                        Tab::make('Stop Loss')
                            ->schema([
                                Radio::make('sl_mode')
                                    ->required()
                                    ->inline()
                                    ->options([
                                        'near' => 'near',
                                        'away' => 'away',
                                    ])
                                    ->descriptions([
                                        'near' => 'small ut bot trailing',
                                        'away' => 'big ut bot trailing',
                                    ]),
                                Radio::make('close_on_reverse')
                                    ->label('Close position when reverse signal observed?')
                                    ->required()
                                    ->boolean()
                                    ->inline()
                            ]),
                    ])
            ]);
    }
}
