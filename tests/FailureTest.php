<?php

namespace Tests\RtLopez;

use RtLopez\ArithmeticException;
use RtLopez\ConversionException;

class FailureTest extends \PHPUnit\Framework\TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    'RtLopez\\DecimalFloat',
    'RtLopez\\DecimalFixed',
  );

  public function providerClasses()
  {
    $result = array();
    foreach (self::$_classes as $class) {
      $result[] = array($class);
    }
    return $result;
  }

  /**
   * @dataProvider providerClasses
   */
  public function testDivisionByZeroInteger($class)
  {
    $this->expectException(ArithmeticException::class);
    $foo = new $class(1, 1);
    $foo->div(0);
  }

  /**
   * @dataProvider providerClasses
   */
  public function testDivisionByZeroFloat($class)
  {
    $this->expectException(ArithmeticException::class);
    $foo = new $class(1, 1);
    $foo->div(0.0);
  }

  /**
   * @dataProvider providerClasses
   */
  public function testDivisionByZeroDecimal($class)
  {
    $this->expectException(ArithmeticException::class);
    $foo = new $class(1, 1);
    $foo->div(new $class(0, 1));
  }

  /**
   * @dataProvider providerClasses
   */
  public function testNegativePrecision($class)
  {
    $this->expectException(\RuntimeException::class);
    new $class(0, -1);
  }

  /**
   * @dataProvider providerClasses
   */
  public function testEmptyValue($class)
  {
    $this->expectException(ConversionException::class);
    new $class('', 2);
  }

  /**
   * @dataProvider providerClasses
   */
  public function testWrongValue($class)
  {
    $this->expectException(ConversionException::class);
    new $class('notnumber', 2);
  }

  /**
   * @dataProvider providerClasses
   */
  public function testNullValue($class)
  {
    $this->expectException(ConversionException::class);
    new $class(null, 2);
  }
}
