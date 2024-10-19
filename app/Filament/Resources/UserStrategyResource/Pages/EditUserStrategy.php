<?php

namespace App\Filament\Resources\UserStrategyResource\Pages;

use App\Filament\Resources\UserStrategyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserStrategy extends EditRecord
{
    protected static string $resource = UserStrategyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
