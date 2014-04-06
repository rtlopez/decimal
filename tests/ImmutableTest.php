<?php
namespace RtLopez;

class ImmutableTest extends \PHPUnit_Framework_TestCase
{
  private static $_classes = array(
    'RtLopez\\DecimalBCMath',
    //'RtLopez\\DecimalFloat',
    //'RtLopez\\DecimalFixed',
  );
  
  public function providerImmutableCallsAndArgs()
  {
    $result = array();
    foreach(self::$_classes as $class)
    {
      $result[] = array($class, 'add',     array(5));
      $result[] = array($class, 'sub',     array(5));
      $result[] = array($class, 'mul',     array(5));
      $result[] = array($class, 'div',     array(5));
      $result[] = array($class, 'mod',     array(5));
      $result[] = array($class, 'pow',     array(1));
      $result[] = array($class, 'sqrt',    array());
      
      $result[] = array($class, 'round',   array(1));
      $result[] = array($class, 'ceil',    array(1));
      $result[] = array($class, 'floor',   array(1));

      $result[] = array($class, 'eq',      array(1));
      $result[] = array($class, 'ne',      array(1));
      $result[] = array($class, 'lt',      array(1));
      $result[] = array($class, 'gt',      array(1));
      $result[] = array($class, 'le',      array(1));
      $result[] = array($class, 'ge',      array(1));

      $result[] = array($class, 'min',     array(1));
      $result[] = array($class, 'min',     array(10));
      $result[] = array($class, 'max',     array(1));
      $result[] = array($class, 'max',     array(10));
      
      $result[] = array($class, 'epsilon', array());
      $result[] = array($class, 'abs',     array());
    }
    return $result;
  }
  
  /**
   * @dataProvider providerImmutableCallsAndArgs
   */
  public function testImmutable($class, $method, $args)
  {
    $orig = new $class(2, 2);
    $copy = clone $orig;
    
    $after = call_user_func_array(array($orig, $method), $args);
    
    $this->assertNotSame($orig, $after, 'Returned same object');
    $this->assertEquals($copy, $orig,   'Original object has been modified');
  }
}