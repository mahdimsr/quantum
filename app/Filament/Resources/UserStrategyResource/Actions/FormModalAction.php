<?php

namespace App\Filament\Resources\UserStrategyResource\Actions;

use App\Enums\StrategyEnum;
use App\Models\User;
use App\Models\UserStrategy;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;

class FormModalAction extends Action
{
    public static function create()
    {
        return Action::make('Add Strategy')
                     ->fillForm(fn(UserStrategy $userStrategy) => ['user_id' => $userStrategy->user_id, 'name' => $userStrategy->name, 'balance' => $userStrategy->balance])
                     ->form([Select::make('user_id')
                                   ->label('User')
                                   ->options(User::all()->pluck('name', 'id')->toArray())
                                   ->required(),
                             Select::make('name')
                                   ->label('Strategy Name')
                                   ->options(StrategyEnum::optionCases())
                                   ->required(),
                             TextInput::make('balance')
                                      ->label('Balance')
                                      ->numeric()
                            ])
                     ->action(function (array $data, UserStrategy $userStrategy): void {

                         $userStrategy->user_id = $data['user_id'];
                         $userStrategy->name    = $data['name'];
                         $userStrategy->balance = $data['balance'];

                         $userStrategy->save();
                     });
    }
}
