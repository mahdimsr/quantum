<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Exchange\Responses\CandleResponseContract;

class CandleResponseAdapter extends BaseResponse implements CandleResponseContract
{
    public function data(): CandleCollection
    {
        $data = $this->response['data'];

        $data = collect($data)->map(function ($item) {

            $item['time'] = $item['created_at'];

            return Candle::fromArray($item);
        });

        return CandleCollection::make($data);
    }
}
