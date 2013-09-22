<?php

/**
 * Capture SimplifiedPHP Code and Execute
 */
if (defined('SIMPLIFIED')) {
  try {
    $code = parse(ob_get_clean());
    get($code, 'do');
  }
  catch(Exception $e) {
    echo '<pre>';
    echo "Error: " . $e->getMessage();
    debug_print_backtrace();
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

/**
 * Capture SimplifiedPHP Code
 */
define('SIMPLIFIED', true);
ob_start();
