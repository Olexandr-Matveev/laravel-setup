<?php
/**
 * Інтерфейс банківського рахунку.
 * Визначає обов'язкові операції для будь-якого типу рахунку.
 */
interface AccountInterface
{
    /**
     * Поповнення рахунку.
     *
     * @param float $amount Сума поповнення.
     * @throws Exception Якщо сума некоректна.
     */
    public function deposit(float $amount): void;

    /**
     * Зняття коштів з рахунку.
     *
     * @param float $amount Сума зняття.
     * @throws Exception Якщо сума некоректна або коштів недостатньо.
     */
    public function withdraw(float $amount): void;

    /**
     * Отримання поточного балансу.
     *
     * @return float Поточний баланс рахунку.
     */
    public function getBalance(): float;
}
