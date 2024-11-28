<?php

namespace Tests;

use app\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function test_合計金額()
    {
        $calculator = new Calculator();
        $this->assertSame(12, $calculator->add(10, 2));
    }

    public function test最初の金額から次の金額を引いた金額()
    {
        $calculator = new Calculator();
        $this->assertSame(8, $calculator->sub(10, 2));
    }
}