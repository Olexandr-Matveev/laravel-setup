<?php
require_once __DIR__ . '/AccountInterface.php';

/**
 * Базовий банківський рахунок.
 * Реалізує поповнення, зняття коштів, отримання балансу та перевірку помилок.
 */
class BankAccount implements AccountInterface
{
    public const MIN_BALANCE = 0.0;

    protected float $balance;
    protected string $currency;

    /**
     * @param float $initialBalance Початковий баланс.
     * @param string $currency Валюта рахунку.
     * @throws Exception Якщо початковий баланс або валюта некоректні.
     */
    public function __construct(float $initialBalance = 0.0, string $currency = 'USD')
    {
        if ($initialBalance < self::MIN_BALANCE) {
            throw new Exception('Початковий баланс не може бути меншим за мінімальний баланс');
        }

        $currency = strtoupper(trim($currency));
        if ($currency === '') {
            throw new Exception('Валюта рахунку не може бути порожньою');
        }

        $this->balance = $initialBalance;
        $this->currency = $currency;
    }

    public function deposit(float $amount): void
    {
        $this->validatePositiveAmount($amount, 'Сума поповнення повинна бути більшою за 0');
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        $this->validatePositiveAmount($amount, 'Сума зняття повинна бути більшою за 0');

        if (($this->balance - $amount) < self::MIN_BALANCE) {
            throw new Exception('Недостатньо коштів');
        }

        $this->balance -= $amount;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getFormattedBalance(): string
    {
        return number_format($this->balance, 2, '.', ' ') . ' ' . $this->currency;
    }

    protected function validatePositiveAmount(float $amount, string $message): void
    {
        if ($amount <= 0) {
            throw new Exception($message);
        }
    }
}
