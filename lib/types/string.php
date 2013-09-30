<?php

type::$string->print = function ($string) {
  echo $string;
};

type::$string->html = function ($string) {
  return htmlspecialchars($string);
};

type::$string->{'#operator +'} =
type::$string->{'#apply array'} =
type::$string->{'#apply object'} =
type::$string->{'#apply string'} =
type::$string->{'#apply integer'} =
type::$string->{'#apply float'} = function ($left, $right) {
  if(is_object($right)) {
    return $left . get($right, 'to_json');
  }
  return $left . $right;
};

type::$string->upper = function ($string) {
  return strtoupper($string);
};

type::$string->lower = function ($string) {
  return strtolower($string);
};
