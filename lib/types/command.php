<?php

/**
 * Create a Command
 */
function cmd($name, $scope, $definitions) {
  $cmd = obj('command', $scope);
  $cmd->{'#name'} = $name;
  foreach ($definitions as $types => $fn) {
    foreach (explode('|', $types) as $type) {
      $cmd->{"#apply $type"} = $fn;
    }
  }
  return $cmd;
}

/**
 * Evaluate Command
 * Do nothing since this is pre-apply
 */
type::$command->{'#run'} = function ($command) {
  return $command;
};

/**
 * Print Command
 */
type::$command->print = function ($command) {
  $types = array();
  foreach($command as $key => $value) {
    if (substr($key, 0, 6) === '#apply') {
      $types[] = substr($key, 7);
    }
  }
  $types = implode(' | ', $types);
  $name = $command->{'#name'};
  echo "(command $name: $types)";
};
