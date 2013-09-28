<?php

/**
 * Evaluate an array
 */
type::$array->{'#run'} = function ($object) {
  if(!isset($object->{'#source'})) {
    return $object;
  }
  $object->{'#value'} = array();
  foreach($object->{'#source'} as $source) {
    $object->{'#value'}[] = run($source);
  }
  return $object;
};

/**
 * Iterate over an array
 */
type::$array->{'#each'} = function ($object, $fn) {
  foreach($object->{'#value'} as $item) {
    $fn($item);
  }
};

/**
 * Get array property
 */
type::$array->{'#get'} = function ($object, $key) {
  $a = a($object);
  foreach($object->{'#value'} as $item) {
    $a->{'#value'}[] = get($item, $key);
  }
  return $a;
};

/**
 * Build an array from source map
 */
type::$array->{'#register'} = function ($object, $item) {
  /**
   * Operator: comma or Break
   * Append #register to #source and reset #register
   */
  if ($item->{'#type'} === 'break' ||
      ($item->{'#type'} === 'operator' && $item->value === ',')) {
    source($object, $object->{'#register'});
    reg_clear($object);
    return state($object, '#value');
  }
  
  /**
   * Operator, colon
   */
  else if ($item->{'#type'} === 'operator' && $item->value === ':') {
    throw new Exception('Operator : not allowed in array definition');
  }
  
  /**
   * Keep going
   */
  register($object, $item);
};
