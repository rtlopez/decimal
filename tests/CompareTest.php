<?php
namespace Tests\RtLopez;

class CompareTest extends \PHPUnit\Framework\TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    'RtLopez\\DecimalFloat',
    'RtLopez\\DecimalFixed',
  );

  public function providerEquals()
  {
    $result = array();
    $precs = array(0, 1, 2, 4, 8);
    foreach(self::$_classes as $class)
    {
      foreach($precs as $prec)
      {
        $result[] = array($class, $prec,   0,   0,  true);
        $result[] = array($class, $prec,   1,   0,  false);
        $result[] = array($class, $prec,   1,   1,  true);
        $result[] = array($class, $prec,   0,   1,  false);
        $result[] = array($class, $prec,  -1,   1,  false);
        $result[] = array($class, $prec,   1,  -1,  false);
        $result[] = array($class, $prec,   2,   2,  true);
      }
    }
    return $result;
  }

  /**
   * @dataProvider providerEquals
   */
  public function testEquals($class, $prec, $lhs, $rhs, $exp)
  {
    $l = new $class($lhs, $prec);
    $r = new $class($rhs, $prec);
    $res1 = $l->eq($r);
    $res2 = $r->eq($l);
    $res3 = $l->eq($rhs);

    $res4 = $l->ne($r);
    $res5 = $r->ne($l);
    $res6 = $l->ne($rhs);
    
    $this->assertEquals($exp, $lhs == $rhs);
    $this->assertEquals($exp, $res1);
    $this->assertEquals($exp, $res2);
    $this->assertEquals($exp, $res3);

    $this->assertNotEquals($exp, $res4);
    $this->assertNotEquals($exp, $res5);
    $this->assertNotEquals($exp, $res6);
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
  public function testEqualPrecision($class)
  {
    $num1 = new $class('10.1', 1);
    $num2 = new $class('10.1', 3);
    
    $this->assertTrue($num2->eq($num1));
    $this->assertTrue($num2->eq('10.1'));
    $this->assertTrue($num1->eq($num2));
    
    $this->assertFalse($num1->eq('10.04'));
    $this->assertTrue($num1->eq('10.05'));
    $this->assertTrue($num1->eq('10.1'));
    $this->assertTrue($num1->eq('10.14'));
    $this->assertFalse($num1->eq('10.15'));
  }

  public function providerGreaterThan()
  {
    $result = array();
    $precs = array(0, 1, 2, 4, 8);
    foreach(self::$_classes as $class)
    {
      foreach($precs as $prec)
      {
        $result[] = array($class, $prec,   0,   0,  false);
        $result[] = array($class, $prec,   1,   0,  true);
        $result[] = array($class, $prec,   1,   1,  false);
        $result[] = array($class, $prec,   0,   1,  false);
        $result[] = array($class, $prec,  -1,   1,  false);
        $result[] = array($class, $prec,   1,  -1,  true);
        $result[] = array($class, $prec,   2,   2,  false);
        $result[] = array($class, $prec, 123,  34,  true);
        $result[] = array($class, $prec, -23, -34,  true);
      }
    }
    return $result;
  }
  
  /**
   * @dataProvider providerGreaterThan
   */
  public function testGreaterThan($class, $prec, $l, $r, $exp)
  {
    $lhs = new $class($l, $prec);
    $rhs = new $class($r, $prec);
    
    $this->assertEquals($exp, $lhs->gt($r));
    $this->assertEquals($exp, $lhs->gt($rhs));

    $this->assertNotEquals($exp, $lhs->le($r));
    $this->assertNotEquals($exp, $lhs->le($rhs));
  }
  
  public function providerLessThan()
  {
    $result = array();
    $precs = array(0, 1, 2, 4, 8);
    foreach(self::$_classes as $class)
    {
      foreach($precs as $prec)
      {
        $result[] = array($class, $prec,   0,   0,  false);
        $result[] = array($class, $prec,   1,   0,  false);
        $result[] = array($class, $prec,   1,   1,  false);
        $result[] = array($class, $prec,   0,   1,  true);
        $result[] = array($class, $prec,  -1,   1,  true);
        $result[] = array($class, $prec,   1,  -1,  false);
        $result[] = array($class, $prec,   2,   2,  false);
        $result[] = array($class, $prec, 123,  34,  false);
        $result[] = array($class, $prec, -43, -34,  true);
      }
    }
    return $result;
  }
  
  /**
   * @dataProvider providerLessThan
   */
  public function testLessThan($class, $prec, $l, $r, $exp)
  {
    $lhs = new $class($l, $prec);
    $rhs = new $class($r, $prec);
    
    $this->assertEquals($exp, $lhs->lt($r));
    $this->assertEquals($exp, $lhs->lt($rhs));

    $this->assertNotEquals($exp, $lhs->ge($r));
    $this->assertNotEquals($exp, $lhs->ge($rhs));
  }

  public function providerMinMax()
  {
    $result = array();
    $precs = array(0, 1, 2, 4, 8);
    foreach(self::$_classes as $class)
    {
      foreach($precs as $prec)
      {
        $result[] = array($class, $prec,   0,   0,  0,    0);
        $result[] = array($class, $prec,   1,   0,  0,    1);
        $result[] = array($class, $prec,   1,   1,  1,    1);
        $result[] = array($class, $prec,   0,   1,  0,    1);
        $result[] = array($class, $prec,  -1,   1,  -1,   1);
        $result[] = array($class, $prec,   1,  -1,  -1,   1);
        $result[] = array($class, $prec,   2,   2,   2,   2);
        $result[] = array($class, $prec, 123,  34,  34, 123);
        $result[] = array($class, $prec, -43, -34, -43, -34);
      }
    }
    return $result;
  }
  
  /**
   * @dataProvider providerMinMax
   */
  public function testMinMax($class, $prec, $l, $r, $exp_min, $exp_max)
  {
    $lhs = new $class($l, $prec);
    $rhs = new $class($r, $prec);
    
    $this->assertEquals($exp_min, ''.$lhs->min($r));
    $this->assertEquals($exp_min, ''.$lhs->min($rhs));

    $this->assertEquals($exp_max, ''.$lhs->max($r));
    $this->assertEquals($exp_max, ''.$lhs->max($rhs));
  }
}
