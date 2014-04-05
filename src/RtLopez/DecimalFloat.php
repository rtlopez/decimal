<?php
namespace RtLopez;

/**
 * Floating-point implementation
 * @author rtlopez
 */
class DecimalFloat extends Decimal
{
  public function __construct($value = 0, $prec = null)
  {
    parent::__construct($value, $prec);
    $this->value = $this->_normalize($value);
  }
    
  public function __toString()
  {
    return $this->_trim(sprintf('%.' . $this->prec . 'f', $this->value));
  }

  public function add($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = round($this->value + $op, $this->prec);
    return $result;
  }

  public function sub($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = round($this->value - $op, $this->prec);
    return $result;
  }

  public function mul($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = round($this->value * $op, $this->prec);
    return $result;
  }

  public function div($op)
  {
    $result = clone $this;
    $result->value = 0.0;
    if($result->eq($op)) throw new ArithmeticException('Division by zero');
    $op = $this->_normalize($op);
    $result->value = round($this->value / $op, $this->prec);
    return $result;
  }

  public function mod($op)
  {
    $result = clone $this;
    $result->value = 0.0;
    if($result->eq($op)) throw new ArithmeticException('Division by zero');
    $op = $this->_normalize($op);
    $result->value = round($this->value % $op, $this->prec);
    return $result;
  }

  public function pow($op)
  {
    $result = clone $this;
    $result->value = 0.0;
    //if($result->gt($op)) throw new ArithmeticException('Exponent must be greather or equal zero: ' . json_encode($op));
    $op = $this->_normalize($op);
    $result->value = pow($this->value, round($op, $this->prec));
    return $result;
  }

  public function sqrt()
  {
    $result = clone $this;
    $result->value = round(sqrt($this->value), $this->prec);
    return $result;
  }
  
  public function round($prec = 0)
  {
    $result = clone $this;
    $result->value = round($this->value, $prec);
    return $result;
  }

  public function ceil($prec = 0)
  {
    $result = clone $this;
    $scale = pow(10, -$prec);
    $result->value = ceil($this->value / $scale) * $scale;
    return $result;
  }

  public function floor($prec = 0)
  {
    $result = clone $this;
    $scale = pow(10, -$prec);
    $result->value = floor($this->value / $scale) * $scale;
    return $result;
  }
  
  public function eq($op)
  {
    $op = $this->_normalize($op);
    return abs($this->value - round($op, $this->prec)) < $this->_epsilon();
  }
  
  public function lt($op)
  {
    $op = $this->_normalize($op);
    return $this->value < round($op, $this->prec);
  }

  public function gt($op)
  {
    $op = $this->_normalize($op);
    return $this->value > round($op, $this->prec);
  }
  
  private function _epsilon()
  {
    return 0.5 / pow(10, $this->prec);
  }
  
  private function _normalize($op)
  {
    return $op instanceof self ? $op->value : round((string)$op, $this->prec);
  }
}
