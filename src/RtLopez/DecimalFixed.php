<?php
namespace RtLopez;

/**
 * Fixed-point implementation
 * @author rtlopez
 */
class DecimalFixed extends Decimal
{
  private $scale = 1;
  
  public function __construct($value = 0, $prec = null)
  {
    parent::__construct($value, $prec);
    $this->scale = pow(10, $this->prec);
    $this->value = $this->_normalize($value);
  }
  
  public function toString()
  {
    $sign = '';
    if($this->value >= 0)
    {
      $total = floor($this->value / $this->scale);
      $factor = $this->value - ($total * $this->scale);
    }
    else
    {
      $total = ceil($this->value / $this->scale);
      $factor = ($total * $this->scale) - $this->value;
      $sign = '-';
    }
    return $this->_trim(sprintf('%s%d.%0' . $this->prec . 'd', $sign, abs($total), $factor));
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
    return $this->value * $op->value / $this->scale;
  }

  protected function _div(Decimal $op)
  {
    return $this->value / $op->value * $this->scale;
  }

  protected function _mod(Decimal $op)
  {
    return $this->truncate()->value % $op->value;
  }

  protected function _pow(Decimal $op)
  {
    return pow($this->value / $this->scale, $op->value / $this->scale) * $this->scale;
  }

  protected function _sqrt()
  {
    return sqrt($this->value / $this->scale) * $this->scale;
  }
  
  protected function _round($number, $prec = null)
  {
    $prec = $prec !== null ? $prec : 0;
    return (int)round($number, $prec - $this->prec);
  }

  protected function _ceil($number, $prec = 0)
  {
    $scale = pow(10, $this->prec - $prec);
    return ceil($number / $scale) * $scale;
  }

  protected function _floor($number, $prec = 0)
  {
    $scale = pow(10, $this->prec - $prec);
    return floor($number / $scale) * $scale;
  }
  
  protected function _eq(Decimal $op)
  {
    return $this->value == $op->value;
  }
  
  protected function _lt(Decimal $op)
  {
    return $this->value < $op->value;
  }

  protected function _gt(Decimal $op)
  {
    return $this->value > $op->value;
  }

  protected function _fix($value)
  {
    return $this->_round($value, $this->prec);
  }
  
  protected function _normalize($op)
  {
    if($op instanceof $this)
    {
      $scale = pow(10, $this->prec - $op->prec);
      return $this->_fix($op->value * $scale);
    }
    else
    {
      return $this->_fix((string)$op * $this->scale);
    }
  }
}
