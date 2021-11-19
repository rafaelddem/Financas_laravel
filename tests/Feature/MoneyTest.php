<?php

namespace Tests\Feature;

use App\Tasks\Financial\Money;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    public function test_NullValues()
    {
        $money = new Money();
        $this->assertEquals(0.0, $money->getValue());
    }

    public function test_EmptyValues()
    {
        $money = new Money("");
        $this->assertEquals(0.0, $money->getValue());
    
        $money = new Money(0);
        $this->assertEquals(0.0, $money->getValue());
    
        $money = new Money("0");
        $this->assertEquals(0.0, $money->getValue());
    
        $money = new Money(-0);
        $this->assertEquals(0.0, $money->getValue());
    
        $money = new Money("-0");
        $this->assertEquals(0.0, $money->getValue());
    }

    public function test_InvalidValues()
    {
        $money = new Money("AAA");
        $this->assertEquals(0.0, $money->getValue());
    
        $money = new Money("true");
        $this->assertEquals(0.0, $money->getValue());

        // $money = new Money(true);
        // $this->expectException(Exception::class);
    }

    public function test_CreateByIntValue()
    {
        $money = new Money(1);
        $this->assertEquals(1.0, $money->getValue());
    
        $money = new Money(-1);
        $this->assertEquals(-1.0, $money->getValue());
    
        $money = new Money(12);
        $this->assertEquals(12.0, $money->getValue());
    }

    public function test_CreateByFloatValue()
    {
        $money = new Money(1.0);
        $this->assertEquals(1.0, $money->getValue());
    
        $money = new Money(-1.0);
        $this->assertEquals(-1.0, $money->getValue());
    
        $money = new Money(12.0);
        $this->assertEquals(12.0, $money->getValue());
    
        $money = new Money(12345.0);
        $this->assertEquals(12345.0, $money->getValue());
    
        $money = new Money(12.3);
        $this->assertEquals(12.3, $money->getValue());
    
        $money = new Money(12.34);
        $this->assertEquals(12.34, $money->getValue());
    
        $money = new Money(12.345);
        $this->assertEquals(12.35, $money->getValue());
    
        $money = new Money(12345.6789);
        $this->assertEquals(12345.68, $money->getValue());
    }

    public function test_CreateByStringValue()
    {
        $money = new Money("1");
        $this->assertEquals(0.01, $money->getValue());

        $money = new Money("-1");
        $this->assertEquals(-0.01, $money->getValue());

        $money = new Money("12");
        $this->assertEquals(0.12, $money->getValue());

        $money = new Money("-12");
        $this->assertEquals(-0.12, $money->getValue());

        $money = new Money("12345");
        $this->assertEquals(123.45, $money->getValue());

        $money = new Money("-12345");
        $this->assertEquals(-123.45, $money->getValue());

        $money = new Money("12.0");
        $this->assertEquals(1.20, $money->getValue());

        $money = new Money("12,0");
        $this->assertEquals(1.20, $money->getValue());

        $money = new Money("-12.0");
        $this->assertEquals(-1.20, $money->getValue());

        $money = new Money("-12,0");
        $this->assertEquals(-1.20, $money->getValue());

        $money = new Money("12.3");
        $this->assertEquals(1.23, $money->getValue());

        $money = new Money("12,3");
        $this->assertEquals(1.23, $money->getValue());

        $money = new Money("-12.3");
        $this->assertEquals(-1.23, $money->getValue());

        $money = new Money("-12,3");
        $this->assertEquals(-1.23, $money->getValue());

        $money = new Money("12.345");
        $this->assertEquals(123.45, $money->getValue());

        $money = new Money("12,345");
        $this->assertEquals(123.45, $money->getValue());

        $money = new Money("-12.345");
        $this->assertEquals(-123.45, $money->getValue());

        $money = new Money("-12,345");
        $this->assertEquals(-123.45, $money->getValue());
    }

    public function test_returnMoneyFormat()
    {
        $money = new Money(1);
        $this->assertEquals("R$ 1,00", $money->formatMoney());

        $money = new Money(1.0);
        $this->assertEquals("R$ 1,00", $money->formatMoney());

        $money = new Money(1.25);
        $this->assertEquals("R$ 1,25", $money->formatMoney());

        $money = new Money(1234.56);
        $this->assertEquals("R$ 1.234,56", $money->formatMoney());

        $money = new Money(-1234.56);
        $this->assertEquals("-R$ 1.234,56", $money->formatMoney());
    }
}
