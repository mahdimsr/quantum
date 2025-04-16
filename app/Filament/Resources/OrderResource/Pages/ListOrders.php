<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Events\PendingOrderCreated;
use App\Filament\Resources\OrderResource;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Manual Dynamic Reward Order')
                ->modal()
                ->form([
                    Select::make('coin')
                        ->options(Coin::query()->pluck('name', 'id')->toArray())
                        ->searchable(),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('margin')
                                ->numeric()
                                ->columnSpan(1),
                            TextInput::make('leverage')
                                ->numeric()
                                ->maxValue(10)
                                ->columnSpan(1),
                        ]),
                    Grid::make(2)
                        ->schema([
                            Radio::make('position')
                                ->options([
                                    SideEnum::LONG->name => SideEnum::LONG->value,
                                    SideEnum::SHORT->name => SideEnum::SHORT->value,
                                ])
                                ->columnSpan(1),
                            TextInput::make('sl')
                                ->numeric()
                                ->columnSpan(1),
                        ]),
                ])->action(function (array $data) {

                    $coin = Coin::query()->find($data['coin']);
                    $leverage = $data['leverage'];
                    $balance = $data['margin'];
                    $side = SideEnum::from($data['position']);
                    $sl = $data['sl'];

                    $order = Order::query()->create([
                        'symbol' => $coin->symbol(),
                        'coin_name' => $coin->name,
                        'exchange' => 'coinex',
                        'leverage' => $leverage,
                        'side' => $side,
                        'type' => TypeEnum::MARKET,
                        'status' => OrderStatusEnum::MANUAL_CREATED,
                        'price' => 0,
                        'sl' => $sl,
                        'strategy' => StrategyEnum::DYNAMIC_REWARD,
                        'balance' => $balance,
                    ]);

                    event(new PendingOrderCreated($order));

                })
        ];
    }
}
