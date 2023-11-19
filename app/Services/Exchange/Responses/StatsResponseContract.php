<?php

namespace App\Services\Exchange\Responses;

interface StatsResponseContract
{
    public function bestSell(): string;
    public function isClosed(): bool;
    public function dayOpen(): string;
    public function dayHigh(): string;
    public function bestBuy(): string;
    public function volumeSrc(): string;
    public function dayLow(): string;
    public function latest(): string;
    public function volumeDst(): string;
    public function dayChange(): string;
    public function dayClose(): string;
}
