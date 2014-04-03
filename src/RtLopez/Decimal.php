<?php
namespace RtLopez;

abstract class Decimal
{
  private static $defaultImplementation = 'RtLopez\\DeciamlBCMath';
  private static $defaultPrecision = 8;
  
  protected $value;
  protected $prec;

  public function __construct($value = 0, $prec = null)
  {
    if($value instanceof $this)
    {
      $this->prec = $prec !== null ? $prec : $value->prec;
      $this->value = $value->value;
    }
    else if($value instanceof self)
    {
      $this->prec = $prec !== null ? $prec : $value->prec;
      $this->value = (string)$value;
    }
    else
    {
      $this->prec = $prec !== null ? $prec : self::$defaultPrecision;
      $this->value = (string)$value;
    }
  }

  /**
   * Decimal factory method 
   * @param number $value
   * @param string $prec
   * @return Decimal
   */
  public static function make($value = 0, $prec = null)
  {
    return new self::$defaultImplementation($value, $prec);
  }
  
  public static function setDefaultImplementation($className)
  {
    self::$defaultImplementation = $className;
  }

  public static function getDefaultImplementation()
  {
    return self::$defaultImplementation;
  }

  public static function setDefaultPrecision($precision)
  {
    self::$defaultPrecision = $precision;
  }
  
  public static function getDefaultPrecision()
  {
    return self::$defaultPrecision;
  }

  protected function normalize($value)
  {
    return $value;
  }
  
  public function format($prec = null)
  {
    return '' . Decimal::make($this, $prec);
  }
  
  abstract public function __toString();
  
  abstract public function add($op);
  
  abstract public function sub($op);
  
  abstract public function mul($op);
  
  abstract public function div($op);
  
  abstract public function mod($op);
  
  abstract public function pow($op);
  
  abstract public function sqrt();
  
  abstract public function round($prec = 0);

  abstract public function ceil($prec = 0);
  
  abstract public function floor($prec = 0);
  
  abstract public function eq($op);
  
  abstract public function lt($op);
  
  abstract public function gt($op);

  public function min($op)
  {
    $op = self::make($op, $this->prec);
    return $me->lt($op) ? clone $this : $op;
  }
  
  public function max($op)
  {
    $op = self::make($op, $this->prec);
    return $this->gt($op) ? clone $this : $op;
  }
  
  final public function ne($op)
  {
    return !$this->eq($op);
  }
  
  final public function ge($op)
  {
    return !$this->lt($op);
  } 

  final public function le($op)
  {
    return !$this->gt($op);
  }
  
  public function epsilon()
  {
    $half = self::make('0.5', $this->prec);
    $factor = self::make('10', $this->prec)->pow($this->prec);
    return $half->div($factor);
    //return 0.5 / pow(10, $this->prec);
  }
  
  protected function _trim($str)
  {
    if(!strpos($str, '.')) return $str;
    return rtrim(rtrim($str, '0'), '.');
  }
}
