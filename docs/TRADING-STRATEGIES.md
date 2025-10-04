# Trading Strategies Documentation

## Overview

The Quantum trading bot implements a sophisticated strategy system that combines multiple technical indicators to generate trading signals. The system uses a pipeline-based approach where multiple algorithms are executed in sequence, and trading signals are generated only when all algorithms agree on the market direction.

## Strategy Architecture

### Core Components

#### 1. Strategy Pipeline System
The strategy system uses a pipeline pattern where multiple algorithms are executed in sequence:

```php
$strategy = new Strategy();
$strategy->send($candleCollection)->through([
    LNLTrendAlgorithm::class,
    SmallUtBotAlgorithm::class
])->run();
```

#### 2. Algorithm Abstract Class
All trading algorithms must extend the `AlgorithmAbstract` class:

```php
abstract class AlgorithmAbstract
{
    public function __construct(protected CandleCollection $candleCollection) {}
    
    public function handle(\Closure $next): CandleCollection
    {
        return $next($this->candleCollection);
    }
    
    abstract public function signal(): ?PositionTypeEnum;
}
```

#### 3. Strategy Contract
All indicator strategies must implement the `StrategyContract` interface:

```php
interface StrategyContract
{
    public function isBullish(): bool;
    public function isBearish(): bool;
    public function sellSignal(?int $candleIndex = 0): bool;
    public function buySignal(?int $candleIndex = 0): bool;
    public function currentPrice(): mixed;
    public function collection(): CandleCollection;
}
```

## Available Strategies

### 1. Harmony Strategy

The Harmony strategy is designed for compound trading with take-profit management and margin optimization.

#### Features
- **Compound Trading**: Automatically adds realized profits to margin
- **Take Profit Management**: Sets automatic take-profit levels
- **Margin Optimization**: Manages position sizing based on available margin
- **Multi-Algorithm**: Combines LNLTrend and UTBot algorithms

#### Configuration
```php
class HarmonySetting extends Settings
{
    public ?bool $active = false;
    public ?int $margin = 0;
    public ?int $leverage = 0;
    public ?int $max_positions = 0;
    public ?array $coins = [];
    public ?bool $compound = false;
    public ?int $tp_percent = 0;
}
```

#### Usage
```php
$harmonyStrategy = new HarmonyStrategy();

// Check if strategy is active
if ($harmonyStrategy->active()) {
    // Get trading signal
    $signal = $harmonyStrategy->signal($candleCollection);
    
    if ($signal === PositionTypeEnum::LONG) {
        // Execute long position
    } elseif ($signal === PositionTypeEnum::SHORT) {
        // Execute short position
    }
}

// Add profit to margin (compound feature)
$harmonyStrategy->addToMargin(100.50);
```

#### Key Methods
- `signal(CandleCollection $candleCollection): ?PositionTypeEnum` - Generates trading signals
- `active(): ?bool` - Checks if strategy is active
- `margin(): ?int` - Gets current margin
- `leverage(): ?int` - Gets leverage setting
- `timeframe(): ?string` - Gets timeframe setting
- `coins(): ?array` - Gets supported coins
- `maxPositions(): ?int` - Gets maximum positions
- `takeProfitPercentage(): ?int` - Gets take profit percentage
- `compound(): ?bool` - Checks if compound is enabled
- `addToMargin(float $value): void` - Adds value to margin

### 2. Orbital Strategy

The Orbital strategy is designed for trend-following with automatic stop-loss management.

#### Features
- **Trend Detection**: Identifies trend starts and enters positions
- **Automatic Stop Loss**: Configurable stop-loss management
- **Auto Close**: Optional automatic position closing
- **Multi-Algorithm**: Combines LNLTrend and UTBot algorithms

#### Configuration
```php
class OrbitalStrategySetting extends Settings
{
    public ?bool $active = false;
    public ?int $margin = null;
    public ?int $leverage = null;
    public ?string $timeframe = null;
    public array $coins = [];
    public ?string $stopLossType = null;
    public ?bool $autoClose = false;
}
```

#### Usage
```php
$orbitalStrategy = new OrbitalStrategy();

// Check if strategy is active
if ($orbitalStrategy->active()) {
    // Get trading signal
    $signal = $orbitalStrategy->signal($candleCollection);
    
    if ($signal === PositionTypeEnum::LONG) {
        // Execute long position with trend following
    } elseif ($signal === PositionTypeEnum::SHORT) {
        // Execute short position with trend following
    }
}
```

