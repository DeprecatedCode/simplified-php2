<?php

/**
 * Certify an object, make sure it has executed
 */
function certify($scope) {
  if (is_object($scope) && isset($scope->{'#type'}) &&
      $scope->{'#type'} !== 'proto' && !isset($scope->{'#runcount'})) {
    run($scope);
  }
}

/**
 * Is Operation?
 */
function is_operation($scope) {
  return is_object($scope) && isset($scope->{'#operator'});
}

/**
 * Construct Operation
 */
function operation($left, $operator) {
  
  /**
   * Triggers are instant operators, no right side needed
   */
  $proto = proto($left);
  if (isset($proto->{"#trigger $operator"})) {
    $fn = $proto->{"#trigger $operator"};
    return $fn($left);
  }
  
  /**
   * Construct the operation
   */
  $op = new stdClass;
  $op->{'#left'} = $left;
  $op->{'#operator'} = $operator;
  return $op;
}

/**
 * Operate - Perform an Operation (left + operator) on right
 */
function operate($op, $right, $context=null) {
  $operator = $op->{'#operator'};
  $left = $op->{'#left'};
  $proto = proto($left);
  
  /**
   * Check that operation is defined
   */
  $name = "#operator $operator";
  if (!isset($proto->$name)) {
    throw new Exception("Operator $operator not defined for type " . typestr($left));
  }
  
  /**
   * Check for identifiers
   */
  if (isset($right->{'#type'}) && $right->{'#type'} === 'identifier') {
    if ($operator === '.') {
      $right = $right->value;
    }
    else {
      $right = get($context, $right->value);
    }
  }
  
  /**
   * Do operation
   */
  $fn = $proto->$name;
  return $fn($left, $right, $context);
}

/**
 * Apply - The big bad boy of SimplifiedPHP
 */
function apply($left, $right) {
  $rtype = typestr($right);
  $proto = proto($left);
  $specific = "#apply $rtype";
  $generic = "#apply *";
  if (isset($proto->$specific)) {
    $fn = $proto->$specific;
  }
  else if (isset($proto->$generic)) {
    $fn = $proto->$generic;
  }
  else {
    throw new Exception(typestr($left) . " does not allow application of type $rtype");
  }
  return $fn($left, $right);
}

/**
 * Run code
 */
function run($scope, $context=null) {
  /**
   * This is a stack to process
   */
  if (is_array($scope)) {
    $register = null;
    foreach($scope as $source) {
      try {
        
        /**
         * Perform operations
         */
        if (is_operation($register)) {
          $register = operate($register, $source, $context);
        }
        
        /**
         * Apply values
         */
        else {
          switch ($source->{'#type'}) {
            case 'value':
              $register = apply($register, $source->value);
              break;
            case 'identifier':
              $register = apply($register, get($context, $source->value));
              break;
            case 'operator':
              $register = operation($register, $source->value);
              break;
            case 'break':
              throw new Exception("Invalid break found in source");
          }
        }
      }
      catch(Exception $e) {
        $l = $source->line;
        $c = $source->column;
        $m = $e->getMessage();
        $from = $e->getFile() . ':' . $e->getLine();
        throw new Exception("$m at $l:$c (from $from)", 0, $e);
      }
    }
    
    return $register;
  }
  
  /**
   * This is an object to process
   */
  else if (is_object($scope)) {
    /**
     * Track run count
     */
    $scope->{'#runcount'} = (isset($scope->{'#runcount'}) ?
      $scope->{'#runcount'} : 0) + 1;
    return get($scope, '#run');
  }
  
  /**
   * This is not runnable
   */
  else {
    return $scope;
  }
}
