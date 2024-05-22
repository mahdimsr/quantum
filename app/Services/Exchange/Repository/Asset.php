<?php

namespace App\Services\Exchange\Repository;

class Asset
{

    public mixed $available;
    public mixed $ccy;
    public mixed $frozen;
    public mixed $margin;
    public mixed $transferrable;
    public mixed $unrealized_pnl;

    public static function fromArray(array $data): self {

        $asset = new Asset();
        $asset->setAvailable($data['available']);
        $asset->setCcy($data['ccy']);
        $asset->setFrozen($data['frozen']);
        $asset->setMargin($data['margin']);
        $asset->setTransferrable($data['transferrable']);
        $asset->setUnrealizedPnl($data['unrealized_pnl']);

        return $asset;
    }

    public function getAvailable(): mixed
    {
        return $this->available;
    }

    private function setAvailable(mixed $available): void
    {
        $this->available = $available;
    }

    public function getCcy(): mixed
    {
        return $this->ccy;
    }

    private function setCcy(mixed $ccy): void
    {
        $this->ccy = $ccy;
    }

    public function getFrozen(): mixed
    {
        return $this->frozen;
    }

    private function setFrozen(mixed $frozen): void
    {
        $this->frozen = $frozen;
    }

    public function getMargin(): mixed
    {
        return $this->margin;
    }

    private function setMargin(mixed $margin): void
    {
        $this->margin = $margin;
    }

    public function getTransferrable(): mixed
    {
        return $this->transferrable;
    }

    private function setTransferrable(mixed $transferrable): void
    {
        $this->transferrable = $transferrable;
    }

    public function getUnrealizedPnl(): mixed
    {
        return $this->unrealized_pnl;
    }

    private function setUnrealizedPnl(mixed $unrealized_pnl): void
    {
        $this->unrealized_pnl = $unrealized_pnl;
    }
}
