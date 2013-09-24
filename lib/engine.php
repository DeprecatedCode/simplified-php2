<?php

/**
 * Debug
 */
function d($obj) {
  echo '<pre>';
  ob_start();
  var_dump($obj);
  $debug = ob_get_clean();
  $debug = str_replace('["', '"',
    str_replace('"]=>', '": ',
      str_replace('(stdClass)', '',
        preg_replace('/\n?\s*?\&?(object|string).*?(#\d+\s)?\(\d+\)\s+/m', '',
          preg_replace('/\n?.+?(int|float)\(([0-9.\-e]+)\)/', '\2',
            preg_replace('/\n?\s+?array\(\d+\)\s+/ms', 'array ',
              preg_replace('/\[\d+\]\=\>/', '', $debug)
            )
          )
        )
      )
    )
  );
  echo htmlspecialchars($debug);
  throw new Exception ('Debug');
}

/**
 * Construct new Object
 */
function n(&$parent, $line=null, $column=null) {
  $scope = new stdClass;
  $scope->{'#type'} = 'object';
  if (!is_null($line) && !is_null($column)) {
    $scope->{'#line'} = $line;
    $scope->{'#column'} = $column;
  }
  $scope->{'#parent'} = &$parent;
  return $scope;
}

/**
 * Construct new Array
 */
function a(&$parent, $line=null, $column=null) {
  $scope = n($parent, $line, $column);
  $scope->{'#type'} = 'array';
  $scope->{'#value'} = array();
  return $scope;
}

/**
 * Construct new File
 */
function f(&$parent, $line=null, $column=null) {
  $scope = n($parent, $line, $column);
  $scope->{'#type'} = 'file';
  return $scope;
}

/**
 * Value
 */
function v(&$scope, $value, $line=null, $column=null) {
  try {
    $fn = proto($scope)->{'#register'};
    $item = new stdClass;
    $item->type = 'value';
    $item->value = $value;
    $item->line = $line;
    $item->column = $column;
    $fn($scope, $item);
  }
  catch (Exception $e) {
    throw new Exception($e->getMessage() . " at $line:$column", 0, $e);
  }
}

/**
 * Identifier
 */
function i(&$scope, $name, $line=null, $column=null) {
  try {
    $fn = proto($scope)->{'#register'};
    $item = new stdClass;
    $item->type = 'identifier';
    $item->value = $name;
    $item->line = $line;
    $item->column = $column;
    $fn($scope, $item);
  }
  catch (Exception $e) {
    throw new Exception($e->getMessage() . " at $line:$column", 0, $e);
  }
}

/**
 * Operator
 */
function o(&$scope, $name, $line=null, $column=null) {
  try {
    $fn = proto($scope)->{'#register'};
    $item = new stdClass;
    $item->type = 'operator';
    $item->value = $name;
    $item->line = $line;
    $item->column = $column;
    $fn($scope, $item);
  }
  catch (Exception $e) {
    throw new Exception($e->getMessage() . " at $line:$column", 0, $e);
  }
}

/**
 * Break
 */
function b(&$scope, $void, $line=null, $column=null) {
  try {
    $fn = proto($scope)->{'#register'};
    $item = new stdClass;
    $item->type = 'break';
    $item->value = $void;
    $item->line = $line;
    $item->column = $column;
    $fn($scope, $item);
  }
  catch (Exception $e) {
    $from = $e->getFile() . ':' . $e->getLine();
    throw new Exception($e->getMessage() . " at $line:$column (from $from)", 0, $e);
  }
}

/**
 * Object and Array State
 */
function state($scope, $set = null) {
  if (!isset($scope->{'#state'}) || !is_null($set)) {
    $scope->{'#state'} = $set ? $set : '#init';
  }
  return $scope->{'#state'};
}

/**
 * Register Item
 */
function register($object, $item) {
  if (!reg_count($object)) {
    reg_clear($object);
  }
  $object->{'#register'}[] = $item;
}

/**
 * Register Item Count
 */
function reg_count($object) {
  if(!isset($object->{'#register'}) || !is_array($object->{'#register'})) {
    return 0;
  }
  return count($object->{'#register'});
}

/**
 * Clear Register
 */
function reg_clear($object) {
  $object->{'#register'} = array();
}

/**
 * Object and Array Source
 */
function source($object, $item) {
  if (!isset($object->{'#source'}) || !is_array($object->{'#source'})) {
    $object->{'#source'} = array();
  }
  $object->{'#source'}[] = $item;
}

/**
 * Create key-value pair
 */
function keyval($key, $val) {
  $item = new stdClass;
  $item->key = $key;
  $item->val = $val;
  return $item;
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
    $proto = proto($scope);
    return get($proto, $key, $instance);
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
  throw new Exception("Setting not yet supported");
}
