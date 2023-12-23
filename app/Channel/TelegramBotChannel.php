<?php

namespace App\Channel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TelegramBotChannel
{
    public function send(mixed $notifiable, TelegramBotNotification $notification): void
    {
        $client = new Client();

        $token = Config::get('notification.telegram.bot_token');
        $chatId = Config::get('notification.telegram.chatId');

        try {

            $request = $client->post("https://api.telegram.org/bot$token/sendMessage", [
                'chat_id' => $chatId,
                'message' => $notification->toTelegramBot()
            ]);

        } catch (GuzzleException $e) {

            Log::error($e);
        }
    }
}
