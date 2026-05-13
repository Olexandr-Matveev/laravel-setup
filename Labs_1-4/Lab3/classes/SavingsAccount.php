<?php
require_once __DIR__ . '/BankAccount.php';

/**
 * Накопичувальний рахунок.
 * Розширює BankAccount і додає застосування відсоткової ставки.
 */
class SavingsAccount extends BankAccount
{
    public static float $interestRate = 0.05; // 5%

    /**
     * Додає до балансу відсоток від поточного балансу.
     *
     * @throws Exception Якщо відсоткова ставка некоректна.
     */
    public function applyInterest(): void
    {
        if (self::$interestRate < 0) {
            throw new Exception('Відсоткова ставка не може бути від’ємною');
        }

        $interestAmount = $this->balance * self::$interestRate;
        $this->balance += $interestAmount;
    }

    public static function getInterestRatePercent(): string
    {
        return number_format(self::$interestRate * 100, 2, '.', ' ') . '%';
    }
}
