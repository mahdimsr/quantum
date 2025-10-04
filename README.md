<p align="center"><a href="https://laravel.com" target="_blank"><img src="./public/favicon.svg" width="400" alt="Quantum Logo"></a></p>

# <p align='center'>Qauntum</p>

<div align="center">

**Version:** 2.2.0 | **Current Exchange:** Bitunix

</div>

## About Quantum

Quantum is a trading bot built with the Laravel framework. 
Its main component consists of indicators developed in PHP (although these indicators are available on TradingView, we had to convert them to PHP for use in Laravel). Additionally, Quantum features various trading strategies,
which have been developed with a win rate of 70%.

## Features

- ### Admin Panel ( Filament )

- ### Strategies
  - **Orbital Strategy** (It check if trend start and then enter position)
  - **Harmony Strategy** (It set TP for position and add the realized pnl to margin)


## Exchanges

- [x] Coinex
- [x] BingX
- [x] Bitunix

## Exchange Architecture Documentation

For detailed information about the exchange services, contract requests, and adapter responses, see: [Exchange Architecture Documentation](docs/EXCHANGE-ARCHITECTURE.md)

## Trading Strategies Documentation

For comprehensive information about trading strategies, algorithm development, and strategy implementation, see: [Trading Strategies Documentation](docs/TRADING-STRATEGIES.md)
