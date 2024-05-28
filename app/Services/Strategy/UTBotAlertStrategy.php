<?php

namespace App\Services\Strategy;

class UTBotAlertStrategy
{
    private array $closeArray;
    private mixed $nLoss;

    public function __construct(array $closeArray, mixed $nLoss) {
        $this->closeArray = $closeArray;
        $this->nLoss = $nLoss;
    }

    public function calculateATRTrailingStop(): array
    {
        $xATRTrailingStop = array_fill(0, count($this->closeArray), 0.0);

        for ($i = 1; $i < count($this->closeArray); $i++) {
            // Use the previous value of xATRTrailingStop or 0 if it was not set
            $prevXATRTrailingStop = $xATRTrailingStop[$i - 1] ?? 0.0;

            if ($this->closeArray[$i] > $prevXATRTrailingStop && $this->closeArray[$i - 1] > $prevXATRTrailingStop) {
                $xATRTrailingStop[$i] = max($prevXATRTrailingStop, $this->closeArray[$i] - $this->nLoss);
            } elseif ($this->closeArray[$i] < $prevXATRTrailingStop && $this->closeArray[$i - 1] < $prevXATRTrailingStop) {
                $xATRTrailingStop[$i] = min($prevXATRTrailingStop, $this->closeArray[$i] + $this->nLoss);
            } else {
                $xATRTrailingStop[$i] = ($this->closeArray[$i] > $prevXATRTrailingStop) ? $this->closeArray[$i] - $this->nLoss : $this->closeArray[$i] + $this->nLoss;
            }
        }

        return $xATRTrailingStop;
    }

    public function calculatePosition(array $src, array $xATRTrailingStop): array
    {
        $pos = [];
        $pos[0] = 0; // Initialize the first position to 0

        for ($i = 1; $i < count($src); $i++) {
            $prevSrc = $src[$i - 1];
            $prevXATRTrailingStop = $xATRTrailingStop[$i - 1] ?? 0;
            $prevPos = $pos[$i - 1] ?? 0;

            if ($prevSrc < $prevXATRTrailingStop && $src[$i] > $prevXATRTrailingStop) {
                $pos[$i] = 1;
            } elseif ($prevSrc > $prevXATRTrailingStop && $src[$i] < $prevXATRTrailingStop) {
                $pos[$i] = -1;
            } else {
                $pos[$i] = $prevPos;
            }
        }

        return $pos;
    }
}
