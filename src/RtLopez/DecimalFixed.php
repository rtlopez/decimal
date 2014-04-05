<?php
namespace RtLopez;

/**
 * Fixed-point implementation
 * @author rtlopez
 */
class DecimalFixed extends Decimal
{
  private $scale;
  
  public function __construct($value = 0, $prec = null)
  {
    parent::__construct($value, $prec);
    $this->scale = pow(10, $this->prec);
    $this->value = $this->_normalize($value);
  }
  
  public function __toString()
  {
    $sign = '';
    if($this->value >= 0)
    {
      $total = (int)floor($this->value / $this->scale);
      $factor = $this->value - $total * $this->scale;
    }
    else
    {
      $total = (int)ceil($this->value / $this->scale);
      $factor = $total * $this->scale - $this->value;
      $sign = '-';
    }
    return $this->_trim(sprintf('%s%d.%0' . $this->prec . 'd', $sign, abs($total), $factor));
  }

  public function add($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value += $op;
    return $result;
  }

  public function sub($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value -= $op;
    return $result;
  }

  public function mul($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = (int)round($this->value * $op / $this->scale);
    return $result;
  }

  public function div($op)
  {
    $result = clone $this;
    $result->value = 0;
    if($result->eq($op)) throw new ArithmeticException('Division by zero');
    $op = $this->_normalize($op);
    $result->value = (int)round($this->value * $this->scale / $op);
    return $result;
  }

  public function mod($op)
  {
    $result = clone $this;
    $result->value = 0;
    if($result->eq($op)) throw new ArithmeticException('Division by zero');
    $op = $this->_normalize($op);
    $op = $op > 0 ? floor($op / $this->scale) : ceil($op / $this->scale); 
    $result->value = $this->value % ($op * $this->scale);
    return $result->value > 0 ? $result->floor(0) : $result->ceil(0);
  }

  public function pow($op)
  {
    $result = clone $this;
    $result->value = 0;
    //if($result->gt($op)) throw new ArithmeticException('Exponent must be greather or equal zero: ' . json_encode($op));
    $op = $this->_normalize($op);
    $result->value = (int)round(pow($this->value / $this->scale, round($op / $this->scale, $this->prec)), $this->prec) * $this->scale;
    return $result;
  }

  public function sqrt()
  {
    $result = clone $this;
    $result->value = (int)round(sqrt($this->value / $this->scale), $this->prec) * $this->scale;
    return $result;
  }
  
  public function round($prec = 0)
  {
    $result = clone $this;
    $result->value = (int)round($this->value, $prec - $this->prec);
    return $result;
  }

  public function ceil($prec = 0)
  {
    $result = clone $this;
    $scale = pow(10, $this->prec - $prec);
    $result->value = ceil($this->value / $scale) * $scale;
    return $result;
  }

  public function floor($prec = 0)
  {
    $result = clone $this;
    $scale = pow(10, $this->prec - $prec);
    $result->value = floor($this->value / $scale) * $scale;
    return $result;
  }
  
  public function eq($op)
  {
    $op = $this->_normalize($op);
    return $this->value == $op;
  }
  
  public function lt($op)
  {
    $op = $this->_normalize($op);
    return $this->value < $op;
  }

  public function gt($op)
  {
    $op = $this->_normalize($op);
    return $this->value > $op;
  }
  
  private function _normalize($op)
  {
    if($op instanceof self)
    {
      //return Decimal::make($op->value, 0)->mul($this->scale)->div($op->scale)->value;
      return (int)round($op->value * $this->scale / $op->scale);
    }
    else
    {
      return (int)round((string)$op * $this->scale);
    }
  }
}
