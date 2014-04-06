<?php
namespace RtLopez;

class RoundTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    //'RtLopez\\DecimalFloat',
    //'RtLopez\\DecimalFixed',
  );
  
  public function providerRoundConstruct()
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
    }
    return $result;
  }
  
  /**
   * @dataProvider providerRoundConstruct
   */
  public function testRoundConstruct($class, $prec, $val, $exp)
  {
    $res = new $class($val, $prec);
    $this->assertEquals($exp, (string)$res);
  }
  
  public function providerRound()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,    0,      '0',   '0');
      $result[] = array($class,    0,    '2.1',   '2');
      $result[] = array($class,    0,    '3.4',   '3');
      $result[] = array($class,    0,    '4.5',   '5');
      $result[] = array($class,    0,    '5.6',   '6');
      $result[] = array($class,    1,   '0.01',   '0');
      $result[] = array($class,    1,  '0.344',   '0.3');
      $result[] = array($class,    1,  '0.349',   '0.3');
      $result[] = array($class,    1, '0.3499',   '0.3');
      $result[] = array($class,    1, '0.3500',   '0.4');
      $result[] = array($class,    1, '0.3501',   '0.4');
      $result[] = array($class,    2, '0.3449',   '0.34');
      $result[] = array($class,    2, '0.3450',   '0.35');
      $result[] = array($class,    2, '0.3451',   '0.35');
      $result[] = array($class,    0,   '-2.1',   '-2');
      $result[] = array($class,    0,   '-3.4',   '-3');
      $result[] = array($class,    0,   '-3.5',   '-4');
      $result[] = array($class,    0,   '-3.6',   '-4');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerRound
   */
  public function testRound($class, $prec, $val, $exp)
  {
    $num = new $class($val, 8);
    $res = $num->round($prec);
    $this->assertEquals($exp, (string)$res);
  }

  public function providerFloor()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,    0,      '0',   '0');
      $result[] = array($class,    0,    '2.1',   '2');
      $result[] = array($class,    0,    '3.4',   '3');
      $result[] = array($class,    0,    '4.5',   '4');
      $result[] = array($class,    0,    '5.6',   '5');
      $result[] = array($class,    0,    '5.9',   '5');
      $result[] = array($class,    0,   '5.99',   '5');
      $result[] = array($class,    1,   '0.01',   '0');
      $result[] = array($class,    1,   '0.39',   '0.3');
      $result[] = array($class,    1,  '0.399',   '0.3');
      $result[] = array($class,    1, '0.3999',   '0.3');
      $result[] = array($class,    2, '0.3999',   '0.39');
      $result[] = array($class,    2, '0.3001',   '0.3');
      $result[] = array($class,    2, '0.3450',   '0.34');
      $result[] = array($class,    2, '0.3451',   '0.34');
      $result[] = array($class,    0,   '-2.1',   '-3');
      $result[] = array($class,    0,   '-3.4',   '-4');
      $result[] = array($class,    0,   '-3.5',   '-4');
      $result[] = array($class,    0,   '-3.9',   '-4');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerFloor
   */
  public function testFloor($class, $prec, $val, $exp)
  {
    $num = new $class($val, 8);
    $res = $num->floor($prec);
    $this->assertEquals($exp, (string)$res);
  }

  public function providerCeil()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,    0,      '0',   '0');
      $result[] = array($class,    0,      '2',   '2');
      $result[] = array($class,    0,    '2.1',   '3');
      $result[] = array($class,    0,  '3.001',   '4');
      $result[] = array($class,    0,    '4.5',   '5');
      $result[] = array($class,    0,    '5.6',   '6');
      $result[] = array($class,    0,    '5.9',   '6');
      $result[] = array($class,    0,   '5.01',   '6');
      $result[] = array($class,    1,   '0.01',   '0.1');
      $result[] = array($class,    2, '0.3000',   '0.3');
      $result[] = array($class,    2, '0.3001',   '0.31');
      $result[] = array($class,    2, '0.3999',   '0.4');
      $result[] = array($class,    0,     '-2',   '-2');
      $result[] = array($class,    0,   '-2.1',   '-2');
      $result[] = array($class,    0,   '-3.4',   '-3');
      $result[] = array($class,    0,   '-3.5',   '-3');
      $result[] = array($class,    0,  '-3.99',   '-3');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerCeil
   */
  public function testCeil($class, $prec, $val, $exp)
  {
    $num = new $class($val, 8);
    $res = $num->ceil($prec);
    $this->assertEquals($exp, (string)$res);
  }
}
