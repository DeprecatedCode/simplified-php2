<?php

/**
 * Create an object
 */
function obj($type = 'object', $parent = null) {
  $obj = new stdClass;
  $obj->{'#type'} = $type;
  $obj->{'#parent'} = $parent;
  return $obj;
}

/**
 * JSON encode
 */
function json($scope, $level=0) {
  if (is_array($scope)) {
    $output = array();
    if (isset($scope['#type']) && $scope['#type'] === 'object') {
      foreach($scope as $key => $item) {
        if ($key[0] !== '#') {
          $output[$key] = is_object($item) || is_array($item) ? json($item, $level + 1) : $item;
        }
      }
    }
    else {
      foreach($scope as $item) {
        $output[] = is_object($item) || is_array($item) ? json($item, $level + 1) : $item;
      }
    }
    $scope = $output;
  }

  else {
    $proto = proto($scope);
    if (isset($proto->to_json)) {
      $fn = $proto->to_json;
      $scope = $fn($scope, $level + 1);
    }
  }

  if ($level === 0) {
    return json_encode($scope, JSON_PRETTY_PRINT);
  }

  return $scope;
}

/**
 * Print an object
 */
type::$object->print = function ($object) {
  echo get($object, 'to_json');
};

/**
 * Object to JSON
 */
type::$object->to_json = function ($object, $level=0) {
  $arr = (array) $object;
  return json($arr, $level);
};

/**
 * Apply array to object
 */
type::$object->{'#apply array'} = function ($object, $array) {
  certify($array);
  $fn = type::$array->{'#each'};
  $result = $object;
  $fn($array, function ($item) use ($array, $object, &$result) {
    $object->it = $item;
    $result = run($object);
  });
  return $result;
};

/**
 * Evaluate an object
 */
type::$object->{'#run'} = type::$object->{'#trigger $'} = function ($object) {
  if(!isset($object->{'#source'})) {
    return $object;
  }
  
  $return = false;
  $result = null;
  
  foreach($object->{'#source'} as $source) {
    
    /**
     * Standalone statements
     */
    if (is_array($source)) {
      $result = run($source, $object);
      $return = true;
    }
    
    /**
     * Key-value pairs
     */
    else if (is_object($source)) {
      
      /**
       * Do not return $result
       */
      $return = false;
      
      /**
       * Single-identifier keys
       */
      if (count($source->key) === 1 && $source->key[0]->{'#type'} === 'identifier') {
        $key = $source->key[0]->value;
      }
      
      /**
       * Complex keys
       */
      else {
        $key = run($source->key, $object);
      }
      
      if (!is_string($key) && !is_int($key) && !is_float($key)) {
        throw new Exception("Object key must be a string or number");
      }
      
      /**
       * Values
       */
      $value = run($source->value, $object);
      
      /**
       * Result
       */
      $object->{$key} = $value;
    }
    
    /**
     * Error
     */
    else {
      throw new Exception("Invalid object source");
    }
  }
  
  /**
   * If the object ends in a statement, return $result
   */
  if ($return) {
    return $result;
  }
  
  return $object;
};

/**
 * Build an object from source map
 */
type::$object->{'#register'} = function ($object, $item) {
  switch(state($object)) {
    case '#init':
      
      /**
       * Handle code execution on newline
       */
      if (($item->{'#type'} === 'operator' && $item->value === ',') || 
          ($item->{'#type'} === 'break')) {
        if(reg_count($object)) {
          source($object, $object->{'#register'});
          reg_clear($object);
          $object->{'#key'} = array();
        }
        return;
      }
      
      /**
       * Handle key-value assignment with colon
       */
      if ($item->{'#type'} === 'operator' && $item->value === ':') {

        if (reg_count($object) === 0) {
          throw new Exception('Operator : not allowed in object without corresponding key');
        }

        $object->{'#key'} = $object->{'#register'};
        reg_clear($object);
        return state($object, '#value');
      }
      break;

    case '#value':
      /**
       * Operator: comma or break
       * Append #key, #register to #source and reset #key and #register
       */
      if (($item->{'#type'} === 'operator' && $item->value === ',') || 
          ($item->{'#type'} === 'break')) {

        if (reg_count($object) === 0) {
          throw new Exception('Operator : not allowed in object without corresponding value');
        }

        $entry = keyval($object->{'#key'}, $object->{'#register'});
        source($object, $entry);
        reg_clear($object);
        $object->{'#key'} = array();
        return state($object, '#init');
      }
      
      /**
       * Operator, colon
       */
      else if ($item->{'#type'} === 'operator' && $item->value === ':') {
        throw new Exception('Operator : not allowed in object without corresponding key');
      }
  }
  register($object, $item);
};
