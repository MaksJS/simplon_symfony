<?php

namespace Tests\AppBundle\Util;

use AppBundle\Util\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    protected $calculator;
    protected $positiveValue1 = 30;
    protected $positiveValue2 = 12;
    protected $negativeValue1 = -30;
    protected $negativeValue2 = -12;
    protected $floatValue1 = 30.2;
    protected $floatValue2 = 12.5;

    protected function setUp()
    {
        $this->calculator = new Calculator();
    }

    protected function tearDown() {
        $this->calculator = null;
    }

    public function testAddWithPositiveValues()
    {
        $result = $this->calculator->add($this->positiveValue1, $this->positiveValue2);
        $this->assertEquals(42, $result);
    }
    
    public function testAddWithNegativeValues()
    {
        $result = $this->calculator->add($this->negativeValue1, $this->negativeValue2);
        $this->assertEquals(-42, $result);
    }

    public function testAddWithFloatValues()
    {
        $result = $this->calculator->add($this->floatValue1, $this->floatValue2);
        $this->assertEquals(42.7, $result);
    }

    public function testAddWithIllegalArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $result = $this->calculator->add($this->positiveValue1, "foo");
    }

    public function testDivideWithPositiveValues()
    {
        $result = $this->calculator->divide($this->positiveValue1, $this->positiveValue2);
        $this->assertEquals(2.5, $result);
    }
    
    public function testDivideWithNegativeValues()
    {
        $result = $this->calculator->divide($this->negativeValue1, $this->negativeValue2);
        $this->assertEquals(2.5, $result);
    }

    public function testDivideWithFloatValues()
    {
        $result = $this->calculator->divide($this->floatValue1, $this->floatValue2);
        $this->assertEquals(2.416, $result);
    }

    public function testDivideWithIllegalArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $result = $this->calculator->divide($this->positiveValue1, "foo");
    }

    public function testDivideByZero()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cannot divide by 0");
        $result = $this->calculator->divide($this->positiveValue1, 0);
    }
}