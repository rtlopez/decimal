<?php
namespace RtLopez;

class MathTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    'RtLopez\\DecimalFloat',
    'RtLopez\\DecimalFixed',
  );
  
  private static $_precs = array(
    0, 1, 2, 4, 8
  );
  
  public function providerAddIntegers()
  {
    $result = array();
    
    foreach(self::$_classes as $class)
    {
      foreach(self::$_precs as $prec)
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
  public function testAddIntegers($class, $prec, $lhs, $rhs, $exp1)
  {
    $exp2 = $lhs + $rhs;
    $l = new $class($lhs, $prec);
    $r = new $class($rhs, $prec);
    $res1 = $l->add($r);
    $res2 = $r->add($l);
    
    $this->assertEquals($exp1, ''.$res1);
    $this->assertEquals($exp1, ''.$res2);
    $this->assertEquals($exp2, ''.$res1);
    $this->assertEquals($exp2, ''.$res2);
  }
}
