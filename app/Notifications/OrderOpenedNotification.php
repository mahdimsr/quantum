<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderOpenedNotification extends Notification
{
    use Queueable;

    protected Order $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
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

    public function toTelegram(object $notifiable)
    {
        $message = "✌️ Order Opened ✌️ \n";
        $message .= "Coin: " . $this->order->coin_name . "\n";
        $message .= $this->order->side->isLong() ? "Long 🟢" : "Short 🔴";

        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->line($message);
    }
}
