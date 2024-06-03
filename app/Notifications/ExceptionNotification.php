<?php

namespace App\Notifications;

use App\Channel\TelegramBotChannel;
use App\Channel\TelegramBotNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExceptionNotification extends Notification implements TelegramBotNotification
{
    use Queueable;

    protected string $symbol;
    protected string $errorMessage;

    public function __construct(string $symbol, string $errorMessage)
    {
        $this->symbol = $symbol;
        $this->errorMessage = $errorMessage;
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

        $message = "🧨🧨Exception🧨🧨" . "\n";
        $message .= "symbol: $this->symbol \n";
        $message .= "error: $this->errorMessage";

        return $message;
    }
}
