<?php
namespace RtLopez;

/**
 * Fixed-point implementation
 * @author rtlopez
 */
class DecimalFixed extends Decimal
{
  private $scale;
  
  protected function init($value, $prec)
  {
    $this->scale = pow(10, $prec);
    $this->value = (int)$this->convert($value);
  }
  
  public function __toString()
  {
    if($this->value > 0)
    {
      $total = (int)floor($this->value / $this->scale);
      $factor = $this->value - $total * $this->scale;
    }
    else
    {
      $total = (int)ceil($this->value / $this->scale);
      $factor = $total * $this->scale - $this->value;
    }
    $sign = $this->value < 0 ? '-' : '';
    return sprintf('%s%d.%0' . $this->prec . 'd', $sign, abs($total), $factor);
  }

  public function add($op)
  {
    $op = $this->convert($op);
    $this->value += $op;
    return $this;
  }

  public function sub($op)
  {
    $op = $this->convert($op);
    $this->value -= $op;
    return $this;
  }

  public function mul($op)
  {
    $op = $this->convert($op);
    $this->value = (int)round($this->value * $op / $this->scale);
    return $this;
  }

  public function div($op)
  {
    $op = $this->convert($op);
    if($op === 0) throw new InvalidArgumentException('Division by zero!'); 
    $this->value = (int)round($this->value * $this->scale / $op);
    return $this;
  }

  public function mod($op)
  {
    $op = $this->convert($op);
    $op = $op > 0 ? floor($op / $this->scale) : ceil($op / $this->scale); 
    $op *= $this->scale;
    $this->value = $this->value % $op;
    return $this;
  }

  public function pow($op)
  {
    $op = $this->convert($op);
    $this->value = (int)round(pow($this->value, round($op / $this->scale)));
    return $this;
  }

  public function sqrt()
  {
    $this->value = (int)round(sqrt($this->value / $this->scale), $this->prec) * $this->scale;
    return $this;
  }
  
  public function round($prec = 0)
  {
    $this->value = (int)round($this->value, $prec - $this->prec);
    return $this;
  }

  public function ceil($prec = 0)
  {
    $scale = pow(10, $this->prec - $prec);
    $this->value = ceil($this->value / $scale) * $scale;
    return $this;
  }

  public function floor($prec = 0)
  {
    $scale = pow(10, $this->prec - $prec);
    $this->value = floor($this->value / $scale) * $scale;
    return $this;
  }
  
  public function eq($op)
  {
    $op = $this->convert($op);
    return $this->value == $op;
  }
  
  public function lt($op)
  {
    $op = $this->convert($op);
    return $this->value < $op;
  }

  public function gt($op)
  {
    $op = $this->convert($op);
    return $this->value > $op;
  }
  
  private function convert($op)
  {
    if($op instanceof DecimalFixed)
    {
      $val = Decimal::get($op->value, 0)->mul($this->scale)->div($op->scale);
      return $val->value;
    }
    else if($op instanceof Decimal)
    {
      return (int)round((string)$op * $this->scale);
    }
    else
    {
      return (int)round($op * $this->scale);
    }
  }
}
