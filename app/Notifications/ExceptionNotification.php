<?php

namespace App\Notifications;

use App\Channel\TelegramBotChannel;
use App\Channel\TelegramBotNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ExceptionNotification extends Notification
{
    use Queueable;

    protected string $errorMessage;

    public function __construct(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
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

    public function toTelegram(object $notifiable): string
    {

        $message = "🧨🧨Exception🧨🧨" . "\n";
        $message .= "error: $this->errorMessage";

        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->line($message);
    }
}
