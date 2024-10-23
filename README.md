<p align="center"><a href="https://laravel.com" target="_blank"><img src="./public/favicon.svg" width="400" alt="Quantum Logo"></a></p>

# <p align='center'>Qauntum</p>


## About Quantum

Quantum is a trading bot built with the Laravel framework. 
Its main component consists of indicators developed in PHP (although these indicators are available on TradingView, we had to convert them to PHP for use in Laravel). Additionally, Quantum features various trading strategies,
which have been developed with a win rate of 70%.

## Features

### Admin Panel ( Filament )
- **assign strategies to users**
- **assign strategies to coins**
- **assign balance assets in USDT for each coin strategy**

### Strategies

- **(Static Reward Strategy) trading strategy with at least on percent profit per day**
- **(Dynamic Reward Strategy) trading strategy with dynamic stop-loss (detect trend and try to take profit from whole trend)**


## Exchanges

- [x] Bingx
- [ ] Coinex
- [ ] Mexc
