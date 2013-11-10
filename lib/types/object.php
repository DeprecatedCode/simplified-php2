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
 * Check if object property exists
 */
type::$object->{'#operator ??'} = function ($object, $key) {
  return property_exists($object, $key);
};

/**
 * Set object property
 */
type::$object->{'#operator ::'} = function ($object, $key) {
  return cmd('::', $object, array('*' => function ($command, $value) use ($object, $key) {
    $object->$key = $value;
  }));
};

/**
 * Iterate over an object's keys and values
 */
type::$object->{'#each'} = function ($object, $fn) {
  certify($object);
  $a = a($object);
  foreach($object as $key => $value) {
    if (strlen($key) && $key[0] === '#') {
      continue;
    }
    $a->{'#value'}[] = $fn($key, $value);
  }
  return $a;
};

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
  certify($object);
  $arr = (array) $object;
  $arr['#type'] = isset($object->{'#type'}) ? $object->{'#type'} : 'object';
  return json($arr, $level);
};

/**
 * Apply string to object
 */
type::$object->{'#apply string'} = function ($object, $string) {
  certify($object);
  if (is_object($object)) {
    return get($object, $string);
  }
  else {
    return apply($object, $string);
  }
};

/**
 * Apply object to object
 */
type::$object->{'#apply object'} = function ($left, $right) {
  $object = n($left);
  if (isset($left->{'#source'})) {
    $object->{'#source'} = $left->{'#source'};
  }
  $fn = type::$object->{'#each'};
  $fn($right, function ($key, $value) use ($object) {
    $object->$key = $value;
  });
  return $object;
};

/**
 * Apply array to object
 */
type::$object->{'#apply array'} = function ($object, $array) {
  certify($array);
  $fn = type::$array->{'#each'};
  $result = $object;
  $fn($array, function ($key, $item) use ($array, $object, &$result) {
    $object->it = $item;
    $object->key = $key;
    $result = run($object);
  });
  return $result;
};

/**
 * Allow iteration of object with object @ object
 */
type::$object->{'#operator @'} = function ($left, $right) {
  if (!is_object($right)) {
    throw new Exception("Invalid iteration expression after @");
  }
  $fn = type::$object->{'#each'};
  $a = $fn($left, function ($key, $item) use ($left, $right) {
    certify($item);
    $right->it = $item;
    $right->key = $key;
    return run($right);
  });
  return $a;
};

/**
 * Trigger evaluation
 */
type::$object->{'#trigger $'} = function ($object) {
  return run($object);
};

/**
 * Evaluate an object
 */
type::$object->{'#run'} = function ($object, $remote=null) {
  if(!isset($object->{'#source'})) {
    return $object;
  }
  
  if ($remote === null) {
    $remote = $object;
    $shouldReturn = true;
  } else {
    $shouldReturn = false;
  }
  
  $return = false;
  $result = null;
  
  $object->{'#prefix'} = null;
  $matched = false;
  
  foreach($object->{'#source'} as $source) {
    try {
    
      /**
       * Standalone statements
       */
      if (is_array($source)) {
        $return = true;
        $result = run($source, $remote);
      }
      
      /**
       * Key-value pairs
       */
      else if (is_object($source)) {
        
        /**
         * Simple keys (like #catch-all)
         */
        if (is_string($source->key)) {
          $key = $source->key;
          
          if ($key === '#catch-all' && !is_null($object->{'#prefix'})) {
            if (!$matched) {
              $return = true;
              $matched = true;
              $result = run($source->value, $remote);
            }
            continue;
          }
        }
        
        /**
         * Complex keys
         */
        else {
          
          /**
           * Check for prefix
           */
          $keySource = $source->key;
          foreach($keySource as $key => $item) {
            if ($item->{'#type'} === 'operator' && $item->value === '?') {
              $object->{'#prefix'} = array_splice($keySource, 0, $key);
              array_shift($keySource);
              $matched = false;
              break;
            }
          }
          
          /**
           * If prefix, return first matching expression
           */
          if (!is_null($object->{'#prefix'})) {
            if (!$matched) {
              $condition = array_merge($object->{'#prefix'}, $keySource);
              $test = run($condition, $remote);
              if ($test) {
                $result = run($source->value, $remote);
                $return = true;
                $matched = true;
              }
            }
            
            /**
             * Check next condition
             */
            continue;
          }
          
          /**
           * Single-identifier keys
           */
          if (count($keySource) === 1 && $keySource[0]->{'#type'} === 'identifier') {
            $key = $keySource[0]->value;
          }
          
          /**
           * Complex keys
           */
          else {
            $key = run($keySource, $remote);
          }
        }
        
        if (!is_string($key) && !is_int($key) && !is_float($key)) {
          throw new Exception("Object key must be a string or number");
        }
        
        /**
         * Values
         */
        $value = run($source->value, $remote);
        
        /**
         * Clone value if it is an object
         */
        if (is_object($value) && $value instanceof stdClass) {
          $value = clone $value;
        }
        
        /**
         * Result
         */
        $remote->{$key} = $value;
        
        /**
         * Do not return $result
         */
        $return = false;
      }
      
      /**
       * Error
       */
      else {
        throw new Exception("Invalid object source");
      }
      
    }
    
    catch (StopCommand $e) {
      break;
    }
  }
  
  /**
   * If there was a condition, and all conditions failed, return null
   */
  if (!$matched && !is_null($object->{'#prefix'})) {
    return null;
  }
  
  /**
   * If the object ends in a statement, return $result
   */
  if ($return) {
    return $result;
  }
  
  /**
   * If running with context, don't return
   */
  if (!$shouldReturn) {
    return;
  }
  
  /**
   * Don't return the original object when running
   */
  $x = n($remote->{'#parent'});
  foreach($remote as $key => $value) {
    if ($key[0] !== '#') {
      $x->$key = $value;
    }
  }
  return $x;
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
      
      /**
       * Handle catch-all assignment with star colon
       */
      if ($item->{'#type'} === 'operator' && $item->value === '*:') {

        if (reg_count($object) !== 0) {
          throw new Exception('Operator *: must not immediately follow a statement');
        }

        $object->{'#key'} = '#catch-all';
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
