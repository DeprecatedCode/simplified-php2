<?php

/**
 * Evaluate an array
 */
type::$array->{'#run'} = function ($array) {
  if(!isset($array->{'#source'})) {
    return $array;
  }
  $array->{'#value'} = array();
  foreach($array->{'#source'} as $source) {
    $array->{'#value'}[] = run($source, isset($array->{'#parent'}) ? $array->{'#parent'} : null);
  }
  return $array;
};

/**
 * Array to JSON
 */
type::$array->to_json = function ($array) {
  return json_encode($array->{'#value'});
};

/**
 * Iterate over an array
 */
type::$array->{'#each'} = function ($array, $fn) {
  certify($array);
  $a = a($array);
  foreach($array->{'#value'} as $item) {
    $a->{'#value'}[] = $fn($item);
  }
  return $a;
};

/**
 * Apply object to array
 */
type::$array->{'#apply object'} = function ($array, $object) {
  $fn = type::$array->{'#each'};
  $a = $fn($array, function ($item) use ($array, $object) {
    $object->it = $item;
    return run($object);
  });
  return $a;
};

/**
 * Get array property
 */
type::$array->{'#get'} = function ($array, $key) {
  $a = a($array);
  foreach($array->{'#value'} as $item) {
    $a->{'#value'}[] = get($item, $key);
  }
  return $a;
};

/**
 * Iterate over an array
 */
type::$array->{'#apply array'} = function ($array, $keys) {
  certify($keys);
  $a = a($array);
  foreach($keys->{'#value'} as $key) {
    if (!array_key_exists($key, $array->{'#value'})) {
      throw new Exception("Key $key not found in array");
    }
    $a->{'#value'}[] = $array->{'#value'}[$key];
  }
  return $a;
};

/**
 * Build an array from source map
 */
type::$array->{'#register'} = function ($array, $item) {

  /**
   * Operator: comma or Break
   * Append #register to #source and reset #register
   */
  if ($item->{'#type'} === 'break' ||
      ($item->{'#type'} === 'operator' && $item->value === ',')) {
    if (isset($array->{'#register'}) && count($array->{'#register'})) {
      source($array, $array->{'#register'});
      reg_clear($array);
    }
    return state($array, '#value');
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
  register($array, $item);
};
