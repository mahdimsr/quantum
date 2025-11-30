<?php

namespace App\Services\Strategy;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;
use http\Exception\InvalidArgumentException;

class StrategyPipeline
{
    protected CandleCollection $candleCollection;
    protected array $algorithms;
    private int $shortSignals = 0;
    private int $longSignals = 0;

    public function send(CandleCollection $candleCollection): static
    {
        $this->candleCollection = $candleCollection;
        return $this;
    }

    public function through(array $algorithms): static
    {
        $this->algorithms = $algorithms;
        return $this;
    }

    public function then(\Closure $closure): CandleCollection
    {
        $pipeline = array_reduce(
            $this->algorithms,
            $this->carry(),
            $closure
        );

        return $pipeline($this->candleCollection);
    }

    public function carry(): \Closure
    {
        return function ($nextAlgorithm, $currentAlgorithm) {
            return function ($data) use ($nextAlgorithm, $currentAlgorithm) {
                if (is_string($currentAlgorithm) and class_exists($currentAlgorithm)){
                    $algorithm = new $currentAlgorithm($data);

                    if (! $algorithm instanceof AlgorithmAbstract){
                        throw new InvalidArgumentException('the algorithm should be an instance of AlgorithmContract');
                    }

                    if ($algorithm->signal() == PositionTypeEnum::SHORT){
                        $this->shortSignals += 1;
                    }

                    if ($algorithm->signal() == PositionTypeEnum::LONG){
                        $this->longSignals += 1;
                    }

                    return $algorithm->handle($nextAlgorithm);
                }

                throw new InvalidArgumentException('the algorithm class '. $currentAlgorithm . ' not exists');
            };
        };
    }

    public function run(): CandleCollection
    {
        return $this->then(function ($payload) {
            return $payload;
        });
    }

    public function hasShortEntry(): bool
    {
        return $this->shortSignals > 0 and $this->shortSignals == count($this->algorithms);
    }

    public function hasLongEntry(): bool
    {
        return $this->longSignals > 0 and $this->longSignals == count($this->algorithms);
    }
}
