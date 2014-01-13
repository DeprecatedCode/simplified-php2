
<?php

/**
 * Debug
 */
function d($obj) {
  static $d = null;
  static $dd = null;
  if (is_null($d)) {
    $dd = $d = isset($_GET['__debug']) ? (int) $_GET['__debug'] : 0;
  }
  if ($dd > 0) {
    $dd--;
    return;
  }
  remove_parents($obj);
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
  echo '<p><a href="?__debug=' . ($d + 1) . '">Next Ocurrence &raquo;</a></p>';

  throw new Exception ('Debug');
}

/**
 * Recursively remove parents from object
 */
function remove_parents(&$obj) {
  if ((is_object($obj) && !$obj instanceof Closure) || is_array($obj)) {
    if (isset($obj->{'#parent'}) && is_object($obj->{'#parent'})) {
      $obj->{'#parent'} = '<#' . typestr($obj->{'#parent'}) . '>';
    }
    foreach($obj as $key => $value) {
      if (is_object($value)) {
        remove_parents($value);
      }
    }
  }
}

/**
 * Trace
 */
function trace($trace=null) {
  if (is_null($trace)) {
    $trace = debug_backtrace();
  }
  $headers = explode(' ', 'function line file args'); // class object type args
  echo '<table border=1><tr><th style="padding: 4px">' . implode('</th><th>', $headers) . '</th></tr>';
  
  foreach($trace as $line) {
    echo '<tr>';
    foreach($headers as $header) {
      echo '<td style="padding: 4px">';
      $item = isset($line[$header]) ? $line[$header] : '';
      if (is_array($item)) {
        $data = $item;
        foreach($data as $key => $value) {
          if (is_string($value)) {
            $data[$key] = json_encode($value);
          }
          if (is_object($value)) {
            $data[$key] = '<#' . typestr($value) . '>';
            if (typestr($value) === 'array') {
              $data[$key] = json_encode($value->{'#value'});
            }
          }
          if (is_array($value)) {
            $data[$key] = "array: " . count($value) . ' items';
          }
          $item = $data;
        }
        $item = implode(', ', $item);
      }
      echo htmlspecialchars(str_replace(__DIR__, '', $item)) . '</td>';
    }
    echo '</tr>';
  }
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
  $scope->{'#parent'} = $parent;
  return $scope;
}

/**
 * Construct new Group
 */
function g(&$parent, $line=null, $column=null) {
  $scope = n($parent, $line, $column);
  $scope->{'#type'} = 'group';
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
 * Value
 */
function v(&$scope, $value, $line=null, $column=null) {
  try {
    $fn = proto($scope)->{'#register'};
    $item = new stdClass;
    $item->{'#type'} = 'value';
    $item->value = $value;
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
 * Identifier
 */
function i(&$scope, $name, $line=null, $column=null) {
  try {
    $fn = proto($scope)->{'#register'};
    $item = new stdClass;
    $item->{'#type'} = 'identifier';
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
    $item->{'#type'} = 'operator';
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
    $item->{'#type'} = 'break';
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
  $item->value = $val;
  return $item;
}
