<?php
namespace RtLopez;

/**
 * Floating-point implementation
 * @author rtlopez
 */
class DecimalFloat extends Decimal
{ 
  public function toString()
  {
    return $this->_trim(sprintf('%.' . $this->prec . 'f', $this->value));
  }

  protected function _add(Decimal $op)
  {
    return $this->value + $op->value;
  }

  protected function _sub(Decimal $op)
  {
   return $this->value - $op->value;
  }

  protected function _mul(Decimal $op)
  {
    return $this->value * $op->value;
  }

  protected function _div(Decimal $op)
  {
    return $this->value / $op->value;
  }

  protected function _mod(Decimal $op)
  {
    return $this->value % $op->value;
  }

  protected function _pow(Decimal $op)
  {
    return pow($this->value, $op->value);
  }

  protected function _sqrt()
  {
    return sqrt($this->value);
  }
  
  protected function _round($number, $prec = 0)
  {
    return round($number, $prec);
  }

  protected function _ceil($number, $prec = 0)
  {
    $scale = pow(10, $prec);
    return ceil($number * $scale) / $scale;
  }

  protected function _floor($number, $prec = 0)
  {
    $scale = pow(10, $prec);
    return floor($number * $scale) / $scale;
  }
  
  protected function _eq(Decimal $op)
  {
    return abs($this->value - $op->value) < $this->_epsilon();
  }
  
  protected function _lt(Decimal $op)
  {
    return $this->value < $op->value;
  }

  protected function _gt(Decimal $op)
  {
    return $this->value > $op->value;
  }
  
  protected function _epsilon()
  {
    return 0.5 / pow(10, $this->prec);
  }

  protected function _fix($value)
  {
    return $this->_round($value, $this->prec);
  }
  
  protected function _normalize($op)
  {
    return $this->_fix($op instanceof $this ? $op->value : (string)$op);
  }
}
