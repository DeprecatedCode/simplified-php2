<?php

/**
 * Make an array
 */
function arr($val, $parent=null) {
  $arr = a($parent);
  $arr->{'#value'} = $val;
  return $arr;
}

/**
 * Evaluate an array
 */
type::$array->{'#run'} = function ($array) {
  if(!isset($array->{'#source'})) {
    return $array;
  }
  $array->{'#value'} = array();
  $array->{'#running'} = true;
  foreach($array->{'#source'} as $source) {
    $array->{'#value'}[] = run($source, $array, true);
  }
  $array->{'#running'} = false;
  return $array;
};

/**
 * Array to JSON
 */
type::$array->to_json = function ($array, $level=0) {
  certify($array);
  return json($array->{'#value'}, $level);
};

/**
 * Check if array key exists
 */
type::$array->{'#operator ??'} = function ($array, $key) {
  return array_key_exists($key, $array->{'#value'});
};

/**
 * Check if array is equal
 */
type::$array->{'#operator ='} = function ($array, $other) {
  certify($array);
  certify($other);
  return $array->{'#value'} == $other->{'#value'};
};

/**
 * Array length
 */
type::$array->length = function ($array) {
  return count($array->{'#value'});
};

/**
 * Array sum
 */
type::$array->sum = function ($array) {
  return array_sum($array->{'#value'});
};

/**
 * Array join
 */
type::$array->join = function ($array) {
  return cmd('array.join', $array, array('string' => function ($command, $string) use ($array) {
    $result = array_map('stringify', $array->{'#value'});
    return implode(stringify($string), $result);
  }));
};

/**
 * Array push
 */
type::$array->push = function ($array) {
  return cmd('array.push', $array, array('*' => function ($command, $scope) use ($array) {
    return $array->{'#value'}[] = $scope;
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
type::$array->{'#apply object'} = function ($left, $right) {
  $array = a($left);
  if (isset($left->{'#source'})) {
    $array->{'#source'} = $left->{'#source'};
  }
  $fn = type::$object->{'#each'};
  $fn($right, function ($key, $value) use ($array) {
    $array->$key = $value;
  });
  return $array;
};

/**
 * Allow iteration of array with array @ object
 */
type::$array->{'#operator @'} = function ($array, $right) {
  if (!is_object($right)) {
    throw new Exception("Invalid iteration expression after @");
  }
  $fn = type::$array->{'#each'};
  $a = $fn($array, function ($key, $item) use ($array, $right) {
    certify($item);
    $right->it = $item;
    $right->key = $key;
    return run($right);
  });
  return $a;
};

/**
 * Get array property
 */
type::$array->{'#get'} = function ($array, $key) {
  if (isset($array->{'#running'}) && $array->{'#running'} === true) {
    if (property_exists($array, $key)) {
      return $array->$key;
    }
    $parent = isset($array->{'#parent'}) ? $array->{'#parent'} : null;
    return get($parent, $key);
  }
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
