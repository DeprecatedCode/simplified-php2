<?php

/**
 * All SimplifiedPHP Types
 */
class type {
  public static $array;
  public static $boolean;
  public static $command;
  public static $dir;
  public static $file;
  public static $float;
  public static $group;
  public static $integer;
  public static $null;
  public static $object;
  public static $regex;
  public static $string;
  public static $system;

  public static $types = ['array', 'boolean', 'command', 'dir', 'file', 'float',
                          'group', 'integer', 'null', 'object', 'regex',
                          'string', 'system'];
}

/**
 * Pretty print exception
 */
function exc($e) {
  $buf = '';
  $fmt = function ($x) {
    return str_replace('d @', 'Debugger @',
      str_replace('.php', '',
        str_replace(__DIR__ . '/', '', $x)
      )
    );
  };
  if ($e->getMessage() !== 'Debug') {
    $buf .= $fmt(get_class($e) . ': ' . $e->getMessage() . "\n\n ... @ " .
      $e->getFile() . ':' . $e->getLine());
  }
  foreach($e->getTrace() as $trace) {
    $buf .= $fmt("\n $trace[function] @ $trace[file]:$trace[line]");
  }
  while ($orig = $e->getPrevious()) {
    $buf .= "\n\n previously:\n";
    $e = $orig;
    foreach($e->getTrace() as $trace) {
      $buf .= $fmt("\n $trace[function] @ $trace[file]:$trace[line]");
    }
  }
  
  return $buf;
}

/**
 * Internal Exception
 */
class InternalException extends Exception {}

/**
 * Include type definitions
 */
foreach(type::$types as $type) {
  type::$$type = new stdClass;
  type::$$type->{'#type'} = 'proto';
  type::$$type->{'#proto'} = $type;
  
  /**
   * Standard . operator
   */
  type::$$type->{'#operator .'} = function ($left, $right, $context) {
    return get($left, $right);
  };
  
  /**
   * Standard ! operator
   */
  type::$$type->{'#trigger !'} = function ($context) {
    return !$context;
  };
  
  /**
   * Standard || operator
   */
  type::$$type->{'#operator ||'} = function ($left, $right, $context) {
    if ($left) {
      return $left;
    }
    return $right;
  };
  
  /**
   * Standard && operator
   */
  type::$$type->{'#operator &&'} = function ($left, $right, $context) {
    if (!$left) {
      return $left;
    }
    return $right;
  };
  
  /**
   * Standard to_json property
   */
  type::$$type->{'to_json'} = function ($context) {
    return json_encode($context);
  };
    
  /**
   * Include lib/types/$type.php
   */
  require_once(__DIR__ . '/types/' . $type . '.php');
}

/**
 * Get Prototype Object
 */
function proto(&$scope) {
  $type = typestr($scope);
  if ($type === 'proto') {
    throw new Exception("Cannot access prototype of prototype");
  }
  return isset(type::$$type) ? type::$$type : type::$object;
}

/**
 * Get Object TypeStr
 */
function typestr(&$scope) {
  
  /**
   * Automatically coerce PHP arrays into the desired type when used
   */
  if (is_array($scope) || $scope instanceof Traversable) {
    
    $transformed = false;
    if (is_array($scope)) {
      for (reset($scope); is_int(key($scope)); next($scope));
    
      /**
       * Associative array
       */
      if (!is_null(key($scope))) {
        $scope = (object) $scope;
        $transformed = true;
      }
    }
    
    if (!$transformed) {
      $parent = null;
      $arr = a($parent);
      $arr->{'#value'} = $scope;
      $scope = $arr;
    }
  }
  
  if (is_object($scope) && !($scope instanceof Closure)) {
    $type = isset($scope->{'#type'}) ? $scope->{'#type'} : null;
    if (!$type) {
      $type = 'object';
    }
    return $type;
  }
  else if (is_string($scope)) {
    return 'string';
  }
  else if (is_int($scope)) {
    return 'integer';
  }
  else if (is_float($scope)) {
    return 'float';
  }
  else if (is_bool($scope)) {
    return 'boolean';
  }
  else if (is_null($scope)) {
    return 'null';
  }
  else {
    throw new Exception("Invalid type");
  }
}

/**
 * Get Property
 */
function get(&$scope, $key, $instance = null) {

  /**
   * Handle different property types
   */
  if(is_object($key)) {
    if (isset($key->type)) {
      switch($key->type) {
        case 'operator':
          throw new Exception("Unexpected operator $key->value");
        case 'identifier':
        case 'value':
          $key = $key->value;
      }
    }

    if (is_object($key)) {
      throw new Exception("Property name must be a string or number");
    }
  }
  
  if (is_null($instance) && (!isset($scope->{'#proto'}) || $scope->{'#proto'} !== 'null')) {
    $instance = $scope;
  }
  
  /**
   * If object
   */
  if (is_object($instance) && !isset($instance->{'#done'})) {
    $instance = $scope = run($instance);
  }

  /**
   * Handle Scalars
   */
  if (!is_object($scope)) {
    $proto = proto($scope);
    return get($proto, $key, $instance);
  }
  
  /**
   * Ensure that the instance is ready
   */
  certify($instance);
  
  /**
   * Handle Objects
   */
  while(true) {
    $proto = !isset($scope->{'#type'}) || $scope->{'#type'} !== 'proto' ? proto($scope) : null;
    
    /**
     * Hash properties
     */
    if (!is_null($proto) && isset($proto->{$key})) {
      $value = $proto->{$key};
      break;
    }

    /**
     * Handle magic #get override in proto
     */
    else if (!is_null($proto) && isset($proto->{'#get'})) {
      $fn = $proto->{'#get'};
      $value = $fn($instance, $key);
      break;
    }

    /**
     * Handle magic #get override on object
     */
    else if (isset($scope->{'#get'})) {
      $fn = $scope->{'#get'};
      $value = $fn($instance, $key);
      break;
    }
    
    /**
     * Check regular object properties
     */
    else if (property_exists($scope, $key)) {
      $value = $scope->$key;
      break;
    }
    
    /**
     * Search in parent scopes
     */
    else {
      $parent = $scope;
      while(isset($parent->{'#parent'})) {
        $parent = $parent->{'#parent'};
        if (property_exists($parent, $key)) {
          $value = $parent->$key;
          break 2;
        }
      }
    }
    
    /**
     * Otherwise, check prototype (if prototype exists)
     */
    if (!is_null($proto)) {
      return get($proto, $key, $instance);
    }

    /**
     * We checked everywhere
     */
    throw new Exception("Property $key not found on " . typestr($instance));
  }

  if ($value instanceof Closure) {
    return $value($instance);
  }
  return $value;
}

/**
 * Set Property
 */
function set(&$scope, $key, $value) {
  /**
   * First check in parents
   */
  $parent = $scope;
  while(isset($parent->{'#parent'})) {
    $parent = $parent->{'#parent'};
    if (property_exists($parent, $key)) {
      $parent->$key = $value;
      return $value;
    }
  }
  
  /**
   * Otherwise, set new variable
   */
  $scope->$key = $value;
  return $value;
}

/**
 * JSON encode
 */
function json($scope, $level=0) {
  $object = false;
  if (is_array($scope)) {
    $output = array();
    if (isset($scope['#type']) && $scope['#type'] === 'object') {
      $object = true;
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
  
  if ($object) {
    $scope = (object) $scope;
  }

  if ($level === 0) {
    return json_encode($scope, JSON_PRETTY_PRINT);
  }

  return $scope;
}
