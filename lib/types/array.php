<?php

type::$array->{'#register'} = function ($object, $item) {
  if ($item->type === 'operator') {
    
    /**
     * Operator: comma
     * Append #register to #source and reset #register
     */
    if ($item->value === ',') {
      source($object->{'#source'}, $object->{'#register'});
      reg_clear($object);
      return state($object, '#value');
    }
    
    /**
     * Operator, colon
     */
    else if ($item->value === ':') {
      throw new Exception('Operator : not allowed in array definition');
    }
  }
  register($object, $item);
};
