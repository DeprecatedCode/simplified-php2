<?php

function regex($value, $context) {
  if (!is_string($value)) {
    throw new Exception("regex value must be a string");
  }
  $regex = obj('regex', $context);
  $regex->{'#value'} = $value;
  return $regex;
}
