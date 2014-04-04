<?php
namespace RtLopez;

class FormateTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    //'RtLopez\\DecimalFloat',
    //'RtLopez\\DecimalFixed',
  );
  
  public function providerToString()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,    1,     0.0,   '0');
      $result[] = array($class,    1,     0.1,   '0.1');
      $result[] = array($class,    1,     0.01,  '0');
      $result[] = array($class,    1,     1.04,  '1');
      $result[] = array($class,    1,     0.05,  '0.1');
      $result[] = array($class,    1,     0.06,  '0.1');
      $result[] = array($class,    1,     0.09,  '0.1');
      $result[] = array($class,    1,    -1.0,   '-1');
      $result[] = array($class,    2, '-2.001',  '-2');
      $result[] = array($class,    2, '-2.001',  '-2');
      $result[] = array($class,    2, '-2.004',  '-2');
      $result[] = array($class,    2, '-2.005',  '-2.01');
      $result[] = array($class,    2, '-2.006',  '-2.01');
      $result[] = array($class,    2, '-2.009',  '-2.01');
      $result[] = array($class,    3, '-2.0999', '-2.1');
      $result[] = array($class,    3, '-2.00049', '-2.00');
      $result[] = array($class,    3, '-2.00049', '-2.00');
      $result[] = array($class,    3, '12.049', '12.049');
      $result[] = array($class,    3, '123.049', '123.049');
      $result[] = array($class,    6, '1234.00049', '1 234.000490');
      $result[] = array($class,    9, '12345.1234567', '12 345.123456700');
      $result[] = array($class,    9, '123456.123456789', '123 456.123456789');
      $result[] = array($class,    9, '1234567.123456789', '1 234 567.123456789');
      $result[] = array($class,    9, '12345678.123456789', '12 345 678.123456789');
      $result[] = array($class,    9, '123456789.123456789', '123 456 789.123456789');
      $result[] = array($class,    9, '1234567890.123456789', '1 234 567 890.123456789');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerToString
   */
  public function testToString($class, $prec, $val, $exp)
  {
    $res = new $class($val, $prec);
    $this->assertEquals($exp, $res->format());
  }
}