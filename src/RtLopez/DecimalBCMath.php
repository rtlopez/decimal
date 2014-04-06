<?php
namespace RtLopez;

/**
 * BCMath implementation
 * @author rtlopez
 */
class DecimalBCMath extends Decimal
{
  public function toString()
  {
    return $this->_trim($this->value);
  }

  protected function _add(Decimal $op)
  {
    return bcadd($this->value, $op->value, $this->prec + 1);
  }

  protected function _sub(Decimal $op)
  {
    return bcsub($this->value, $op->value, $this->prec + 1);
  }

  protected function _mul(Decimal $op)
  {
    return bcmul($this->value, $op->value, $this->prec + 1);
  }

  protected function _div(Decimal $op)
  {
    return bcdiv($this->value, $op->value, $this->prec + 1);
  }

  protected function _mod(Decimal $op)
  {
    return bcmod($this->value, $op->value);
  }

  protected function _pow(Decimal $op)
  {
    return bcpow($this->value, $op->value, $this->prec + 1);
  }

  protected function _sqrt()
  {
    return bcsqrt($this->value, $this->prec + 1);
  }
  
  protected function _eq(Decimal $op)
  {
    return bccomp($this->value, $op->value, $this->prec + 1) == 0;
  }
  
  protected function _lt(Decimal $op)
  {
    return bccomp($this->value, $op->value, $this->prec + 1) < 0;
  }

  protected function _gt(Decimal $op)
  {
    return bccomp($this->value, $op->value, $this->prec + 1) > 0;
  }
  
  protected function _round($number, $prec = null)
  {
    //TODO: negative precision
    $prec = $prec !== null ? $prec : $this->prec;
    $positive = bccomp($number, '0', $this->prec + 1) >= 0;
    $fix = '0.' . str_repeat('0', $prec) . '5';
    $number = $positive ? bcadd($number, $fix, $prec + 1) : bcsub($number, $fix, $prec + 1);
    return bcadd($number, '0', $prec);
  }

  protected function _ceil($number, $prec)
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
  
  protected function _floor($number, $prec)
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
  
  protected function _fix($value)
  {
    return $this->_round($value, $this->prec);
  }
  
  protected function _normalize($op)
  {
    return $this->_fix($op instanceof $this ? $op->value : (string)$op);
  }
}
