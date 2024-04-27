<?php

namespace App\Services\Exchange\Responses;

interface CurrentResponseContract
{
    public function symbol(): string;
    public function volume(): string;
    public function indexPrice(): string;
    public function markPrice(): string;
}
