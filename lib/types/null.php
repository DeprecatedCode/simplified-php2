<?php

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

/**
 * & includes a variable in current context
 */
type::$null->{'#operator &'} = function ($left, $right, $context) {
  if (!is_object($context)) {
    throw new Exception("No valid context");
  }
  $context->$right = get($context, $right);
};
