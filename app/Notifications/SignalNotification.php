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
use NotificationChannels\Telegram\TelegramMessage;

class SignalNotification extends Notification
{
    use Queueable;

    protected string $symbol;
    protected string $position;
    protected string $strategy;

    public function __construct(string $symbol, string $position, mixed $strategy)
    {
        $this->symbol = $symbol;
        $this->position = $position;
        $this->strategy = $strategy;
    }


    public function via(object $notifiable): array
    {
        return ['telegram'];
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

    public function toTelegram(object $notifiable)
    {
        $positionTitle = in_array($this->position , ['long', 'buy']) ? "Long 🟢" : "Short 🔴";
        $nowDateTimeString = Carbon::now()->toDateTimeString();

        $message = "Strategy: $this->strategy \n";
        $message .= "Symbol: $this->symbol \n";
        $message .= "Position: $positionTitle\n";
        $message .= "Now: $nowDateTimeString\n";

        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->line($message);
    }
}
