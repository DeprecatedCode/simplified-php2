<?php

/**
 * Create a file object
 */
function ofile($path) {
  $file = obj('file');
  $file->path = $path;
  $file->name = basename($path);
  return $file;
}

type::$file->{'#run'} = function ($file) {
  return $file;
};

type::$file->print = function ($file) {
  echo '(@file "' . $file->path . '")';
};

type::$file->read = function ($file) {
  return file_get_contents($file->path);
};

type::$file->exists = function ($file) {
  return is_file($file->path);
};

type::$file->name = function ($file) {
  return basename($file->path);
};

type::$file->dir = function ($file) {
  return odir(dirname($file->path));
};

type::$file->delete = function ($file) {
  return unlink($file->path);
};

type::$file->write = function ($file) {
  return cmd('file.write', $file, array('*' => function ($command, $value) use ($file) {
    file_put_contents($file->path, $value);
  }));
};

type::$file->lines = function ($file, $context=null) {
  $arr = a($context);
  $arr->{'#value'} = file($file->path);
  return $arr;
};