#### Key Methods
- `signal(CandleCollection $candleCollection): ?PositionTypeEnum` - Generates trading signals
- `active(): bool` - Checks if strategy is active
- `margin(): ?int` - Gets current margin
- `leverage(): ?int` - Gets leverage setting
- `timeframe(): ?string` - Gets timeframe setting
- `coins(): ?array` - Gets supported coins
- `stopLossType(): ?string` - Gets stop loss type
- `autoClose(): ?bool` - Checks if auto close is enabled

## Technical Indicators

### 1. LNLTrend Algorithm

The LNLTrend algorithm analyzes trend direction using cloud and line indicators.

#### Features
- **Trend Cloud Analysis**: Analyzes trend cloud patterns
- **Trend Line Analysis**: Analyzes trend line patterns
- **Power Trend Detection**: Identifies strong trend conditions
- **Bullish/Bearish Detection**: Determines market direction

#### Usage
```php
$lnlTrend = new LNLTrendAlgorithm($candleCollection);
$signal = $lnlTrend->signal();

if ($signal === PositionTypeEnum::LONG) {
    // Bullish trend detected
} elseif ($signal === PositionTypeEnum::SHORT) {
    // Bearish trend detected
}
```

#### Key Methods
- `currentTrend(): string` - Gets current trend direction
- `isPowerTrend(): bool` - Checks if trend is strong
- `isBullish(): bool` - Checks if trend is bullish
- `isBearish(): bool` - Checks if trend is bearish
- `currentPrice(): mixed` - Gets current price

### 2. SmallUtBot Algorithm

The SmallUtBot algorithm uses UTBot alerts for signal generation.

#### Features
- **UTBot Alerts**: Uses UTBot indicator for signals
- **Sensitivity Control**: Configurable sensitivity levels
- **ATR Period**: Configurable ATR period for calculations
- **Signal Detection**: Detects buy/sell signals

#### Usage
```php
$utBot = new SmallUtBotAlgorithm($candleCollection);
$signal = $utBot->signal();

if ($signal === PositionTypeEnum::LONG) {
    // Buy signal detected
} elseif ($signal === PositionTypeEnum::SHORT) {
    // Sell signal detected
}
```

#### Key Methods
- `buySignal(?int $candleIndex = 0): bool` - Checks for buy signals
- `sellSignal(?int $candleIndex = 0): bool` - Checks for sell signals
- `isBullish(): bool` - Checks if last signal is bullish
- `isBearish(): bool` - Checks if last signal is bearish
- `lastSignalCandle(): Candle` - Gets the last signal candle
- `currentPrice(): mixed` - Gets current price

## Strategy Pipeline Logic

### Signal Generation Process

1. **Data Collection**: Candle data is collected for analysis
2. **Algorithm Execution**: Multiple algorithms are executed in sequence
3. **Signal Aggregation**: Signals from all algorithms are collected
4. **Consensus Check**: Trading signal is generated only when all algorithms agree
5. **Position Decision**: Long/Short position is determined based on consensus

### Pipeline Flow

```php
// 1. Initialize strategy
$strategy = new Strategy();

// 2. Send candle data
$strategy->send($candleCollection);

// 3. Define algorithm pipeline
$strategy->through([
    LNLTrendAlgorithm::class,
    SmallUtBotAlgorithm::class
]);

// 4. Execute pipeline
$strategy->run();

// 5. Check for signals
if ($strategy->hasLongEntry()) {
    return PositionTypeEnum::LONG;
}

if ($strategy->hasShortEntry()) {
    return PositionTypeEnum::SHORT;
}
```

### Consensus Mechanism

- **Long Entry**: All algorithms must generate LONG signals
- **Short Entry**: All algorithms must generate SHORT signals
- **No Entry**: If algorithms disagree, no position is opened

## Creating New Strategies

### Step 1: Create Strategy Class

```php
namespace App\Services\Strategy\Defaults;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Strategy\Strategy;

class MyCustomStrategy
{
    public function name(): string
    {
        return 'my-custom-strategy';
    }

    public function signal(CandleCollection $candleCollection): ?PositionTypeEnum
    {
        $strategy = new Strategy();
        $strategy->send($candleCollection)->through([
            // Add your algorithms here
            MyCustomAlgorithm::class,
            AnotherAlgorithm::class
        ])->run();

        if ($strategy->hasShortEntry()) {
            return PositionTypeEnum::SHORT;
        }

        if ($strategy->hasLongEntry()) {
            return PositionTypeEnum::LONG;
        }

        return null;
    }

    // Add your configuration methods
    public function active(): bool
    {
        return true; // Your logic here
    }
}
```

