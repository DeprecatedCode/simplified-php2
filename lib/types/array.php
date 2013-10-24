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
type::$array->to_json = function ($array, $level=0) {
  return json($array->{'#value'}, $level);
};

/**
 * Check if array key exists
 */
type::$array->{'#operator ??'} = function ($array, $key) {
  return array_key_exists($key, $array->{'#value'});
};


/**
 * Array length
 */
type::$array->length = function ($array) {
  return count($array->{'#value'});
};

/**
 * Array join
 */
type::$array->join = function ($array) {
  return cmd('join', $array, array('string' => function ($command, $string) use ($array) {
    return implode($string, $array->{'#value'});
  }));
};

/**
 * Apply an array to something that does not accept array application
 */
type::$array->{'#applied'} = function ($scope, $array) {
  $fn = type::$array->{'#each'};
  return $fn($array, function ($key, $item) use ($scope) {
    return apply($scope, $item);
  });
};

/**
 * Apply something generic to an array
 */
type::$array->{'#apply *'} = function ($array, $scope) {
  $fn = type::$array->{'#each'};
  return $fn($array, function ($key, $item) use ($scope) {
    return apply($item, $scope);
  });
};

/**
 * Apply an integer to get that offset
 */
type::$array->{'#apply integer'} = function ($array, $integer) {
  certify($array);
  return $array->{'#value'}[$integer];
};

/**
 * Iterate over an array
 */
type::$array->{'#each'} = function ($array, $fn) {
  certify($array);
  $a = a($array);
  $key = 0;
  foreach($array->{'#value'} as $item) {
    try {
      $a->{'#value'}[] = $fn($key++, $item);
    }
    catch (BreakCommand $break) {
      break;
    }
    catch (ContinueCommand $continue) {
      continue;
    }
  }
  return $a;
};

/**
 * Apply object to array
 */
type::$array->{'#apply object'} = function ($array, $object) {
  $fn = type::$array->{'#each'};
  $a = $fn($array, function ($key, $item) use ($array, $object) {
    $object->it = $item;
    $object->key = $key;
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
  certify($array);
  certify($keys);
  $a = a($array);
  foreach($keys->{'#value'} as $key) {
    if ($key < 0) {
      $key = $key + count($array->{'#value'});
    }
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
