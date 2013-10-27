<?php

/**
 * Create a dir object
 */
function odir($path) {
  $dir = obj('dir');
  $dir->path = $path;
  $dir->name = basename($path);
  return $dir;
}

type::$dir->{'#run'} = function ($dir) {
  return $dir;
};

type::$dir->print = function ($dir) {
  echo '(@dir "' . $dir->path . '")';
};

type::$dir->exists = function ($dir) {
  return is_dir($dir->path);
};

type::$dir->ensure = function ($dir) {
  if (!is_dir($dir->path)) {
    mkdir($dir->path, 0777, true);
  }
};

type::$dir->file = function ($dir) {
  return cmd('dir.file', $dir, array(
    'string' => function ($cmd, $string) use ($dir) {

      $sep = strpos('/\\', substr($dir->path, -1)) !== false ?
        '' : DIRECTORY_SEPARATOR;

      return ofile($dir->path . $sep . $string);
    }
  ));
};

type::$dir->files = function ($dir) {
  $files = array();
  foreach(scandir($dir->path) as $path) {

    // Add a separator if path does not end with one
    $sep = strpos('/\\', substr($dir->path, -1)) !== false ?
      '' : DIRECTORY_SEPARATOR;

    $path = $dir->path . $sep . $path;

    if (is_file($path)) {
      $files[] = ofile($path);
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

    // Add a separator if path does not end with one
    $sep = strpos('/\\', substr($dir->path, -1)) !== false ?
      '' : DIRECTORY_SEPARATOR;

    $path = $dir->path . $sep . $path;

    if (is_dir($path)) {
      $dirs[] = odir($path);
    }
  }
  $arr = a($context);
  $arr->{'#value'} = $dirs;
  return $arr;
};
