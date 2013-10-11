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
  if (is_object($right)) {
    $right = run($right);
  }
  if (is_object($right)) {
    return $left . get($right, 'to_json');
  }
  return $left . $right;
};

type::$string->{'#operator ='} = function ($left, $right) {
  return $left === $right;
};

type::$string->{'#operator ~'} = function ($left, $right) {
  return strtolower($left) === strtolower($right);
};

type::$string->trim = function ($string) {
  return trim($string);
};

type::$string->upper = function ($string) {
  return strtoupper($string);
};

type::$string->lower = function ($string) {
  return strtolower($string);
};

type::$string->contains = function ($string) {
  return cmd('string.contains', $string, array('string' => function ($command, $search) use ($string) {
    return strpos($string, $search) !== false;
  }));
};

type::$string->split = function ($string, $context=null) {
  return cmd('string.split', $string, array('string' => function ($command, $by) use ($string, $context) {
    $arr = a($context);
    $arr->{'#value'} = explode($by, $string);
    return $arr;
  }));
};
