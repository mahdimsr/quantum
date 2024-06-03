<?php

namespace App\Filament\Resources\CoinResource\Pages;

use App\Filament\Resources\CoinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoin extends EditRecord
{
    protected static string $resource = CoinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
