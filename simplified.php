<?php

/**
 * Command Line Interface
 */
if (defined('CLI') && !defined('XCLI')) {
  define('XCLI', true);

  $simplified = __FILE__;
  $opts = getopt('c:', array('init'));
  
  /**
   * Initialize SimplifiedPHP with Apache + PHP
   */
  if (isset($opts['init'])) {
    echo "[ ] Adding SPHP to Apache .htaccess file\n";
    $hta = '.htaccess';
    $str = file_exists($hta) ? file_get_contents($hta) : '';
    
    $re_prepend = '/php_value auto_prepend_file .*/i';
    $re_append = '/php_value auto_append_file .*/i';
    
    $hta_prepend = "php_value auto_prepend_file $simplified";
    $hta_append = "php_value auto_append_file $simplified";
    
    /**
     * Update if pre/append values exist
     */
    $str = preg_replace($re_prepend, $hta_prepend, $str);
    $str = preg_replace($re_append, $hta_append, $str);
    
    /**
     * Otherwise, add the php_value directives
     */
    if (!preg_match($re_prepend, $str)) {
      $str .= "\n" . $hta_prepend;
    }
    if (!preg_match($re_append, $str)) {
      $str .= "\n" . $hta_append;
    }
    
    /**
     * Write .htaccess
     */
    file_put_contents($hta, $str);
    echo "[x] Done!\n";
    exit;
  }
  
  if (isset($opts['c'])) {
    require($simplified);
    echo $opts['c'];
    done();
  }
  
  if (isset($argv[1])) {
    require($simplified);
    echo file_get_contents($argv[1]);
    done();
  }
  
  echo "SimplifedPHP by Nate Ferrero

Usage:
  sphp -c \"1+2.print\"   # Execute SimplifiedPHP code
  sphp file.php         # Execute SimplifiedPHP file
  sphp --init           # Add SimplifiedPHP to .htaccess for Apache
";
  
  exit;
}

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
 * Done function for command line scripts
 */
else {
  function done () {
    require(__FILE__);
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
 * Capture SimplifiedPHP Code
 */
define('SIMPLIFIED', true);
ob_start();
