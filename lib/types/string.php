<?php

type::$string->print = function ($string) {
  echo $string;
};

type::$string->html = function ($string) {
  return htmlspecialchars($string);
};

type::$string->{'#operator +'} = type::$string->{'#apply string'} = function ($left, $right) {
  if(is_object($right)) {
    return $left . get($right, 'to_json');
  }
  return $left . $right;
};
