<?php
namespace RtLopez;

/**
 * Floating-point implementation
 * @author rtlopez
 */
class DecimalFloat extends Decimal
{
  protected function init($value, $prec)
  {
    $this->value = $this->convert($value);
  }
    
  public function __toString()
  {
    return sprintf('%.' . $this->prec . 'f', $this->value);
  }

  public function add($op)
  {
    $op = $this->convert($op);
    $this->value = round($this->value + $op, $this->prec);
    return $this;
  }

  public function sub($op)
  {
    $op = $this->convert($op);
    $this->value = round($this->value - $op, $this->prec);
    return $this;
  }

  public function mul($op)
  {
    $op = $this->convert($op);
    $this->value = round($this->value * $op, $this->prec);
    return $this;
  }

  public function div($op)
  {
    $op = $this->convert($op);
    $this->value = round($this->value / $op, $this->prec);
    return $this;
  }

  public function mod($op)
  {
    $op = $this->convert($op);
    $this->value = round($this->value % $op, $this->prec);
    return $this;
  }

  public function pow($op)
  {
    $op = $this->convert($op);
    $this->value = pow($this->value, round($op, $this->prec));
    return $this;
  }

  public function sqrt()
  {
    $this->value = round(sqrt($this->value), $this->prec);
    return $this;
  }
  
  public function round($prec = 0)
  {
    $this->value = round($this->value, $prec);
    return $this;
  }

  public function ceil($prec = 0)
  {
    $scale = pow(10, -$prec);
    $this->value = ceil($this->value / $scale) * $scale;
    return $this;
  }

  public function floor($prec = 0)
  {
    $scale = pow(10, -$prec);
    $this->value = floor($this->value / $scale) * $scale;
    return $this;
  }
  
  public function eq($op)
  {
    $op = $this->convert($op);
    $epsilon = $this->epsilon();
    return abs($this->value - round($op, $this->prec)) < $epsilon;
  }
  
  public function lt($op)
  {
    $op = $this->convert($op);
    return $this->value < round($op, $this->prec);
  }

  public function gt($op)
  {
    $op = $this->convert($op);
    return $this->value > round($op, $this->prec);
  }
  
  private function convert($op)
  {
    return $op instanceof self ? $op->value() : round($op, $this->prec);
  }
}
