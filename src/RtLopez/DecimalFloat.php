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
    $op = $this->_normalize($op);
    $result->value = round($this->value / $op, $this->prec);
    return $result;
  }

  public function mod($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = round($this->value % $op, $this->prec);
    return $result;
  }

  public function pow($op)
  {
    $result = clone $this;
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
    $epsilon = (float)(string)$this->epsilon();
    return abs($this->value - round($op, $this->prec)) < $epsilon;
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
  
  private function _normalize($op)
  {
    return $op instanceof self ? $op->value : round((string)$op, $this->prec);
  }
}
