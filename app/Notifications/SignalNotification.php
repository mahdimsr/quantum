<?php

namespace App\Notifications;

use App\Channel\TelegramBotChannel;
use App\Channel\TelegramBotNotification;
use App\Services\Exchange\Enums\SymbolEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class SignalNotification extends Notification implements TelegramBotNotification
{
    use Queueable;

    protected string $symbol;
    protected string $position;
    protected mixed $currentPrice;
    protected mixed $takeProfit;
    protected mixed $stopLoss;

    public function __construct(string $symbol, string $position, mixed $currentPrice, mixed $takeProfit, mixed $stopLoss)
    {
        $this->symbol = $symbol;
        $this->position = $position;
        $this->currentPrice = $currentPrice;
        $this->takeProfit = $takeProfit;
        $this->stopLoss = $stopLoss;
    }


    public function via(object $notifiable): array
    {
        return [TelegramBotChannel::class];
    }


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toTelegramBot(): string
    {
        $positionTitle = $this->position == 'long' ? "Long 🟢" : "Short 🔴";
        $nowDateTimeString = Carbon::now()->toDateTimeString();

        $message = "Symbol: $this->symbol \n";
        $message .= "Position: $positionTitle\n";
        $message .= "CurrentPrice: $this->currentPrice\n";
        $message .= "TakeProfit: $this->takeProfit\n";
        $message .= "StopLoss: $this->stopLoss\n";
        $message .= "Now: $nowDateTimeString\n";

        return $message;
    }
}
