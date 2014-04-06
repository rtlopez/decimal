<?php
namespace RtLopez;

abstract class Decimal
{
  private static $defaultImplementation = 'RtLopez\\DecimalBCMath';
  private static $defaultPrecision = 8;
  
  /**
   * @var internal value
   */
  protected $value;
  
  /**
   * @var int precision
   */
  protected $prec;

  public function __construct($value = 0, $prec = null)
  {
    if($value instanceof $this)
    {
      $this->prec = $prec !== null ? $prec : $value->prec;
      $this->value = $value->value;
    }
    else if($value instanceof Decimal)
    {
      $this->prec = $prec !== null ? $prec : $value->prec;
      $this->value = $this->_normalize($value);
    }
    else
    {
      $this->prec = $prec !== null ? $prec : self::$defaultPrecision;
      $this->value = $this->_normalize($value);
    }
    if($this->prec < 0) throw new \RuntimeException('precision can not be negative');
  }

  /**
   * Decimal factory method 
   * @param number $value
   * @param string $prec
   * @return \RtLopez\Decimal
   */
  public static function create($value = 0, $prec = null)
  {
    return new self::$defaultImplementation($value, $prec);
  }

  /**
   * Make the same type obkect
   * @param number $value
   * @param string $prec
   * @return \RtLopez\Decimal
   */
  public function same($value = 0, $prec = null)
  {
    if($value instanceof $this && $value->prec == $this->prec && ($prec === null || $value->prec == $prec))
    {
      return $value;
    }
    $class = get_class($this);
    return new $class($value, $prec);
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

  /**
   * convert object to string
   */
  public function __toString()
  {
    return $this->toString();
  }
  
  public function format($prec = null, $dec_point = '.' , $thousands_sep = ' ', $with_dec_zero = true)
  {
    $prec = $prec !== null ? $prec : $this->prec;
    $str = $this->round($prec)->toString();
    
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
  
  /**
   * remove trailing spaces
   * @param string $str
   * @return string
   */
  protected function _trim($str)
  {
    if(!strpos($str, '.')) return $str;
    return rtrim(rtrim($str, '0'), '.');
  }
  
  public function add($op)
  {
    $dst = clone $this;
    $dst->value = $this->_round($this->_add($this->same($op, $this->prec)));
    return $dst;
  }
  
  public function sub($op)
  {
    $dst = clone $this;
    $dst->value = $this->_round($this->_sub($this->same($op, $this->prec)));
    return $dst;
  }
  
  public function mul($op)
  {
    $dst = clone $this;
    $dst->value = $this->_round($this->_mul($this->same($op, $this->prec)));
    return $dst;
  }
  
  public function div($op)
  {
    $dst = clone $this;
    $dst->value = '0';
    if($dst->eq($op)) throw new ArithmeticException(sprintf('Division by zero (%s)', json_encode($op)));
    $dst->value = $this->_round($this->_div($this->same($op, $this->prec)));
    return $dst;
  }
  
  public function mod($op)
  {
    $dst = clone $this;
    $dst->value = '0';
    if($dst->eq($op)) throw new ArithmeticException(sprintf('Division by zero (%s)', json_encode($op)));
    $dst->value = $this->_round($this->_mod($this->same($op, $this->prec)));
    return $dst;
  }
  
  public function pow($op)
  {
    $dst = clone $this;
    $op = $this->same($op, $this->prec);
    //$dst->value = '0';
    //if($dst->gt($op)) throw new ArithmeticException('Exponent must be greather or equal zero: ' . json_encode($op));
    if((string)$op !== (string)$op->round(0)) throw new ArithmeticException('Exponent must be integer: ' . json_encode(array($op)));
    $dst->value = $this->_round($this->_pow($op));
    return $dst;
  }
  
  public function sqrt()
  {
    $dst = clone $this;
    $dst->value = $this->_round($this->_sqrt());
    return $dst;
  }
  
  public function round($prec = 0)
  {
    $dst = clone $this;
    $dst->value = $this->_round($this->value, $prec);
    return $dst;
  }

  public function ceil($prec = 0)
  {
    $dst = clone $this;
    $dst->value = $this->_round($this->_ceil($this->value, $prec));
    return $dst;
  }
  
  public function floor($prec = 0)
  {
    $dst = clone $this;
    $dst->value = $this->_round($this->_floor($this->value, $prec));
    return $dst;
  }

  public function eq($op)
  {
    return $this->_eq(self::same($op, $this->prec));
  }
  
  public function lt($op)
  {
    return $this->_lt(self::same($op, $this->prec));
  }
  
  public function gt($op)
  {
    return $this->_gt(self::same($op, $this->prec));
  }
  
  public function min($op)
  {
    $op = $this->same($op, $this->prec);
    return $this->lt($op) ? clone $this : $op;
  }
  
  public function max($op)
  {
    $op = $this->same($op, $this->prec);
    return $this->gt($op) ? clone $this : $op;
  }
  
  public function ne($op)
  {
    return !$this->eq($op);
  }
  
  public function ge($op)
  {
    return !$this->lt($op);
  } 

  public function le($op)
  {
    return !$this->gt($op);
  }
  
  public function epsilon()
  {
    $half = $this->same('0.5', $this->prec);
    $factor = $this->same('10', $this->prec)->pow($this->prec);
    return $half->div($factor);
  }

  public function abs()
  {
    return $this->gt(0) ? clone $this : $this->mul(-1);
  }

  abstract protected function _add(Decimal $op);
  
  abstract protected function _sub(Decimal $op);
  
  abstract protected function _mul(Decimal $op);
  
  abstract protected function _div(Decimal $op);
  
  abstract protected function _mod(Decimal $op);
  
  abstract protected function _pow(Decimal $op);
  
  abstract protected function _sqrt();
  

  abstract protected function _normalize($value);
  
  abstract protected function _round($value, $prec = null);
  
  abstract protected function _ceil($value, $prec);
  
  abstract protected function _floor($value, $prec);
  

  abstract protected function _eq(Decimal $op);
  
  abstract protected function _lt(Decimal $op);
  
  abstract protected function _gt(Decimal $op);
  
  abstract public function toString();
}
