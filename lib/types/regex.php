<?php

function regex($value, $context) {
  if (!is_string($value)) {
    throw new Exception("regex value must be a string");
  }
  $regex = obj('regex', $context);
  $regex->{'#done'} = true;
  $regex->{'#value'} = $value;
  return $regex;
}

type::$regex->to_json = function ($regex, $level=0) {
  $val = $regex->{'#value'};
  return json("/$val/", $level);
};
