<?php

/**
 * Null allows any object to be itself
 */
type::$null->{'#apply *'} = function ($left, $right) {
  return $right;
};
