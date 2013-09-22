<?php

type::$object->do = function ($object) {d($object);
  
  if (!isset($object->{'#definition'})) {
    return $object;
  }
  
  $result = n($object);
  
  foreach($object->{'#definition'} as $def) {
    $key = isset($def['key']) ? $def['key'] : null;
    
    if ($key instanceof Closure) {
      $key = $key();
    }
    
    $value = isset($def['value']) ? $def['value'] : null;
    
    if ($value instanceof Closure) {
      $value = $value();
    }
    
    if (!is_null($key)) {
      $result->$key = $value;
    }
  }
  
  return $result;
};
