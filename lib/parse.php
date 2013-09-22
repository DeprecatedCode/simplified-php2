<?php

function parse($code) {
  static $root;
  if (!isset($root)) {
    $root = new stdClass;
    $root->request = new stdClass;
    $root->request->args = (object) $_GET;
    $root->request->form = (object) $_POST;
    $root->request->method = $_SERVER['REQUEST_METHOD'];
    $root->request->time = time(true);
  }
  $scope = _o($root);
  
  $file = dirname(__DIR__) . '/cache/' . substr(md5($code), 0, 8) . '.php';
  $php = '<?php';
  
  file_put_contents($file, $php);
  
  return require($file);
}