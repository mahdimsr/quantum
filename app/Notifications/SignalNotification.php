<?php

namespace App\Notifications;

use App\Channel\TelegramBotChannel;
use App\Channel\TelegramBotNotification;
use App\Services\Exchange\Enums\SymbolEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SignalNotification extends Notification implements TelegramBotNotification
{
    use Queueable;

    protected SymbolEnum $symbol;
    protected string $position;
    protected mixed $currentPrice;

    public function __construct(SymbolEnum $symbol, string $position, mixed $currentPrice)
    {
        $this->symbol = $symbol;
        $this->position = $position;
        $this->currentPrice = $currentPrice;
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
        return "position: $this->position
        \n
        token: {$this->symbol->value}
        \n
        currentPrice: $this->currentPrice";
    }
}
