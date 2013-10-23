<?php

/**
 * Capture SimplifiedPHP Code and Execute
 */
if (defined('SIMPLIFIED')) {
  try {
    $code = parse(ob_get_clean());
    run($code);
  }
  catch(Exception $e) {
    $fmt = function ($x) {
      return str_replace('d @', 'Debugger @',
        str_replace('.php', '',
          str_replace(__DIR__ . '/', '', $x)
        )
      );
    };
    echo '<pre><h2>SimplifiedPHP Exception</h2>';
    if ($e->getMessage() !== 'Debug') {
      echo $fmt($e->getMessage() . "\n\n ... @ " .
        $e->getFile() . ':' . $e->getLine());
    }
    foreach($e->getTrace() as $trace) {
      echo $fmt("\n $trace[function] @ $trace[file]:$trace[line]");
    }
    while ($orig = $e->getPrevious()) {
      echo "\n\n previously:\n";
      $e = $orig;
      foreach($e->getTrace() as $trace) {
        echo $fmt("\n $trace[function] @ $trace[file]:$trace[line]");
      }
    }
    echo '</pre>';
  }
  exit;
}

/**
 * Error Handler
 */
set_error_handler(function($num, $str, $file, $line) {
    throw new ErrorException($str, $num, 1, $file, $line);
});

/**
 * Include Engine
 */
require_once(__DIR__ . '/lib/engine.php');
require_once(__DIR__ . '/lib/parse.php');
require_once(__DIR__ . '/lib/run.php');
require_once(__DIR__ . '/lib/source.php');

/**
 * Capture SimplifiedPHP Code
 */
define('SIMPLIFIED', true);
ob_start();
