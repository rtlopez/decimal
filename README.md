Decimal
=======

An object oriented arbitrary precision arithmetic class for PHP (>5.3.3).

Installation
------------

You can use [Composer](http://getcomposer.org/)

To add this package as a local, per-project dependency to your project, simply add a dependency on `rtlopez/decimal` to your project's `composer.json` file. Here is a minimal example of a `composer.json` file that just defines a dependency on `Decimal`:

```json
{
  "require": {
    "rtlopez/decimal": "dev-master"
  }
}
```

Features
--------

The `Decimal` class can be used for an arbitrary precision calculation. Main features are:

* Immutable result of each operation,
* The same result precision,
* Safe for finance calculations,
* Fluent interface,
* Many unit test (more than 1100 tests and 3700 assertions),
* Easy to use and install,

Why should I use it?
--------------------

The `Decimal` solve several inconveniences known in other solutions. 

* `Float` is only an aproximation and have limited precision,
* `Fixed-point` have limited size (64-bits on 64-bit system),
* Pure `bcmath` cannot correctly round, only truncate,
* `GMP` is not object-oriented

Usage
-----

### Creating objects with simple factory

```php
use RtLopez\Decimal;

$n1 = Decimal::create(123, 2);
$n2 = Decimal::create(123.45, 2); // not recommended
$n3 = Decimal::create('123.45', 2);
$n4 = Decimal::create($n1, 2);
```
### Printing and formatting numbers

```php
$number = Decimal::create('12345.671', 2);

echo (string)$number;
// 12345.67

echo $number->format(1, ',', ' ');
// 12 345,7

echo $number->truncate()->format(null, ',', ' ', false);
// 12 345,00
```
### Arithmetics

```php
$n1 = Decimal::create('12345.671', 3);
$n2 = Decimal::create('11111.111', 3);
$n3 = $n1->add($n2)->mul(-1);
echo $n3; // -23456.782

// fluid interface
$n4 = $n3->pow(2)->sqrt()->mul(-2)->div(2)->abs();
```

### Immutable

```php
$n1 = Decimal::create('12345.671', 3);
$n1->add(2);
...
echo $n1;
// 12345.671
```

### Comparisions

```php
$n1 = Decimal::create('12345.671', 3);
$n2 = Decimal::create('11111.111', 3);
$n1->gt($n2);        // true
$n1->eq(0);          // false
$n2->le(100);        // false
```

### Rounding

```php
$n = Decimal::create('12345.671', 3);
echo $n->round(2);   // 12345.67
echo $n->round(1);   // 12345.7
echo $n->truncate(); // 12345
echo $n->ceil(1);    // 12345.6
echo $n->floor();    // 12346
```

### Implementations

There are exists three internal implementations of this library.

* `RtLopez\DecimalBCMath`: (default)based on bcmath library
* `RtLopez\DecimalFixed`:  based on scaled integers
* `RtLopez\DecimalFloat`:  based on floats

Only `BCMath` implementation works correctly in wide spectrum of numbers. All others can fail in many border cases. They was implemented only as proof of concept and you can using it only for your own risk.

The default implementation or precision can be changed by functions:

```php
Decimal::setDefaultImplementation('RtLopez\DecimalBCMath');
Decimal::setDefaultPrecision(6);
```

Licence
-------

This library is distributed under MIT Licence.

Issue reporting or feature request
----------------------------------

Coming soon.

TODO
----

* GMP implementation

Donation
--------
BTC: 1Lopez7yPtbyjfLGe892JfheDFJMMt43tW
LTC: LV3G3sJxz9AYpDMYUp8e1LCmerFYxVY3ak
