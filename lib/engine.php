<?php

/**
 * Construct new Object
 */
function _o(&$parent) {
  $scope = new stdClass;
  $scope->{'#type'} = 'object';
  $scope->{'#parent'} = &$parent;
  return $scope;
}

/**
 * Construct new Array
 */
function _a(&$parent) {
  $scope = obj($parent);
  $scope->{'#type'} = 'array';
  $scope->{'#value'} = array();
  return $scope;
}

/**
 * Construct new File
 */
function _f(&$parent) {
  $scope = obj($parent);
  $scope->{'#type'} = 'file';
  return $scope;
}

/**
 * All SimplifiedPHP Types
 */
class type {
  public static $array;
  public static $boolean;
  public static $file;
  public static $float;
  public static $integer;
  public static $null;
  public static $object;
  public static $string;
  
  public static $types = ['string', 'integer', 'float', 'boolean', 'null',
                          'object', 'array', 'file'];
}

/**
 * Include type definitions
 */
foreach(type::$types as $type) {
  type::$$type = new stdClass;
  type::$$type->{'#type'} = 'proto';
  require_once(__DIR__ . '/types/' . $type . '.php');
}

/**
 * Get Prototype Object
 */
function proto(&$scope) {
  if (is_object($scope)) {
    $type = $scope->{'#type'};
    if (!$type) {
      $type = 'object';
    }
    if ($type === 'proto') {
      throw new Exception("Cannot access prototype of prototype");
    }
    return type::$$type;
  }
  else if (is_string($scope)) {
    return type::$string;
  }
  else if (is_int($scope)) {
    return type::$integer;
  }
  else if (is_float($scope)) {
    return type::$float;
  }
  else if (is_bool($scope)) {
    return type::$boolean;
  }
  else if (is_null($scope)) {
    return type::$null;
  }
  else {
    throw new Exception("Invalid type");
  }
}

/**
 * Get Property
 */
function get(&$scope, $key, $instance = null) {
  if (is_null($instance)) {
    $instance = $scope;
  }
  
  /**
   * Handle Scalars
   */
  if (!is_object($scope)) {
    return get(proto($scope), $key, $instance);
  }
  
  /**
   * Handle Objects
   */
  while(true) {
    $proto = null;
    if ($scope->{'#type'} !== 'proto') {
      $proto = proto($scope);
      if (isset($proto->{'#get'})) {
        $value = $proto->{'#get'};
        break;
      }
    }
    else if (isset($scope->$key)) {
      $value = $scope->$key;
      break;
    }
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
     * Check prototype if exists
     */
    if (!is_null($proto)) {
      return get($proto, $key, $instance);
    }
    
    throw new Exception("Property $key not found");
  }
  if (is_callable($value)) {
    return $value($instance, $key);
  }
  return $value;
}

/**
 * Set Property
 */
function set(&$scope, $key, $value) {
  throw new Exception ("Setting not yet supported");
}
