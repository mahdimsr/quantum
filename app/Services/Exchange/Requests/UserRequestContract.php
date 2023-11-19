<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\UserResponseContract;

interface UserRequestContract
{
    public function user(): UserResponseContract;
}
