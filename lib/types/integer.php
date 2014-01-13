<?php

/**
 * Print Number
 */
type::$integer->print =
type::$float->print = function ($float) {
  echo $float;
};

/**
 * Mathematical Operators
 */

type::$integer->{'#operator +'} =
type::$float->{'#operator +'} = function ($left, $right) {
  if(!is_numeric($right)) {
    return $left + parseFloat($right);
  }
  return $left + $right;
};

type::$integer->{'#operator -'} =
type::$float->{'#operator -'} = function ($left, $right) {
  return $left - $right;
};

type::$integer->{'#operator *'} =
type::$float->{'#operator *'} = function ($left, $right) {
  return $left * $right;
};

type::$integer->{'#operator /'} =
type::$float->{'#operator /'} = function ($left, $right) {
  return $left / $right;
};

type::$integer->{'#operator ^'} =
type::$float->{'#operator ^'} = function ($left, $right) {
  return pow($left, $right);
};

/**
 * Range generation
 */
type::$integer->{'#operator ..'} =
type::$float->{'#operator ..'} = function ($left, $right) {
  $parent = null;
  $arr = a($parent);
  do {
    $arr->{'#value'}[] = $left;
  }
  while (++$left <= $right);
  return $arr; 
};

/**
 * Number String concatenation
 */
type::$integer->{'#apply string'} =
type::$float->{'#apply string'} = function ($left, $right) {
  return "$left$right";
};

/**
 * Comparators
 */
type::$integer->{'#operator <'} =
type::$float->{'#operator <'} = function ($left, $right) {
  return $left < $right;
};

type::$integer->{'#operator <='} =
type::$float->{'#operator <='} = function ($left, $right) {
  return $left <= $right;
};

type::$integer->{'#operator ='} =
type::$float->{'#operator ='} = function ($left, $right) {
  return $left == $right;
};

type::$integer->{'#operator >='} =
type::$float->{'#operator >='} = function ($left, $right) {
  return $left >= $right;
};

type::$integer->{'#operator >'} =
type::$float->{'#operator >'} = function ($left, $right) {
  return $left > $right;
};

/**
 * Numeric Properties
 */
type::$integer->{'ceil'} =
type::$float->{'ceil'} = function ($scope) {
  return ceil($scope);
};

type::$integer->{'floor'} =
type::$float->{'floor'} = function ($scope) {
  return floor($scope);
};

type::$integer->{'round'} =
type::$float->{'round'} = function ($scope) {
  return round($scope);
};
