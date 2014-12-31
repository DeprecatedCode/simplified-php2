<?php

/**
 * Evaluate a group
 */
type::$group->{'#run'} = function ($group) {
  if (!isset($group->{'#register'})) {
    return null;
  }
  return run($group->{'#register'}, $group);
};

/**
 * Build a group from source map
 */
type::$group->{'#register'} = function ($group, $item) {

  /**
   * Operator: comma or Break
   * Append #register to #source and reset #register
   */
  if ($item->{'#type'} === 'break' ||
      ($item->{'#type'} === 'operator' && $item->value === ',')) {
    
    /**
     * Allow breaks if the register is empty
     */
    if (reg_count($group) === 0) {
      return;
    }
    
    $group->{'#break'} = true;
    return;
  }
  
  /**
   * Operator, colon
   */
  else if ($item->{'#type'} === 'operator' && $item->value === ':') {
    throw new Exception('Operator : not allowed in parenthetical group');
  }
  
  /**
   * If we already hit a break after a statement, don't allow new statement
   */
  if (isset($group->{'#break'}) && $group->{'#break'}) {
    throw new Exception("Multiple statements are not permitted within a parenthetical group");
  }

  /**
   * Keep going
   */
  register($group, $item);
};

/**
 * Group to Source String
 */
type::$group->to_source = function ($group) {
  $code = array();
  foreach ($group->{'#source'} as $item) {
    foreach($item as $seg) {
      $val = $seg->value;
      if (!is_string($val)) {
        $val = get($val, 'to_source');
      }
      $code[] = $val;
    }
  }
  return '(' . join($code, ' ') . ')';
};