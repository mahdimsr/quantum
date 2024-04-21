<?php

namespace App\Services\Exchange\Repository;

use App\Services\Exchange\Exceptions\CandleException;

class Candle
{
    private mixed $time;
    private mixed $open;
    private mixed $high;
    private mixed $low;
    private mixed $close;
    private mixed $volume;

    /**
     * @throws CandleException
     */
    public static function fromArray(array $data) : self
    {
        self::validateArrayKeys($data);

        $candle = new Candle();
        $candle->setTime($data['time']);
        $candle->setOpen($data['open']);
        $candle->setHigh($data['high']);
        $candle->setLow($data['low']);
        $candle->setClose($data['close']);
        $candle->setVolume($data['volume']);

        return $candle;
    }

    /**
     * @throws CandleException
     */
    public static function validateArrayKeys(array $data): void
    {
        if (!array_key_exists('time',$data)){
            throw CandleException::keyNotExist('time');
        }

        if (!array_key_exists('open',$data)){
            throw CandleException::keyNotExist('open');
        }

        if (!array_key_exists('high',$data)){
            throw CandleException::keyNotExist('high');
        }

        if (!array_key_exists('low',$data)){
            throw CandleException::keyNotExist('low');
        }

        if (!array_key_exists('close',$data)){
            throw CandleException::keyNotExist('close');
        }

        if (!array_key_exists('volume',$data)){
            throw CandleException::keyNotExist('volume');
        }
    }

    /**
     * @return mixed
     */
    public function getTime(): mixed
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime(mixed $time): void
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getOpen(): mixed
    {
        return $this->open;
    }

    /**
     * @param mixed $open
     */
    public function setOpen(mixed $open): void
    {
        $this->open = $open;
    }

    /**
     * @return mixed
     */
    public function getHigh(): mixed
    {
        return $this->high;
    }

    /**
     * @param mixed $high
     */
    public function setHigh(mixed $high): void
    {
        $this->high = $high;
    }

    /**
     * @return mixed
     */
    public function getLow(): mixed
    {
        return $this->low;
    }

    /**
     * @param mixed $low
     */
    public function setLow(mixed $low): void
    {
        $this->low = $low;
    }

    /**
     * @return mixed
     */
    public function getClose(): mixed
    {
        return $this->close;
    }

    /**
     * @param mixed $close
     */
    public function setClose(mixed $close): void
    {
        $this->close = $close;
    }

    /**
     * @return mixed
     */
    public function getVolume(): mixed
    {
        return $this->volume;
    }

    /**
     * @param mixed $volume
     */
    public function setVolume(mixed $volume): void
    {
        $this->volume = $volume;
    }


}
