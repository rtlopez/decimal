<?php
namespace Tests\RtLopez;

class FailureTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    'RtLopez\\DecimalFloat',
    'RtLopez\\DecimalFixed',
  );

  public function providerClasses()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class);
    }
    return $result;
  }
  
  /**
   * @dataProvider providerClasses
   * @expectedException  RtLopez\ArithmeticException
   */
  public function testDivisionByZeroInteger($class)
  {
    $foo = new $class(1, 1);
    $foo->div(0); 
  }

  /**
   * @dataProvider providerClasses
   * @expectedException  RtLopez\ArithmeticException
   */
  public function testDivisionByZeroFloat($class)
  {
    $foo = new $class(1, 1);
    $foo->div(0.0); 
  }

  /**
   * @dataProvider providerClasses
   * @expectedException  RtLopez\ArithmeticException
   */
  public function testDivisionByZeroDecimal($class)
  {
    $foo = new $class(1, 1);
    $foo->div(new $class(0, 1)); 
  }

  /**
   * @dataProvider providerClasses
   * @expectedException  RuntimeException
   */
  public function testNegativePrecision($class)
  {
    new $class(0, -1);
  }

  /**
   * @dataProvider providerClasses
   * @expectedException RtLopez\ConversionException
   */
  public function testEmptyValue($class)
  {
    new $class('', 2);
  }

  /**
   * @dataProvider providerClasses
   * @expectedException RtLopez\ConversionException
   */
  public function testWrongValue($class)
  {
    new $class('notnumber', 2);
  }

  /**
   * @dataProvider providerClasses
   * @expectedException RtLopez\ConversionException
   */
  public function testNullValue($class)
  {
    new $class(null, 2);
  }
}
