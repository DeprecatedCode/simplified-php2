<?php

/**
 * All SimplifiedPHP Types
 */
class type {
  public static $array;
  public static $boolean;
  public static $command;
  public static $file;
  public static $float;
  public static $group;
  public static $integer;
  public static $null;
  public static $object;
  public static $string;
  public static $system;

  public static $types = ['array', 'boolean', 'command', 'file', 'float',
                          'group', 'integer', 'null', 'object', 'string',
                          'system'];
}

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
  if (is_object($scope)) {
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
        if (isset($parent->$key)) {
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
  throw new Exception("Setting not yet supported");
}
