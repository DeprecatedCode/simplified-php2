<?php

type::$float->print = function ($float) {
  echo $float;
};

type::$float->{'#operator +'} = function ($left, $right) {
  if(!is_numeric($right)) {
    return $left + parseFloat($right);
  }
  return $left + $right;
};
