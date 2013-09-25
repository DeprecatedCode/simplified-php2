<?php

type::$string->print = function ($string) {
  echo $string;
};

type::$string->{'#operator +'} = function ($left, $right) {
  return $left . $right;
};
