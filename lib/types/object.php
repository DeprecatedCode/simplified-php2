<?php

type::$object->{'do'} = function ($object) {
  d($object);
};

type::$object->{'#register'} = function ($object, $item) {
  switch(state($object)) {
    case '#init':
      
      /**
       * Handle code execution on newline
       */
      if ($item->type === 'break') {
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
      if ($item->type === 'operator' && $item->value === ':') {

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
      if (($item->type === 'operator' && $item->value === ',') || 
          ($item->type === 'break')) {

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
      else if ($item->type === 'operator' && $item->value === ':') {
        throw new Exception('Operator : not allowed in object without corresponding key');
      }
  }
  register($object, $item);
};
