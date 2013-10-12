<?php

/**
 * System Import SimplifiedPHP File
 */
type::$system->import = function ($context) {
  return cmd('@import', $context, array('string' => function ($command, $string) use ($context) {
    if ($string == '') {
      throw new Exception("Cannot import empty string");
    }
    
    /**
     * Future imports are relative to the file the @import is defined in
     * regardless of the current file being executed
     */
    try {
      $dir = get($context, '#directory');
      if ($string[0] !== '/') {
        $string = $dir . $string;
      }
    }
    catch(Exception $e) {}
    $code = parse(file_get_contents($string), $context);
    $code->{'#directory'} = dirname(realpath($string)) . '/';
    return run($code);
  }));
};

/**
 * System Parent
 */
type::$system->parent = function ($context) {
  return is_object($context) && isset($context->{'#parent'}) ? $context->{'#parent'} : null;
};

/**
 * System Request
 */
type::$system->request = function ($context) {
  static $request;
  if (!is_object($request)) {
    $request = obj('object');
    $request->method = $_SERVER['REQUEST_METHOD'];

    $request->args = (object) $_GET;
    $request->form = (object) $_POST;
    $request->cookie = (object) $_COOKIE;

    $request->host = $_SERVER['HTTP_HOST'];
    $request->path = $_SERVER['REQUEST_URI'];
    $request->query = $_SERVER['QUERY_STRING'];
    $name = explode('?', $request->path);
    $name = array_shift($name);
    $name = explode('/', $name);
    $request->basename = array_pop($name);

    $request->remote_addr = $_SERVER['REMOTE_ADDR'];
    $request->remote_port = $_SERVER['REMOTE_PORT'];

    $request->protocol = $_SERVER['SERVER_PROTOCOL'];
    $request->time = $_SERVER['REQUEST_TIME_FLOAT'];
    
    $request->file = obj('file');
    $request->file->path = $_SERVER['SCRIPT_FILENAME'];
  }
  return $request;
};

/**
 * System Timer
 */
type::$system->timer = function () {
  return number_format(1000 * microtime(true) - 1000 * sys::$timer, 1);
};

/**
 * Finally
 */
type::$system->finally = function($context) {
  return cmd('@finally', $context, array('object' => function ($command, $object) {
    sys::$finally[] = $object;
  }));
};

/**
 * Null
 */
type::$system->null = function($context) {
  return null;
};

/**
 * Continue
 */
type::$system->continue = function($context) {
  throw new ContinueCommand("@continue");
};

/**
 * Continue Exception
 */
class ContinueCommand extends InternalException {}

/**
 * Break
 */
type::$system->break = function($context) {
  throw new BreakCommand("@break");
};

/**
 * Break Exception
 */
class BreakCommand extends InternalException {}

/**
 * System Class
 */
class sys {
  public static $finally = array();
  public static $timer = null;
}

/**
 * Start timer
 */
sys::$timer = microtime(true);

/**
 * Process Finally
 */
register_shutdown_function(function () {
  foreach(sys::$finally as $object) {
    run($object);
  }
});
