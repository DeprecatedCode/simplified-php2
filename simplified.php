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
    echo '<pre><h2>SimplifiedPHP Exception in ' . $_SERVER['SCRIPT_NAME'] . ' </h2>';
    echo exc($e);
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
