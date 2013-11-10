<?php

/**
 * SimplifiedPHP
 * @author Nate Ferrero
 */

/**
 * SimplifiedPHP Version
 */
if (!defined('VERSION')) {
  define('VERSION', '0.0.1');
  define('SPHP', __FILE__);
}

/**
 * Capture SimplifiedPHP Code and Execute
 */
if (defined('SIMPLIFIED')) {
  sphp_eval(ob_get_clean());
  exit;
}

/**
 * Done function for command line scripts
 */
else {
  function done () {
    sphp_eval(ob_get_clean());
    exit;
  }
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
 * Command Line Interface
 */
if (defined('CLI')) {
  require_once('lib/cli.php');
}

/**
 * Capture SimplifiedPHP Code
 */
define('SIMPLIFIED', true);
ob_start();
