<?php

type::$integer->print = function ($integer) {
  echo $integer;
};

type::$integer->{'#operator +'} = function ($left, $right) {
  if(!is_numeric($right)) {
    return $left + parseFloat($right);
  }
  return $left + $right;
};
