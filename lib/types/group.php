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
    throw new Exception("Multiple statements are not permitted within a parenthetical group");
  }
  
  /**
   * Operator, colon
   */
  else if ($item->{'#type'} === 'operator' && $item->value === ':') {
    throw new Exception('Operator : not allowed in parenthetical group');
  }
  
  /**
   * Keep going
   */
  register($group, $item);
};
