<?php

/**
 * System Import SimplifiedPHP File
 */
type::$system->import = function ($context) {
  return cmd('@import', $context, array('string' => function ($command, $string) use ($context) {
    $code = parse(file_get_contents($string), $context);
    return run($code);
  }));
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
 * Finally
 */
type::$system->finally = function($context) {
  return cmd('@finally', $context, array('object' => function ($command, $object) {
    sys::$finally[] = $object;
  }));
};

/**
 * System Class
 */
class sys {
  public static $finally = array();
}

/**
 * Process Finally
 */
register_shutdown_function(function () {
  foreach(sys::$finally as $object) {
    run($object);
  }
});
