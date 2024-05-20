<?php

namespace App\Notifications;

use App\Channel\TelegramBotChannel;
use App\Channel\TelegramBotNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BollingerBandsNotification extends Notification implements TelegramBotNotification
{
    use Queueable;

    protected string $symbol;
    protected string $position;
    protected mixed $price;

    public function __construct(string $symbol, string $position, mixed $price)
    {
        $this->symbol = $symbol;
        $this->position = $position;
        $this->price = $price;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramBotChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toTelegramBot(): string
    {
       $telegramMessage = "algorithm: Bollinger-bands \n";
       $telegramMessage = "price: $this->price \n";
       $telegramMessage .= "time: " . now()->toDateTimeString() . "\n";
       $telegramMessage .= "symbol: $this->symbol \n";
       $telegramMessage .= $this->position == 'long' ? "Long 🟢" : "Short 🔴";

       return $telegramMessage;
    }
}
