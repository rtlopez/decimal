<?php
namespace RtLopez;

/**
 * BCMath implementation
 * @author rtlopez
 */
class DecimalBCMath extends Decimal
{
  public function __construct($value = 0, $prec = null)
  {
    parent::__construct($value, $prec);
    $this->value = $this->_normalize($value);
  }
  
  public function __toString()
  {
    return $this->_trim($this->value);
  }

  public function add($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = $this->_round(bcadd($this->value, $op, $this->prec + 1), $this->prec);
    return $result;
  }

  public function sub($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = $this->_round(bcsub($this->value, $op, $this->prec + 1), $this->prec);
    return $result;
  }

  public function mul($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = $this->_round(bcmul($this->value, $op, $this->prec + 1), $this->prec);
    return $result;
  }

  public function div($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    if($op == 0) throw new DivisionByZeroException();
    $result->value = $this->_round(bcdiv($this->value, $op, $this->prec + 1), $this->prec);
    return $result;
  }

  public function mod($op)
  {
    $result = clone $this;
    $op = $this->_normalize($op);
    $result->value = $this->_round(bcmod($this->value, $op), $this->prec);
    return $result;
  }

  public function pow($op)
  {
    $result = clone $this;
    $op = round($this->_normalize($op));
    $result->value = $this->_round(bcpow($this->value, $op, $this->prec + 1), $this->prec);
    return $result;
  }

  public function sqrt()
  {
    $result = clone $this;
    $result->value = $this->_round(bcsqrt($this->value, $this->prec + 1), $this->prec);
    return $result;
  }
  
  public function round($prec = 0)
  {
    $result = clone $this;
    $result->value = $this->_round($this->_round($this->value, $prec), $this->prec);
    return $result;
  }
  
  public function ceil($prec = 0)
  {
    $result = clone $this;
    $result->value = $this->_round($this->_ceil($this->value, $prec), $this->prec);
    return $result;
  }
  
  public function floor($prec = 0)
  {
    $result = clone $this;
    $result->value = $this->_round($this->_floor($this->value, $prec), $this->prec);
    return $result;
  }
  
  public function eq($op)
  {
    $op = $this->_normalize($op);
    return bccomp($this->value, $op, $this->prec + 1) == 0;
  }
  
  public function lt($op)
  {
    $op = $this->_normalize($op);
    return bccomp($this->value, $op, $this->prec + 1) < 0;
  }

  public function gt($op)
  {
    $op = $this->_normalize($op);
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
  
  private function _normalize($op)
  {
    return $op instanceof self ? $op->value : $this->_round((string)$op, $this->prec);
  }
}
