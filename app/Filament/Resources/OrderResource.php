<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Events\OrderClosedEvent;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Services\Exchange\BingX\BingXService;
use App\Services\Exchange\Enums\SideEnum;
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
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('side')
                    ->badge(),
                Tables\Columns\TextColumn::make('balance')
            ])
            ->filters([
                Tables\Filters\Filter::make('pending')
                    ->query(fn(Builder $query) => $query->where('status', OrderStatusEnum::PENDING->value))
                    ->default()
            ])
            ->actions([
                Tables\Actions\Action::make('close')
                    ->requiresConfirmation()
                    ->visible(fn(Order $order): bool => $order->status == OrderStatusEnum::PENDING)
                    ->disabled(fn(Order $order): bool => $order->status != OrderStatusEnum::PENDING)
                    ->action(function (Order $order) {

                        $bingXService = app(BingXService::class);

                        $closeOrderResponse = $bingXService->closePositionByPositionId($order->position_id);

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

                    })
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
