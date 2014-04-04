<?php
namespace RtLopez;

class MathTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    'RtLopez\\DecimalFloat',
    'RtLopez\\DecimalFixed',
  );
  
  public function providerAddIntegers()
  {
    $result = array();
    $precs = array(0, 1, 2, 4, 8);
    foreach(self::$_classes as $class)
    {
      foreach($precs as $prec)
      {
        $result[] = array($class, $prec,   0,   0,  '0');
        $result[] = array($class, $prec,   1,   0,  '1');
        $result[] = array($class, $prec,   1,   1,  '2');
        $result[] = array($class, $prec,   0,   1,  '1');
        $result[] = array($class, $prec,  -1,   1,  '0');
        $result[] = array($class, $prec,   1,  -1,  '0');
        $result[] = array($class, $prec,   2,   2,  '4');
      }
    }
    return $result;
  }
  
  /**
   * @dataProvider providerAddIntegers
   */
  public function testAddIntegers($class, $prec, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, $prec);
    $r = new $class($rhs, $prec);
    $res1 = $l->add($r);
    $res2 = $r->add($l);
    $res3 = $l->add($rhs);

    $this->assertEquals($exp, (string)($lhs + $rhs));
    $this->assertEquals($exp, ''.$res1);
    $this->assertEquals($exp, ''.$res2);
    $this->assertEquals($exp, ''.$res3);
  }

  public function providerAddFloats()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,   0.0,   0.0,  '0');
      $result[] = array($class,   0.1,   0.0,  '0.1');
      $result[] = array($class,   0.1,   1.0,  '1.1');
      $result[] = array($class,   0.0,   0.1,  '0.1');
      $result[] = array($class,  -1.0,   1.0,  '0');
      $result[] = array($class,   1.0,  -1.0,  '0');
      $result[] = array($class,   2.0,   2.0,  '4');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerAddFloats
   */
  public function testAddFloats($class, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, 2);
    $r = new $class($rhs, 2);
    $res1 = $l->add($r);
    $res2 = $r->add($l);
    $res3 = $l->add($rhs);

    $this->assertEquals($exp, (string)($lhs + $rhs));
    $this->assertEquals($exp, ''.$res1);
    $this->assertEquals($exp, ''.$res2);
    $this->assertEquals($exp, ''.$res3);
  }
}