### Step 2: Create Algorithm Class

```php
namespace App\Services\Strategy;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;

class MyCustomAlgorithm extends AlgorithmAbstract
{
    public function signal(): ?PositionTypeEnum
    {
        // Your algorithm logic here
        if ($this->myCustomLogic()) {
            return PositionTypeEnum::LONG;
        }

        if ($this->myOtherLogic()) {
            return PositionTypeEnum::SHORT;
        }

        return null;
    }

    private function myCustomLogic(): bool
    {
        // Your custom logic here
        return true;
    }
}
```

### Step 3: Create Indicator Strategy (Optional)

```php
namespace App\Services\Indicator\Strategy;

use App\Services\Exchange\Repository\CandleCollection;

class MyCustomIndicatorStrategy implements StrategyContract
{
    public function __construct(private CandleCollection $candleCollection) {}

    public function isBullish(): bool
    {
        // Your bullish logic here
        return true;
    }

    public function isBearish(): bool
    {
        // Your bearish logic here
        return false;
    }

    public function sellSignal(?int $candleIndex = 0): bool
    {
        // Your sell signal logic here
        return false;
    }

    public function buySignal(?int $candleIndex = 0): bool
    {
        // Your buy signal logic here
        return true;
    }

    public function currentPrice(): mixed
    {
        return $this->candleCollection->get(0)->getClose();
    }

    public function collection(): CandleCollection
    {
        return $this->candleCollection;
    }
}
```

### Step 4: Create Settings Class

```php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MyCustomStrategySetting extends Settings
{
    public ?bool $active = false;
    public ?int $margin = null;
    public ?int $leverage = null;
    public ?string $timeframe = null;
    public array $coins = [];
    // Add your custom settings here

    public static function group(): string
    {
        return 'my-custom-strategy';
    }
}
```

### Step 5: Register Strategy

Add your strategy to the system by updating the appropriate configuration files and ensuring it's properly registered in the application.

## Best Practices

### 1. Algorithm Design
- Keep algorithms focused on single responsibilities
- Use clear, descriptive method names
- Implement proper error handling
- Test algorithms with historical data

### 2. Strategy Configuration
- Use settings classes for configuration
- Provide sensible defaults
- Validate configuration values
- Document all configuration options

### 3. Signal Generation
- Ensure algorithms are independent
- Use consensus-based signal generation
- Implement proper risk management
- Test with different market conditions

### 4. Performance Optimization
- Cache expensive calculations
- Use efficient data structures
- Minimize database queries
- Optimize algorithm execution order

## Testing Strategies

### 1. Backtesting
```php
// Test strategy with historical data
$historicalData = CandleCollection::fromHistoricalData($data);
$strategy = new MyCustomStrategy();
$signals = [];

foreach ($historicalData as $candle) {
    $signal = $strategy->signal($candle);
    $signals[] = $signal;
}
```

### 2. Paper Trading
```php
// Test strategy with live data without real trades
$strategy = new MyCustomStrategy();
$signal = $strategy->signal($liveCandleData);

if ($signal) {
    // Log signal for analysis
    Log::info('Strategy signal generated', ['signal' => $signal]);
}
```

### 3. Performance Metrics
- Win rate calculation
- Profit/loss analysis
- Drawdown measurement
- Risk-adjusted returns

## Troubleshooting

### Common Issues

1. **No Signals Generated**
   - Check if all algorithms are properly implemented
   - Verify candle data quality
   - Ensure algorithms are compatible

2. **Inconsistent Signals**
   - Review algorithm logic
   - Check for data inconsistencies
   - Verify consensus mechanism

3. **Performance Issues**
   - Optimize algorithm execution
   - Cache expensive calculations
   - Review data processing efficiency

### Debugging Tips

1. **Enable Logging**
   ```php
   Log::info('Strategy execution', [
       'algorithms' => $algorithms,
       'signals' => $signals,
       'consensus' => $consensus
   ]);
   ```

2. **Add Debug Methods**
   ```php
   public function debug(): array
   {
       return [
           'algorithms' => $this->algorithms,
           'signals' => $this->signals,
           'consensus' => $this->consensus
       ];
   }
   ```

3. **Test Individual Algorithms**
   ```php
   $algorithm = new MyCustomAlgorithm($candleCollection);
   $signal = $algorithm->signal();
   // Test individual algorithm behavior
   ```

This comprehensive strategy system provides a robust foundation for developing and managing trading strategies in the Quantum trading bot.
