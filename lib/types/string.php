<?php

type::$string->print = function ($string) {
  echo $string;
};

type::$string->{'#operator +'} = function ($left, $right) {
  if(is_object($right)) {
    return $left . get($right, 'to_json');
  }
  return $left . $right;
};
