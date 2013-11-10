<?php

/**
 * SimplifiedPHP
 * @author Nate Ferrero
 */

$opts = getopt('c:hv', array('init', 'help', 'version'));

/**
 * Initialize SimplifiedPHP with Apache + PHP
 */
if (isset($opts['init'])) {

  /**
   * Make sure the user wants to edit the file
   */
  echo "[ ] Adding SPHP to Apache .htaccess file\n";
  echo "[ ] Are you sure? This will create/edit the following file:\n";
  $hta = '.htaccess';
  $dir = getcwd();
  echo "\n    $dir/$hta\n\n";
  echo "[ ] If you wish to continue, type yes: ";
  $input = fgets(STDIN);
  if (trim($input) !== 'yes') {
    echo "[ ] Operation canceled!\n";
    exit;
  }

  $str = file_exists($hta) ? file_get_contents($hta) : '';
  
  $re_prepend = '/php_value auto_prepend_file .*/i';
  $re_append = '/php_value auto_append_file .*/i';
  
  $hta_prepend = "php_value auto_prepend_file " . SPHP;
  $hta_append = "php_value auto_append_file " . SPHP;
  
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

/**
 * Version
 */
if (isset($opts['v']) || isset($opts['version'])) {
  echo "SimplifiedPHP version " . VERSION . "\n";
  exit;
}

/**
 * Help
 */
if (isset($opts['h']) || isset($opts['help'])) {
  $dir = dirname(__DIR__);
  echo "SimplifedPHP by Nate Ferrero
  
SPHP Install Location:
  $dir
  
Usage:
  sphp                            # Interactive mode
  sphp file.php                   # Execute SimplifiedPHP file
  sphp --code \"1+2.print\"   [-c]  # Execute SimplifiedPHP code
  sphp --init                     # Add SimplifiedPHP to .htaccess for Apache
  sphp --help               [-h]  # Show this help
  sphp --version            [-v]  # Show version information\n";
  exit;
}

/**
 * Code
 */
if (isset($opts['c'])) {
  sphp_eval($opts['c']);
  exit;
}

/**
 * Code
 */
if (isset($opts['code'])) {
  sphp_eval($opts['code']);
  exit;
}

/**
 * File
 */
if (isset($argv[1])) {
  sphp_eval(file_get_contents($argv[1]));
  exit;
}

/**
 * Interactive mode
 */
$context = obj();
while (true) {
  echo "> ";
  $line = fgets(STDIN);
  if ($line === false) {
    echo "\nexiting...\n";
    exit;
  }
  ob_start();
  $result = sphp_eval($line, $context);
  if (!is_null($result) && $result !== $context) {
    $context->_ = $result;
  }
  get($result, 'print');
  $captured = ob_get_clean();
  if (strlen($captured) > 0) {
    echo "$captured\n";
  }
}