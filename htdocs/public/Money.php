<?php

namespace app;

class Money implements Expression
{
    public int $amount;
    protected string $currency;

    public function times(int $multiplier): Expression
    {
        return new Money($this->amount * $multiplier,$this->currency);
    }
    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }
    public function currency(): string
    {
        return $this->currency;
    }
    public function equals(object $object): bool
    {
        $money = $object;
        return $this->amount === $money->amount &&
            $this->currency()===$money->currency();
    }
    public static function dollar(int $amount): Money
    {
        return new Money($amount,'USD');
    }
    public static function franc(int $amount): Money
    {
        return new Money($amount,'CHF');
    }

    public function plus(Expression $money): Expression{
        return new Sum($this,$money);
    }
    public function reduce(Bank $bank,string $to): Money
    {
        $rate = $bank->rate($this->currency,$to);
        return new Money($this->amount / $rate,$to);
    }
}