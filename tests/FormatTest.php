<?php
namespace Tests\RtLopez;

class FormatTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    'RtLopez\\DecimalFloat',
    'RtLopez\\DecimalFixed',
  );
  
  public function providerToString()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,    1,     0.0,       '0.0');
      $result[] = array($class,    1,     0.1,       '0.1');
      $result[] = array($class,    1,     0.01,      '0.0');
      $result[] = array($class,    1,     1.04,      '1.0');
      $result[] = array($class,    1,     0.05,      '0.1');
      $result[] = array($class,    1,     0.06,      '0.1');
      $result[] = array($class,    1,     0.09,      '0.1');
      $result[] = array($class,    1,    -1.0,      '-1.0');
      $result[] = array($class,    2,   '-2.001',   '-2.00');
      $result[] = array($class,    2,   '-2.001',   '-2.00');
      $result[] = array($class,    2,   '-2.004',   '-2.00');
      $result[] = array($class,    2,   '-2.005',   '-2.01');
      $result[] = array($class,    2,   '-2.006',   '-2.01');
      $result[] = array($class,    2,   '-2.009',   '-2.01');
      $result[] = array($class,    3,   '-2.0999',  '-2.100');
      $result[] = array($class,    3,   '-2.00049', '-2.000');
      $result[] = array($class,    3,   '-2.00049', '-2.000');
      $result[] = array($class,    3,   '12.049',   '12.049');
      $result[] = array($class,    3,  '123.049',  '123.049');
      $result[] = array($class,    6,      '1234.00049',                 '1 234.000490');
      $result[] = array($class,    9,     '12345.1234567',           '12 345.123456700');
      $result[] = array($class,    9,    '123456.123456789',        '123 456.123456789');
      $result[] = array($class,    9,    '1234567.123456789',     '1 234 567.123456789');
      //$result[] = array($class,    8,   '12345678.123456788',    '12 345 678.12345679');
      $result[] = array($class,    8,   '92345678.923456798',    '92 345 678.92345680');
      //$result[] = array($class,    8,  '123456789.123456789',   '123 456 789.12345679');  // float fail
      //$result[] = array($class,    8, '1234567890.123456789', '1 234 567 890.12345679');  // float and fixed fail
      $result[] = array($class,    8, '1.23456789e+9', '1 234 567 890.00000000');  // scientific notation
    }
    return $result;
  }
  
  /**
   * @dataProvider providerToString
   */
  public function testToString($class, $prec, $val, $exp)
  {
    $res = new $class($val, $prec);
    $this->assertSame($exp, $res->format());
  }
  
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
   */
  public function testFormatNoParams($class)
  {
    $res = new $class('1234.5678', 4);
    $this->assertSame('1 234.5678', $res->format());
  }

  /**
   * @dataProvider providerClasses
   */
  public function testFormatPrec($class)
  {
    $res = new $class('1234.5678', 4);
    $this->assertSame('1 234.57', $res->format(2));
  }

  /**
   * @dataProvider providerClasses
   */
  public function testFormatDecPoint($class)
  {
    $res = new $class('1234.5678', 4);
    $this->assertSame('1 234,5678', $res->format(null, ','));
  }
  
  /**
   * @dataProvider providerClasses
   */
  public function testFormatEmptyThousands($class)
  {
    $res = new $class('1234.5678', 4);
    $this->assertSame('1234.5678', $res->format(null, '.', ''));
  }

  /**
   * @dataProvider providerClasses
   */
  public function testFormatCommaThousands($class)
  {
    $res = new $class('1234.5678', 4);
    $this->assertSame('1,234.5678', $res->format(null, '.', ','));
  }
  
  /**
   * @dataProvider providerClasses
   */
  public function testFormatWithoutCommaThousands($class)
  {
    $res = new $class('234.5678', 4);
    $this->assertSame('234.5678', $res->format(null, '.', ','));
  }

  /**
   * @dataProvider providerClasses
   */
  public function testFormatDecimalAndThousands($class)
  {
    $res = new $class('1234.5678', 4);
    $this->assertSame('1--234--568', $res->format(3, '--', '--'));
  }
  
  /**
   * @dataProvider providerClasses
   */
  public function testFormatTrailingZero($class)
  {
    $res = new $class('1234.5', 4);
    $this->assertSame('1 234.500', $res->format(3));
  }
  
  /**
   * @dataProvider providerClasses
   */
  public function testFormatNoTrailingZero($class)
  {
    $res = new $class('1234', 4);
    $this->assertSame('1,234', $res->format(null, '.', ',', false));
  }

  /**
   * @dataProvider providerClasses
   */
  public function testFormatFractionOnly($class)
  {
    $res = new $class('0.5678', 4);
    $this->assertSame('0.5678', $res->format());
  }

  /**
   * @dataProvider providerClasses
   */
  public function testFormatNoNegativeZero($class)
  {
    $res = new $class('-0.0001', 4);
    $this->assertSame('0.00', $res->format(2));
  }
}
