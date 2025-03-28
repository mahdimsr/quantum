<?php

namespace App\Filament\Pages;

use App\Settings\AppSetting as SpatieAppSetting;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class AppSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';

    protected static string $settings = SpatieAppSetting::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Leverage')
                            ->schema([
                                TextInput::make('leverage')
                                    ->numeric()
                                    ->nullable()
                            ]),
                    ])
            ]);
    }
}
