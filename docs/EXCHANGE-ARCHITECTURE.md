# Exchange Architecture Documentation

## Overview

The Quantum trading bot implements a sophisticated exchange architecture that provides a unified interface for multiple cryptocurrency exchanges. The architecture follows the Adapter pattern and uses contracts to ensure consistency across different exchange implementations.

## Architecture Components

### 1. Exchange Services

The system supports multiple exchanges through dedicated service classes:

#### Supported Exchanges
- **Bitunix** (`BitunixService`)
- **BingX** (`BingXService`) 
- **Coinex** (`CoineXService`)

#### Exchange Facade
The `Exchange` facade provides a unified interface for all exchange operations:

```php
use App\Services\Exchange\Facade\Exchange;

// Get available coins
$coins = Exchange::coins();

// Set leverage
$leverage = Exchange::setLeverage($symbol, $side, $leverage);

// Place order
$order = Exchange::setOrder($symbol, $type, $side, $positionSide, $amount, $price);

// Get current position
$position = Exchange::currentPosition($symbol);

// Close position
$result = Exchange::closePosition($symbol, $marketType, $type, $price, $amount);
```

### 2. Request Contracts (ContractRequest)

Request contracts define the interface for exchange operations:

#### AssetRequestContract
```php
interface AssetRequestContract
{
    public function futuresBalance(): ?AssetBalanceContract;
}
```

#### CandleRequestContract
```php
interface CandleRequestContract
{
    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract;
}
```

#### OrderRequestContract
```php
interface OrderRequestContract
{
    public function orders(?string $symbol = null, ?array $orderIds = null): OrderListResponseContract;
    public function setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price, mixed $client_id = null, ?Target $takeProfit = null, ?Target $stopLoss = null): ?SetOrderResponseContract;
}
```

#### PositionRequestContract
```php
interface PositionRequestContract
{
    public function currentPosition(string $symbol): ?PositionResponseContract;
    public function closePositionByPositionId(string $positionId, ?string $symbol = null): ?ClosePositionResponseContract;
    public function setStopLoss(string $symbol, mixed $stopLossPrice, string $stopLossType): ?PositionResponseContract;
    public function setTakeProfit(string $symbol, mixed $takeProfitPrice, string $takeProfitType): ?PositionResponseContract;
    public function positionHistory(string $symbol, string $positonId): ?PositionResponseContract;
}
```

#### SetLeverageRequestContract
```php
interface SetLeverageRequestContract
{
    public function setLeverage(string $symbol, SideEnum $side, string $leverage): SetLeverageResponseContract;
}
```

#### CoinsRequestContract
```php
interface CoinsRequestContract
{
    public function coins(): CoinsResponseContract;
}
```

### 3. Response Contracts

Response contracts define the structure for exchange responses:

#### AssetBalanceContract
```php
interface AssetBalanceContract
{
    public function message(): string;
    public function isSuccess(): bool;
    public function balance(): mixed;
    public function availableMargin(): mixed;
}
```

#### SetOrderResponseContract
```php
interface SetOrderResponseContract
{
    public function isSuccess(): bool;
    public function message(): string;
    public function order(): ?Order;
}
```

#### PositionResponseContract
```php
interface PositionResponseContract
{
    public function isSuccess(): bool;
    public function message(): string;
    public function position(): ?Position;
}
```

### 4. Adapter Response Classes

Adapter classes convert exchange-specific responses to standardized contracts:

#### Base Response Structure
```php
abstract class BaseResponse
{
    protected array $response;

    public function code(): int
    public function message(): string
    public function isSuccess(): bool
}
```

#### Exchange-Specific Adapters

**Bitunix Adapters:**
- `OrderResponseAdapter` - Converts Bitunix order responses
- `PositionResponseAdapter` - Converts Bitunix position responses
- `AssetBalanceResponseAdapter` - Converts Bitunix balance responses
- `CandleResponseAdapter` - Converts Bitunix candle data
- `ClosePositionResponseAdapter` - Converts Bitunix close position responses
- `OrderListResponseAdapter` - Converts Bitunix order list responses
- `PositionHistoryResponseAdapter` - Converts Bitunix position history
- `SetLeverageResponseAdapter` - Converts Bitunix leverage responses

**Coinex Adapters:**
- `OrderResponseAdapter` - Converts Coinex order responses
- `PositionResponseAdapter` - Converts Coinex position responses
- `AssetBalanceResponseAdapter` - Converts Coinex balance responses
- `CandleResponseAdapter` - Converts Coinex candle data
- `ClosePositionResponseAdapter` - Converts Coinex close position responses
- `OrderListResponseAdapter` - Converts Coinex order list responses
- `PositionHistoryResponseAdapter` - Converts Coinex position history
- `SetLeverageResponseAdapter` - Converts Coinex leverage responses

