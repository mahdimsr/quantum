<?php  #items: array:500 [▶]


namespace App\Services\Exchange\Bingx\Response;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Exchange\Responses\CandleResponseContract;

class CandleResponseAdapter extends BingXResponse implements CandleResponseContract
{
    public function data(): CandleCollection
    {
        $data = $this->response['data'];

        if (count($data) == 0) {

            return CandleCollection::make([]);
        }

        $data = collect($data)->map(fn($item) => Candle::fromArray($item));

        return CandleCollection::make($data);
    }
}
