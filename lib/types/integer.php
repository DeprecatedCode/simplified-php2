<?php

type::$integer->print =
type::$float->print = function ($float) {
  echo $float;
};

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

type::$integer->{'#apply string'} =
type::$float->{'#apply string'} = function ($left, $right) {
  return "$left$right";
};
