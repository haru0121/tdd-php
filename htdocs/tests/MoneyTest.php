<?php

namespace Tests;

use app\Bank;
use app\Expression;
use app\Money;
use app\Sum;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase{

    public function test_乗法()
    {
        $five = Money::dollar(5);
        $this->assertEquals(Money::dollar(10), $five->times(2));
        $this->assertEquals(Money::dollar(15),$five->times(3));
    }
    public function test_等価性()
    {
        $this->assertTrue((Money::dollar(5))->equals((Money::dollar(5))));
        $this->assertFalse((Money::dollar(5))->equals((Money::dollar(6))));
    }

    public function test_フランとドルが等価ではない()
    {
        $this->assertFalse((Money::dollar(5))->equals((Money::franc(5))));
    }
    public function test_通貨の単位()
    {
        $this->assertEquals('USD',Money::dollar(1)->currency());
        $this->assertEquals('CHF',Money::franc(1)->currency());
    }
    public function test_足し算の結果にSumクラスインスタンスが返るか()
    {
        $five = Money::dollar(5);
        $result = $five->plus($five);
        $sum = $result;
        $this->assertEquals($five,$sum->augend);
        $this->assertEquals($five,$sum->addend);
    }
    public function test_シンプルな足し算()
    {
        $five = Money::dollar(5);
        $sum = $five->plus($five);
        $bank = new Bank();
        $reduced = $bank->reduce($sum,"USD");
        $this->assertEquals(Money::dollar(10), $reduced);
    }
    public function test_Moneyインスタンスを簡略化()
    {
        $bank = new Bank();
        $result = $bank->reduce(Money::dollar(1),"USD");
        $this->assertEquals(Money::dollar(1),$result);
    }
    public function test_2フランを１ドルに変換する()
    {
        $bank = new Bank();
        $bank->addRate("CHF","USD",2);
        $result = $bank->reduce(Money::franc(2),"USD");
        $this->assertEquals(Money::dollar(1),$result);
    }
    public function test_同じ通貨のレートは1()
    {
        $this->assertEquals(1,(new Bank())->rate("USD","USD"));
    }
    public function test_通貨単位の違う足し算()
    {
        /** @var $fiveDollar Expression **/
        $fiveDollar = Money::dollar(5);
        /** @var $tenFranc Expression **/
        $tenFranc = Money::franc(10);
        $bank = new Bank();
        $bank->addRate("CHF","USD",2);
        $result = $bank->reduce($fiveDollar->plus($tenFranc),"USD");
        $this->assertEquals(Money::dollar(10),$result);
    }
    public function test_Sumインスタンスでの足し算()
    {
        /** @var $fiveDollar Expression **/
        $fiveDollar = Money::dollar(5);
        /** @var $tenFranc Expression **/
        $tenFranc = Money::franc(10);
        $bank = new Bank();
        $bank->addRate("CHF","USD",2);
        $sum = (new Sum($fiveDollar, $tenFranc))->plus($fiveDollar);
        $result = $bank->reduce($sum,"USD");
        $this->assertEquals(Money::dollar(15),$result);
    }
    public function test_Sumインスタンスでの掛け算()
    {
        $fiveDollar = Money::dollar(5);
        $tenFranc = Money::franc(10);
        $bank = new Bank();
        $bank->addRate("CHF","USD",2);
        $sum = (new Sum($fiveDollar, $tenFranc))->times(2);
        $result = $bank->reduce($sum,"USD");
        $this->assertEquals(Money::dollar(20),$result);
    }
}