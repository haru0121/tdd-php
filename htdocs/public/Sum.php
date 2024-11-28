<?php

namespace app;

class Sum implements Expression
{
    public Expression $augend;
    public Expression $addend;
    public function __construct(Expression $augend, Expression $addend)
    {
        $this->augend = $augend;
        $this->addend = $addend;
    }
    public function reduce(Bank $bank,string $to): Money
    {
        $reducedAugend = $this->augend->reduce($bank,$to)->amount;
        $reducedAddend = $this->addend->reduce($bank,$to)->amount;
        $amount = $reducedAugend + $reducedAddend;
        return new Money($amount,$to);
    }
    public function plus(Expression $addend): Expression
    {
        return new Sum($this,$addend);
    }
    public function times(int $multiplier): Expression
    {
        return new Sum($this->augend->times($multiplier),$this->addend->times($multiplier));

    }
}