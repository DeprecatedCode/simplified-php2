<?php

/**
 * Null allows any object to be itself
 */
type::$null->{'#apply *'} = function ($left, $right) {
  return $right;
};

/**
 * @ allows access to system objects
 */
type::$null->{'#operator @'} = function ($left, $right, $context) {
  if (!isset(type::$system->$right)) {
    throw new Exception("@$right is not a valid system object");
  }
  $fn = type::$system->$right;
  return $fn($context);
};