**BingX Adapters:**
- `SetOrderResponseAdapter` - Converts BingX order responses
- `PositionResponseAdapter` - Converts BingX position responses
- `AssetResponseAdapter` - Converts BingX asset responses
- `CandleResponseAdapter` - Converts BingX candle data
- `ClosePositionResponseAdapter` - Converts BingX close position responses
- `CoinResponseAdapter` - Converts BingX coin responses
- `SetLeverageResponseAdapter` - Converts BingX leverage responses

### 5. Repository Classes

Repository classes provide data transfer objects (DTOs) for structured data:

#### Order Repository
```php
class Order
{
    public static function create(?string $symbol, ?string $side, ?string $type, mixed $price, mixed $quantity, mixed $client_id = null, mixed $order_id = null): Order
    
    public function getOrderId(): mixed
    public function getClientId(): mixed
    public function getSymbol(): string
    public function getSide(): SideEnum
    public function getType(): TypeEnum
    public function getPrice(): mixed
    public function getQuantity(): mixed
}
```

#### Position Repository
```php
class Position
{
    public static function create(array $item): self
    
    public function getPositionId(): string
    public function getSymbol(): string
    public function getUnrealizeProfit(): string
    public function getRealizeProfit(): string
    public function getMarkPrice(): string
    public function getPnlPercent(): string
    public function getOrderId(): ?string
}
```

#### Asset Repository
```php
class Asset
{
    public static function fromArray(array $data): self
    
    public function getAvailable(): mixed
    public function getCcy(): mixed
    public function getFrozen(): mixed
    public function getMargin(): mixed
    public function getTransferrable(): mixed
    public function getUnrealizedPnl(): mixed
}
```

#### Candle Repository
```php
class Candle
{
    public static function fromArray(array $data): self
    
    public function getTime(): mixed
    public function getOpen(): mixed
    public function getHigh(): mixed
    public function getLow(): mixed
    public function getClose(): mixed
    public function getVolume(): mixed
    public function isBullish(): bool
    public function hasSellSignal(): bool
    public function hasBuySignal(): bool
}
```

#### Target Repository
```php
class Target
{
    public static function create(string $type, mixed $stopPrice, mixed $price, ?string $workingType = null): self
    
    public function getType(): string
    public function getStopPrice(): mixed
    public function getWorkingType(): ?string
    public function getPrice(): mixed
    public function toArray(): array
}
```

### 6. Enums

The system uses various enums for type safety:

#### SideEnum
- `BUY` / `SELL`
- `LONG` / `SHORT`

#### TypeEnum
- `MARKET` / `LIMIT`
- `STOP_MARKET` / `STOP_LIMIT`

#### ExchangeResolutionEnum
- Timeframe resolutions for candle data

#### TimeframeEnum
- Standard timeframes (1m, 5m, 15m, 1h, 4h, 1d, etc.)

## Usage Examples

### Getting Market Data
```php
// Get candle data
$candles = Exchange::candles('BTCUSDT', '1h', 100);

// Get available coins
$coins = Exchange::coins();
```

### Trading Operations
```php
// Set leverage
$leverage = Exchange::setLeverage('BTCUSDT', SideEnum::LONG, '10');

// Place order
$order = Exchange::setOrder(
    'BTCUSDT',
    TypeEnum::LIMIT,
    SideEnum::BUY,
    SideEnum::LONG,
    0.001,
    50000,
    'client_123'
);

// Get current position
$position = Exchange::currentPosition('BTCUSDT');
```

### Position Management
```php
// Close position by ID
$result = Exchange::closePositionByPositionId('pos_123', 'BTCUSDT');

// Set stop loss
$position = Exchange::setStopLoss('BTCUSDT', 45000, 'MARKET');

// Set take profit
$position = Exchange::setTakeProfit('BTCUSDT', 55000, 'MARKET');
```

### Account Management
```php
// Get futures balance
$balance = Exchange::futuresBalance();

// Get order history
$orders = Exchange::orders('BTCUSDT');
```

## Architecture Benefits

1. **Unified Interface**: Single API for all supported exchanges
2. **Type Safety**: Strong typing with enums and contracts
3. **Extensibility**: Easy to add new exchanges by implementing contracts
4. **Consistency**: Standardized response format across exchanges
5. **Maintainability**: Clear separation of concerns with adapters
6. **Testability**: Contract-based design enables easy mocking

## Adding New Exchanges

To add a new exchange:

1. Create service class implementing all required contracts
2. Create exchange-specific adapter classes
3. Update the Exchange facade to use the new service
4. Add exchange-specific configuration
5. Implement all required methods from contracts

This architecture ensures that the Quantum trading bot can seamlessly work with multiple exchanges while maintaining a consistent and reliable trading interface.
