<?php
namespace RtLopez;

abstract class Decimal
{
  protected $value;
  protected $prec;

  public function __construct($value = 0, $prec = null)
  {
    $this->prec = $prec !== null ? $prec : 8;
    $this->init($value, $this->prec);
  }

  /**
   * Decimal factory method 
   * @param number $value
   * @param string $prec
   * @return Decimal
   */
  public static function get($value = 0, $prec = null)
  {
    return new DecimalBCMath((string)$value, $prec);
  }
  
  public function format($prec = null)
  {
    return '' . Decimal::get($this, $prec);
  }
  
  public function value()
  {
    return $this->value;
  }
  
  final public function toFloat()
  {
    return (float)(string)$this;
  }
  
  abstract protected function init($value, $prec);
  
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
    $op = self::get($op, $this->prec);
    return $this->lt($op) ? $this : $op;
  }
  
  public function max($op)
  {
    $op = self::get($op, $this->prec);
    return $this->gt($op) ? $this : $op;
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
    return 0.5 / pow(10, $this->prec);
  }
}
