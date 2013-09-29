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
 * Print an object
 */
type::$object->to_json = function ($object) {
  $arr = new stdClass;
  foreach($object as $key => $value) {
    if ($key[0] !== '#') {
      $arr->$key = $value;
    }
  }
  return json_encode($arr);
};

/**
 * Apply an array, grab keys
 */
type::$object->{'#apply array'} = function ($left, $right) {
  certify($right);
  $fn = proto($right)->{'#each'};
  $arr = array();
  $fn($right, function ($key) use (&$arr, $left) {
    
    $arr[] = get($left, $key);
  });
  $result = a($left);
  $result->{'#value'} = $arr;
  return $result;
};

/**
 * Evaluate an object
 */
type::$object->{'#run'} = type::$object->{'#trigger $'} = function ($object) {
  if(!isset($object->{'#source'})) {
    return $object;
  }
  
  foreach($object->{'#source'} as $source) {
    
    /**
     * Standalone statements
     */
    if (is_array($source)) {
      run($source, $object);
    }
    
    /**
     * Key-value pairs
     */
    else if (is_object($source)) {
      
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
