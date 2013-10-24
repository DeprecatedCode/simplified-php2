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
      $file = obj('file');
      $file->path = $path;
      $file->name = basename($path);
      $files[] = $file;
    }
  }
  $arr = a($context);
  $arr->{'#value'} = $files;
  return $arr;
};

type::$dir->dirs = function ($dir) {
  $dirs = array();
  foreach(scandir($dir->path) as $path) {
    if ($path == '.' || $path == '..') {
      continue;
    }
    $path = str_replace('//', '/', $dir->path . '/' . $path);
    if (is_dir($path)) {
      $dir = obj('dir');
      $dir->path = $path;
      $dir->name = basename($path);
      $dirs[] = $dir;
    }
  }
  $arr = a($context);
  $arr->{'#value'} = $dirs;
  return $arr;
};
