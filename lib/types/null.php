<?php

type::$null->{'#operator ='} = function ($left, $right) {
  return $left === $right;
};

/**
 * @ allows access to system objects
 */
type::$null->{'#operator @'} = function ($null, $right, $context) {
  if (!isset(type::$system->$right)) {
    throw new Exception("@$right is not a valid system object");
  }
  $fn = type::$system->$right;
  return $fn($context);
};

/**
 * & includes a variable in current context
 */
type::$null->{'#operator &'} = function ($null, $right, $context) {
  if (!is_object($context)) {
    throw new Exception("No valid context");
  }
  $context->$right = get($context, $right);
  
  return $context;
};

/**
 * ?? checks variable existence in current context
 */
type::$null->{'#operator ??'} = function ($null, $right, $context) {
  if (!is_object($context)) {
    throw new Exception("No valid context");
  }
  try {
    get($context, $right);
    return true;
  }
  catch(Exception $e) {
    return false;
  }
};

/**
 * Print is noop
 */
type::$null->print = null;

/**
 * ~ converts a string into a regex object
 */
type::$null->{'#operator ~'} = function ($null, $right, $context) {
  return regex($right, $context);
};

/**
 * ++ increment variable operator
 */
type::$null->{'#operator ++'} = function ($null, $key, $context) {
  if (!is_object($context)) {
    throw new Exception("No valid context");
  }
  return cmd('++', $context, array('integer|float' =>
    function ($command, $value) use ($key, $context) {
      $current = get($context, $key);
      set($context, $key, $current + $value);
      return $current;
    }
  ));
};
