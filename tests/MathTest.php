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

  public function providerSub()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,   0.0,   0.0,  '0');
      $result[] = array($class,   0.1,   0.0,  '0.1');
      $result[] = array($class,   0.1,   1.0,  '-0.9');
      $result[] = array($class,   0.0,   0.1,  '-0.1');
      $result[] = array($class,  -1.0,   1.0,  '-2');
      $result[] = array($class,   1.0,  -1.0,  '2');
      $result[] = array($class,   2.0,   2.0,  '0');
      $result[] = array($class, 123.0,  45.5,  '77.5');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerSub
   */
  public function testSub($class, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, 3);
    $r = new $class($rhs, 3);
    $res1 = $l->sub($r);
    $res2 = $l->sub($rhs);

    $this->assertEquals($exp, (string)($lhs - $rhs));
    $this->assertEquals($exp, ''.$res1);
    $this->assertEquals($exp, ''.$res2);
  }

  public function providerMul()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,   0.0,   0.0,  '0');
      $result[] = array($class,   0.1,   0.0,  '0');
      $result[] = array($class,   0.1,   1.0,  '0.1');
      $result[] = array($class,   0.0,   0.1,  '0');
      $result[] = array($class,  -1.0,   1.0,  '-1');
      $result[] = array($class,   1.0,    -1,  '-1');
      $result[] = array($class,   2.0,  -2.0,  '-4');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerMul
   */
  public function testMul($class, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, 4);
    $r = new $class($rhs, 4);
    $res1 = $l->mul($r);
    $res2 = $r->mul($l);
    $res3 = $l->mul($rhs);
  
    $this->assertEquals($exp, (string)($lhs * $rhs));
    $this->assertEquals($exp, ''.$res1);
    $this->assertEquals($exp, ''.$res2);
    $this->assertEquals($exp, ''.$res3);
  }
  
  public function providerDiv()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,   0.1,     1,  '0.1');
      $result[] = array($class,   0.0,   0.1,  '0');
      $result[] = array($class,  -1.0,     4,  '-0.25');
      $result[] = array($class,   1.0,  -1.0,  '-1');
      $result[] = array($class,   2.0,   2.0,  '1');
      $result[] = array($class,    99,     3,  '33');
      $result[] = array($class,   100,     3,  '33.33');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerDiv
   */
  public function testDiv($class, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, 2);
    $r = new $class($rhs, 2);
    $res1 = $l->div($r);
    $res2 = $l->div($rhs);
  
    $this->assertEquals($exp, sprintf('%.2f', $lhs / $rhs));
    $this->assertEquals($exp, ''.$res1);
    $this->assertEquals($exp, ''.$res2);
  }

  public function providerMod()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,     5,     2,   '1');
      $result[] = array($class,     5,     5,   '0');
      $result[] = array($class,    -7,     3,  '-1');
      $result[] = array($class,    -7,    -2,  '-1');
      $result[] = array($class,     9,    -4,   '1');
      $result[] = array($class, '23.45',   4,   '3');
      $result[] = array($class, '-23.75',  4,  '-3');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerMod
   */
  public function testMod($class, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, 2);
    $r = new $class($rhs, 2);
    $res1 = $l->mod($r);
    $res2 = $l->mod($rhs);
  
    $this->assertEquals($exp, (string)($lhs % $rhs));
    $this->assertEquals($exp, ''.$res1);
    $this->assertEquals($exp, ''.$res2);
  }

  public function providerPow()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class,     0,     0,    '1');
      $result[] = array($class,     2,     0,    '1');
      $result[] = array($class,     3,     1,    '3');
      $result[] = array($class,     5,     2,   '25');
      $result[] = array($class,    -5,     3, '-125');
      $result[] = array($class,    -7,     2,   '49');
      $result[] = array($class, '3.3',     3, '35.937');
      //$result[] = array($class,     6,    -1,   '0.1667');
      //$result[] = array($class,     9,    -2,   '0.0123');
    }
    return $result;
  }
  
  /**
   * @dataProvider providerPow
   */
  public function testPow($class, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, 4);
    $r = new $class($rhs, 4);
    $res1 = $l->pow($r);
    $res2 = $l->pow($rhs);
  
    $this->assertEquals($exp, sprintf('%.4f',(pow($lhs, $rhs))));
    $this->assertEquals($exp, ''.$res1);
    $this->assertEquals($exp, ''.$res2);
  }
}
