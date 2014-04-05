<?php
namespace RtLopez;

class FailureTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    'RtLopez\\DecimalFloat',
    'RtLopez\\DecimalFixed',
  );

  public function providerDivisionByZero()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class);
    }
    return $result;
  }
  
  /**
   * @dataProvider providerDivisionByZero
   * @expectedException  RtLopez\ArithmeticException
   */
  public function testDivisionByZeroInteger($class)
  {
    $foo = new $class(1, 1);
    $foo->div(0); 
  }

  /**
   * @dataProvider providerDivisionByZero
   * @expectedException  RtLopez\ArithmeticException
   */
  public function testDivisionByZeroFloat($class)
  {
    $foo = new $class(1, 1);
    $foo->div(0.0); 
  }

  /**
   * @dataProvider providerDivisionByZero
   * @expectedException  RtLopez\ArithmeticException
   */
  public function testDivisionByZeroDecimal($class)
  {
    $foo = new $class(1, 1);
    $foo->div(new $class(0, 1)); 
  }
}