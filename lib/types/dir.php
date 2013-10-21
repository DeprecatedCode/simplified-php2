<?php

type::$dir->{'#run'} = function ($dir) {
  return $dir;
};

type::$dir->ensure = function ($dir) {
  if (!is_dir($dir->path)) {
    mkdir($dir->path, 0777, true);
  }
};

type::$dir->files = function ($dir) {
  $files = array();
  foreach(scandir($dir->path) as $path) {
    $path = str_replace('//', '/', $dir->path . '/' . $path);
    if (is_file($path)) {
      $files[] = $path;
    }
  }
  $arr = a($context);
  $arr->{'#value'} = $files;
  return $arr;
};
