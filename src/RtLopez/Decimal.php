<?php
namespace RtLopez;

abstract class Decimal
{
  private static $defaultImplementation = 'RtLopez\\DecimalBCMath';
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
    if($this->prec < 0) throw new \RuntimeException('precision can not be negative');
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
  
  public function format($prec = null, $dec_point = '.' , $thousands_sep = ' ', $with_dec_zero = true)
  {
    $prec = $prec !== null ? $prec : $this->prec;
    $str = (string)$this->round($prec);
    
    // extract parts
    $parts = explode('.', $str);
    $ints = $parts[0];
    $decs = ''. @$parts[1];
    
    // extract sign
    $sign = '';
    if($ints[0] == '-')
    {
      $ints = substr($ints, 1);
      $sign = '-';
    }
    
    // format integer part
    $int_len = strlen($ints);
    $int_seps = (int)floor($int_len / 3);
    $int_total = $int_len + $int_seps;
    $int_str = str_repeat($thousands_sep, $int_total);
    for($i = $int_len - 1, $j = $int_total - 1; $i >= 0; $j--, $i--)
    {
      $c = $ints[$i];
      $int_str[$j] = $c;
      if(($int_len - $i) % 3 == 0) $j--;
    }
    
    // format decimal part
    $dec_len = strlen($decs);
    $dec_str = str_repeat('0', $prec);
    for($i = 0; $i < $dec_len; $i++)
    {
      $dec_str[$i] = $decs[$i];
    }
    
    // connect all parts
    $number = $sign . trim($int_str . $dec_point . $dec_str);
    if($with_dec_zero) return $number;
    
    return $this->_trim($number);
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
    $class = get_class($this);
    $op = new $class($op, $this->prec);
    return $this->lt($op) ? clone $this : $op;
  }
  
  public function max($op)
  {
    $class = get_class($this);
    $op = new $class($op, $this->prec);
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
    $class = get_class($this);
    $half = new $class('0.5', $this->prec);
    $factor = (new $class('10', $this->prec))->pow($this->prec);
    return $half->div($factor);
  }

  public function abs()
  {
    $class = get_class($this);
    $zero = new $class('0', $this->prec);
    return $this->gt($zero) ? clone $this : $zero->sub($this);
  }
  
  protected function _trim($str)
  {
    if(!strpos($str, '.')) return $str;
    return rtrim(rtrim($str, '0'), '.');
  }
}
