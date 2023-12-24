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

    protected string $symbol;
    protected string $position;
    protected mixed $rsi;

    public function __construct(string $symbol, string $position, mixed $rsi)
    {
        $this->symbol = $symbol;
        $this->position = $position;
        $this->rsi = $rsi;
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
        $message = "Symbol: $this->symbol \n";
        $message .= "Position: $this->position";
        $message .= "RSI: $this->rsi";

        return $message;
    }
}
