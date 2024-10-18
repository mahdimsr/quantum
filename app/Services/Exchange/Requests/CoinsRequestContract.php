<?php

namespace App\Services\Exchange\Requests;


use App\Services\Exchange\Responses\CoinsResponseContract;

interface CoinsRequestContract
{
    public function coins(): CoinsResponseContract;
}
