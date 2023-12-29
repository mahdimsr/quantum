<?php

namespace App\Channel;

interface TelegramBotNotification
{
    public function toTelegramBot(): string;
}
