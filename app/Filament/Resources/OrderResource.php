<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Events\OrderClosedEvent;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Services\Exchange\BingX\BingXService;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Facade\Exchange;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Tables\Columns\TextColumn::make('coin_name'),
                Tables\Columns\TextColumn::make('position_id'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('side')
                    ->badge(),
                Tables\Columns\TextColumn::make('balance')
            ])
            ->filters([
                Tables\Filters\Filter::make('pending')
                    ->query(fn(Builder $query) => $query->where('status', OrderStatusEnum::OPEN->value))
                    ->default()
            ])
            ->actions([
                Tables\Actions\Action::make('close')
                    ->requiresConfirmation()
                    ->visible(fn(Order $order): bool => $order->status == OrderStatusEnum::OPEN)
                    ->disabled(fn(Order $order): bool => $order->status != OrderStatusEnum::OPEN)
                    ->action(function (Order $order) {

                        $closeOrderResponse = Exchange::closePositionByPositionId($order->position_id, $order->symbol);

                        if ($closeOrderResponse->isSuccess()) {

                            event(new OrderClosedEvent($order));

                            Notification::make()
                                ->title('Order Closed')
                                ->success()
                                ->send();

                        } else {

                            Notification::make()
                                ->title('Sth Wrong ...')
                                ->danger()
                                ->send();
                        }

                    }),
                Tables\Actions\Action::make('update position Id')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn(Order $order): bool => ! isset($order->position_id))
                    ->disabled(fn(Order $order): bool => isset($order->position_id))
                    ->action(function (Order $order) {

                        $positionResponse = Exchange::currentPosition($order->symbol);


                        if ($positionResponse->isSuccess()) {

                            $order->update([
                                'position_id' => $positionResponse->position()->getPositionId(),
                            ]);

                            Notification::make()
                                ->title('Position ID updated')
                                ->success()
                                ->send();

                        } else {

                            Notification::make()
                                ->title('Sth Wrong ...')
                                ->danger()
                                ->send();
                        }

                    }),
                Tables\Actions\DeleteAction::make('delete')
            ])
            ->bulkActions([

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
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
