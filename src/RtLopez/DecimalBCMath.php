<?php
namespace RtLopez;

/**
 * Bcmath implementation
 * @author rtlopez
 */
class DecimalBCMath extends Decimal
{
  protected function init($value, $prec)
  {
    $this->value = $this->convert($value);
  }

  public function __toString()
  {
    return (string)$this->value;
  }

  public function add($op)
  {
    $op = $this->convert($op);
    $this->value = bcadd($this->value, $op, $this->prec + 1);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }

  public function sub($op)
  {
    $op = $this->convert($op);
    $this->value = bcsub($this->value, $op, $this->prec + 1);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }

  public function mul($op)
  {
    $op = $this->convert($op);
    $this->value = bcmul($this->value, $op, $this->prec + 1);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }

  public function div($op)
  {
    $op = $this->convert($op);
    if($op == 0)
    {
      $e = new \Exception();
      echo $e->getTraceAsString() . "\n";
    }
    $this->value = bcdiv($this->value, $op, $this->prec + 1);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }

  public function mod($op)
  {
    $op = $this->convert($op);
    $this->value = bcmod($this->value, $op);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }

  public function pow($op)
  {
    $op = $this->convert($op);
    $this->value = bcpow($this->value, $op, $this->prec + 1);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }

  public function sqrt()
  {
    $this->value = bcsqrt($this->value, $this->prec + 1);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }
  
  public function round($prec = 0)
  {
    $this->value = $this->_round($this->value, $prec);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }
  
  public function ceil($prec = 0)
  {
    $this->value = $this->_ceil($this->value, $prec);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }
  
  public function floor($prec = 0)
  {
    $this->value = $this->_floor($this->value, $prec);
    $this->value = $this->_round($this->value, $this->prec);
    return $this;
  }
  
  public function eq($op)
  {
    $op = $this->convert($op);
    return bccomp($this->value, $op, $this->prec + 1) == 0;
  }
  
  public function lt($op)
  {
    $op = $this->convert($op);
    return bccomp($this->value, $op, $this->prec + 1) < 0;
  }

  public function gt($op)
  {
    $op = $this->convert($op);
    return bccomp($this->value, $op, $this->prec + 1) > 0;
  }
  
  private function _round($number, $prec)
  {
    $positive = bccomp($number, '0', $this->prec + 1) >= 0;
    $fix = '0.' . str_repeat('0', $prec) . '5';
    $number = $positive ? bcadd($number, $fix, $prec + 1) : bcsub($number, $fix, $prec + 1);
    return bcadd($number, '0', $prec);
  }

  private function _ceil($number, $prec)
  {
    if(bccomp($number, '0', $this->prec + 1) >= 0)
    {
      $tmp = bcadd($number, '0', $prec);
      if(bccomp($number, $tmp, $this->prec) == 0) return $tmp;
      $fix = bcdiv('1', pow(10, $prec), $prec);
      return bcadd($number, $fix, $prec);
    }
    else
    {
      return bcadd($number, '0', $prec);
    }
  }
  
  private function _floor($number, $prec)
  {
    if(bccomp($number, '0', $this->prec + 1) >= 0)
    {
      return bcsub($number, '0', $prec);
    }
    else
    {
      $tmp = bcsub($number, '0', $prec);
      if(bccomp($number, $tmp, $this->prec) == 0) return $tmp;
      $fix = bcdiv('1', pow(10, $prec), $prec);
      return bcsub($number, $fix, $prec);
    }
  }
  
  private function convert($op)
  {
    return $op instanceof DecimalBCMath ? $op->value : $this->_round((string)$op, $this->prec);
  }
}
